<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OrdersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:order')
            ->setDescription('check cost and start trade')
            ->addOption('fe', null, InputOption::VALUE_REQUIRED, 'first wallet')
            ->addOption("se", null, InputOption::VALUE_OPTIONAL, "second wallet")
            ->addOption("user_name", null, InputOption::VALUE_REQUIRED, "user name")
            ->addOption("ft", null, InputOption::VALUE_REQUIRED, "first token")
            ->addOption("st", null, InputOption::VALUE_REQUIRED, "second token")
            ->addOption("sb", null, InputOption::VALUE_REQUIRED, "start balance")
            ->addOption("feb", null, InputOption::VALUE_REQUIRED, "first exchange bid")
            ->addOption("sea", null, InputOption::VALUE_REQUIRED, "second exchange ask")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set("UTC");
        $time = time();
        $first_exchange = $input->getOption("fe");
        $second_exchange = $input->getOption("se");
        $first_token = $input->getOption("ft");
        $second_token = $input->getOption("st");
        $user_name = $input->getOption("user_name");
        $start_balance = $input->getOption("sb");
        $first_ex_bid = $input->getOption("feb");
        $second_ex_ask = $input->getOption("sea");



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


        $first_order = $exchanges_array[$first_exchange]
            ->takeOrder(
                $user_name,
                $first_token . "/" . $second_token,
                $start_balance,
                "sell",
                $first_ex_bid
            );


        $second_order = $exchanges_array[$second_exchange]
            ->takeOrder(
                $user_name,
                $first_token . "/" . $second_token,
                $start_balance,
                "buy",
                $second_ex_ask
            );
        print_r($first_order);
        echo "\n";
        print_r($second_order);
        echo "\n";
        do {
            sleep(1);
            $first_status =
                $exchanges_array[$first_exchange]
                    ->getInfoByOrder(
                        $user_name,
                        $first_order,
                        $first_token . "/" . $second_token,
                        $time - 10800);

            $second_status =
                $exchanges_array[$second_exchange]
                    ->getInfoByOrder(
                        $user_name,
                        $second_order,
                        $first_token . "/" . $second_token,
                        $time - 10800);
        } while ((($first_status != "closed") || (time() >= $time + 10800)) && (($second_status != "closed") || (time() >= $time + 10800)));


        if ($first_status == "closed" && $second_status == "closed") {
            exit("closed\n");
        } else {
            exit("error\n");
        }
    }

}
