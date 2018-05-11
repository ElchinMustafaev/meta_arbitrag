<?php

namespace ApiBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiTradeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:trade')
            ->setDescription('check cost and start trade')
            ->addOption('fe', null, InputOption::VALUE_REQUIRED, 'first wallet')
            ->addOption("se", null, InputOption::VALUE_OPTIONAL, "second wallet")
            ->addOption("user_name", null, InputOption::VALUE_REQUIRED, "user name")
            ->addOption("ft", null, InputOption::VALUE_REQUIRED, "first token")
            ->addOption("st", null, InputOption::VALUE_REQUIRED, "second token")
            ->addOption("sb", null, InputOption::VALUE_REQUIRED, "start balance")
            ->addOption("step", null, InputOption::VALUE_REQUIRED, "step")
            ->addOption("id", null, InputOption::VALUE_REQUIRED, "id in db")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            date_default_timezone_set("UTC");

            $first_exchange = $input->getOption("fe");
            $second_exchange = $input->getOption("se");
            $first_token = $input->getOption("ft");
            $second_token = $input->getOption("st");
            $user_name = $input->getOption("user_name");
            $start_balance = $input->getOption("sb");
            $step = $input->getOption('step');
            $id = $input->getOption("id");

            $record = $this
                ->getContainer()
                ->get("doctrine")
                ->getRepository("AppBundle:TradeInfo")
                ->find($id)
            ;

            $exchanges_array = array(
                "livecoin" => $this->getContainer()->get("api.livecoin"),
                "bitmex" => $this->getContainer()->get("api.bitmex"),
                "binance" => $this->getContainer()->get("api.binance"),
                "cryptopia" => $this->getContainer()->get("api.cryptopia"),
                "gdax" => $this->getContainer()->get("api.gdax"),
                "hitbtc" => $this->getContainer()->get("api.hitbtc"),
                "huobipro" => $this->getContainer()->get("api.huobipro"),
                "kraken" => $this->getContainer()->get("api.kraken"),
                "okex" => $this->getContainer()->get("api.okex"),
                "bittrex" => $this->getContainer()->get("api.bittrex"),
                "bithumb" => $this->getContainer()->get("api.bithumb"),
            );

            if ($step == 1) {
                $first_exchange = $exchanges_array[$first_exchange];
                $second_exchange = $exchanges_array[$second_exchange];
            } elseif($step == 2) {
                $first_exchange = $exchanges_array[$second_exchange];
                $second_exchange = $exchanges_array[$first_exchange->getName()];
            } else {
                exit("error haven't step");
            }

            $rate = $this->getPercentRate($first_exchange, $second_exchange, $first_token, $second_token);
            $first_balance = $first_exchange->getBalance($user_name, $first_token, $second_token);
            $first_ex_first_token_balance = $first_balance[0];


            if ($start_balance <= $first_ex_first_token_balance) {
                $second_balance = $second_exchange->getBalance($user_name, $first_token, $second_token);
                $second_ex_second_token_balance = $second_balance[1];


                if (!empty($first_ex_first_token_balance) && !empty($second_ex_second_token_balance))
                {
                    $second_ex_order_balance =
                        $start_balance * $rate["first rate"]["bid"] * $second_exchange->getFeePercent();
                    if ($second_ex_order_balance <= $second_ex_second_token_balance) {

                        $status = system(" php bin/console api:order " .
                            "--fe=" . $first_exchange->getName() .
                            " --se=" . $second_exchange->getName() . " --user_name=" . $user_name .
                            " --ft=" . $first_token . " --st=" . $second_token ." --sb=" . $start_balance .
                            " --feb=" . $rate["first rate"]["bid"] .
                            " --sea=" . $rate["second rate"]["ask"]
                        );

                        if ($step == 1) {
                            if ($status == "closed") {
                                $record->setStatus(2);
                                $record->setFirstStepBidStatus(true);
                                $record->setFirstStepAskStatus(true);
                            }
                        } else {
                            if ($status == "closed") {
                                $record->setStatus(0);
                                $record->setSecondStepBidStatus(true);
                                $record->setSecondStepAskStatus(true);
                            }
                        }
                        $em = $this
                            ->getContainer()
                            ->get("doctrine")
                            ->getManager()
                        ;
                        $em->persist($record);
                        $em->flush();
                        die("end step\n");
                    } else {
                        die("low balance ex2 token2\n");
                    }
                } else {
                    exit("balance = 0?\n");
                }
            } else {
                exit("invalid balance, u don't have money\n");
            }
        } catch (\Exception $e) {
            $err_arr = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            print_r($err_arr);
        }

    }


    public function getPercentRate($first_exchange, $second_exchange, $first_token, $second_token)
    {
        try {
            $first_rate = $first_exchange->mainFunction("", $first_token . "/" . $second_token);
            $second_rate = $second_exchange->mainFunction("", $first_token . "/" . $second_token);

            return array(
                "first rate" => $first_rate,
                "second rate" => $second_rate,
            );
        } catch (\Exception $e) {
            $err_arr = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            print_r($err_arr);
        }
    }


}
