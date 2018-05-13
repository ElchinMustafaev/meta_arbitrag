<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ControllCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:control')
            ->setDescription('...')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //ini_set('memory_limit', '-1');
        set_time_limit(-1);
        $trade_helper = $this->getContainer()->get("api.trade");
        $em = $this
            ->getContainer()
            ->get("doctrine")
            ->getManager()
        ;

        $qb = $em
            ->createQueryBuilder('t');
        $qb->select(
                "t.status, t.stopLose, t.takeProfit, t.startBalance,
                 t.targetPercent, t.userName, t.firstExchange, t.secondExchange, t.firstCoin, t.secondCoin, 
                 t.firstStepBidStatus, t.firstStepAskStatus, t.secondStepBidStatus, t.secondStepAskStatus, 
                 t.id, t.targetRevSpread"
            )
            ->from("AppBundle:TradeInfo", "t")
        ;
        $query = $qb->getQuery();
        $result = $query->getResult();
        print_r($result);

        foreach ($result as $value) {
            if ($value["status"] != 0) {

                $info = $trade_helper
                    ->analyse(
                        $value["firstExchange"],
                        $value["secondExchange"],
                        $value["firstCoin"] . "/" . $value["secondCoin"],
                        $value["userName"]
                    );

                print_r($value["firstCoin"] . "/" . $value["secondCoin"]);
                echo ("\n");
                print_r($info);
                $this->runSystem($value, $info);
            }
        }

    }

    public function runSystem(array $value, array $info)
    {
        if ((($value["status"] == 1) && ($info["spread"] >= $value["targetPercent"])) ||
            (($value["status"] == 2) && ($info["rev spread"] <= $value["targetRevSpread"]))) {
            system("php bin/console api:trade " .
                "--fe=" . $value["firstExchange"] . " --se=" . $value["secondExchange"] .
                " --user_name=" . $value["userName"] .
                " --ft=" . $value["firstCoin"] . " --st=" . $value["secondCoin"] .
                " --sb=" . $value["startBalance"] . " --step=" . $value["status"] . " --id=" .
                $value["id"]
            );
        }
    }

}
