<?php

namespace ApiBundle\Command;

use ccxt\bitfinex2;
use ccxt\bitmex;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ApiBundle\Service\Livecoin;

class ApiTradeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:trade')
            ->setDescription('check cost and start trade')
            ->addOption('exchange1', null, InputOption::VALUE_REQUIRED, 'first wallet')
            ->addOption("exchange2", null, InputOption::VALUE_REQUIRED, "second wallet")
            ->addOption("user_name", null, InputOption::VALUE_REQUIRED, "user name")
            ->addOption("pair", null, InputOption::VALUE_REQUIRED, "pair")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set("UTC");

        $first_exchange = $input->getOption("exchange1");
        $second_exchange = $input->getOption("exchange2");
        $pair = $input->getOption("pair");
        $user_name = $input->getOption("user_name");


        $exchanges_array = array(
            "livecoin" => $this->getContainer()->get("api.livecoin"),
            "bitmex" => $this->getContainer()->get("api.bitmex"),
            "binance" => $this->getContainer()->get("api.binance"),
            "cryptopia" => $this->getContainer()->get("api.cryptopia"),
        );

        try {


            $first_result = $exchanges_array[$first_exchange]->mainFunction($user_name, $pair);
            $second_result = $exchanges_array[$second_exchange]->mainFunction($user_name, $pair);

            $result = array(
                "pair" => $pair,
                "first exchange" => $first_exchange,
                "second exchange" => $second_exchange,

            );

            if (is_numeric($first_result["bid"]) && is_numeric($second_result["bid"])) {
                $spreads = array(
                    $first_exchange . " bid" => $first_result["bid"],
                    $first_exchange . " ask" => $first_result["ask"],
                    $second_exchange . " bid" => $second_result["bid"],
                    $second_exchange . " ask" => $second_result["ask"],
                    "bid diff" => $first_result["bid"] - $second_result["bid"],
                    "ask diff" => $first_result["ask"] - $second_result["ask"],
                    "bid  spread" => ($first_result["bid"] / $second_result["bid"] - 1) * 100,
                    "ask spread" => ($first_result["ask"] / $second_result["ask"] -1) * 100,
                );
                $result = array_merge($result, $spreads);
            } else {
                $err = array(
                    $first_exchange => $first_result["bid"],
                    $second_exchange => $second_result["bid"],
                );
                $result = array_merge($result, $err);
            }
            print_r($result);



        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getFile());
            $output->writeln($e->getLine());
        }
    }
}
