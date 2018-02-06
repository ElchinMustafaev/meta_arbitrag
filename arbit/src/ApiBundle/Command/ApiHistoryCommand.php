<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\History;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiHistoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:history')
            ->setDescription('generate history info for exchanges')
            ->addOption("user_name", null, InputOption::VALUE_REQUIRED, "user name")
            ->addOption("pair", null, InputOption::VALUE_REQUIRED, "pair")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set("UTC");
        try {
            ini_set("memory_limit", -1);
            $pair = $input->getOption("pair");
            $user_name = $input->getOption("user_name");

            $exchanges_array = array(
                "livecoin" => $this->getContainer()->get("api.livecoin"),
                "bitmex" => $this->getContainer()->get("api.bitmex"),
                "binance" => $this->getContainer()->get("api.binance"),
                "cryptopia" => $this->getContainer()->get("api.cryptopia"),
                "gdax" => $this->getContainer()->get("api.gdax"),
                "hitbtc" => $this->getContainer()->get("api.hitbtc"),
                "huobipro" => $this->getContainer()->get("api.huobipro"),
                "kraken" => $this->getContainer()->get("api.kraken"),
            );

            foreach ($exchanges_array as $key1 => $value1) {
                foreach ($exchanges_array as $key2 => $value2) {
                    if ($key1 != $key2) {
                        $new_record = new History();

                        $first_result = $exchanges_array[$key1]->mainFunction($user_name, $pair);
                        $second_result = $exchanges_array[$key2]->mainFunction($user_name, $pair);

                        $new_record->setExchange1($key1);
                        $new_record->setExchange2($key2);
                        $new_record->setTimeStamp(time());
                        $new_record->setPair($pair);

                        if (is_numeric($first_result["bid"]) && is_numeric($second_result["bid"])) {
                            $new_record->setSpread(($first_result["bid"] / $second_result["ask"] - 1) * 100);
                            $new_record->setRevSpread(($first_result["ask"] / $second_result["bid"] - 1) * 100);
                        } else {
                            $new_record->setSpread($first_result["bid"]);
                            $new_record->setRevSpread($second_result["bid"]);
                        }
                        $em = $this->getContainer()->get('doctrine')->getManager();
                        $em->persist($new_record);
                        $em->flush();
                        unset($new_record, $first_result, $second_result, $em);
                    }
                }
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

}
