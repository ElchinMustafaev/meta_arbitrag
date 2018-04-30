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
                 t.firstStepBidStatus, t.firstStepAskStatus, t.secondStepBidStatus, t.secondStepAskStatus, t.id"
            )
            ->from("AppBundle:TradeInfo", "t")
        ;
        $query = $qb->getQuery();
        $result = $query->getResult();
        
    }

}
