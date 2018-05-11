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
        if ($result[0]["status"] != 0) {

            $info = $trade_helper
                ->analyse(
                    $result[0]["firstExchange"],
                    $result[0]["secondExchange"],
                    $result[0]["firstCoin"] . "/" . $result[0]["secondCoin"],
                    $result[0]["userName"]
                );

            print_r($info);
            $this->runSystem($result, $info);

            $result = $query->getResult();
            print_r(memory_get_usage(true));
            echo "\n";
            print_r($result);
        } else {
            system("pm2 stop bot.sh");
        }

    }

    public function runSystem(array $result, array $info)
    {
        if ((($result[0]["status"] == 1) && ($info["spread"] >= $result[0]["targetPercent"])) ||
            (($result[0]["status"] == 2) && ($info["rev spread"] <= $result[0]["targetRevSpread"]))) {
            system("php bin/console api:trade " .
                "--fe=" . $result[0]["firstExchange"] . " --se=" . $result[0]["secondExchange"] .
                " --user_name=" . $result[0]["userName"] .
                " --ft=" . $result[0]["firstCoin"] . " --st=" . $result[0]["secondCoin"] .
                " --sb=" . $result[0]["startBalance"] . " --step=" . $result[0]["status"] . " --id=" .
                $result[0]["id"]
            );
        }
    }

}
