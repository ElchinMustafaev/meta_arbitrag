<?php

/**
 * Created by PhpStorm.
 * User: el
 * Date: 02.02.2018
 * Time: 15:15
 */
namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Livecoin
{
    private $contailer;

    private $em;

    /**
     * Livecoin constructor.
     *
     * @param ContainerInterface $container
     * @param \Doctrine\ORM\EntityManager $em
     */
    function __construct(ContainerInterface $container, \Doctrine\ORM\EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }


    public function mainFunction(
        $name,
        $pair
    )
    {
        try {
            $db_record = $this
                ->em
                ->getRepository('ApiBundle:ApiKey')
                ->findOneBy(array(
                        "exchange" => "livecoin",
                        "users" => $name,
                    )
                );

            $livecoin = new \ccxt\livecoin();
            $livecoin->apiKey = $db_record->getKey();
            $livecoin->secret = $db_record->getSecretKey();

            $bid = ($livecoin->fetch_ticker ($pair)['bid']);
            $ask = ($livecoin->fetch_ticker ($pair)['ask']);

            return array(
                "bid" => $bid,
                "ask" => $ask,
            );
        } catch (\Exception $e) {
            return array(
                "bid" => $e->getMessage(),
                "ask" => $e->getMessage(),
            );
        }

    }
}