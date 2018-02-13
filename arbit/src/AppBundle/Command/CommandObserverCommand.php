<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandObserverCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('command:observer')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $em = $this
                ->getContainer()
                ->get('doctrine')
                ->getManager();
            $qb = $em->createQueryBuilder('t');
            $qb->select('t.id, t.enabled, t.task, t.period, t.lastStart')
                ->from('AppBundle:Crontask', 't');
            $query = $qb->getQuery();
            $tasks = $query->getArrayResult();

            foreach ($tasks as $key => $value) {
                if ($value["enabled"] && time() >= $value["lastStart"] + $value["period"]){
                    $task = $this
                        ->getContainer()
                        ->get('doctrine')
                        ->getRepository('AppBundle:Crontask')
                        ->find($value["id"]);
                    $task->setLastStart(time());
                    $em->persist($task);
                    $em->flush();
                    $output->writeln("task id: " . $value["id"] . " in progress");
                    system($value["task"]);
                }
            }
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            $output->writeln(json_encode($err));
        }
    }

}
