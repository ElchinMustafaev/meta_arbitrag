<?php

namespace ApiBundle\Command;

use ccxt\bitfinex2;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addOption('wallet1', null, InputOption::VALUE_REQUIRED, 'first wallet')
         //   ->addOption("wallet2", null, InputOption::VALUE_REQUIRED, "second wallet")
         //   ->addOption("percent", null, InputOption::VALUE_REQUIRED, "percent rate")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set("UTC");

        $first_wallet = $input->getOption("wallet1");
        //$second_wallet = $input->getOption("wallet2");
        //$percent_rate = $input->getOption("percent");

        try {
            $first_record = $this->getContainer()->
                get("doctrine")
                ->getManager()
                ->getRepository('ApiBundle:ApiKey')
                ->findOneBy(array(
                    "wallet" => $first_wallet,
                    )
                );


            $bitfinex = new bitfinex2();
            $bitfinex->apiKey = $first_record->getKey();
            $bitfinex->secret =$first_record->getSecretKey();
            print_r($bitfinex->fetch_balance());
            $bitfinex->load_markets();
            $markets = $bitfinex->market("BTC/USD");
            print_r($markets);

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getFile());
            $output->writeln($e->getLine());
        }
    }

}
