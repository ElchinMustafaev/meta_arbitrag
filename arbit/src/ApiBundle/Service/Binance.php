<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 03.02.2018
 * Time: 22:47
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Binance
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
                        "exchange" => "binance",
                        "users" => $name,
                    )
                );

            $binance = new \ccxt\binance();
            $binance->apiKey = $db_record->getKey();
            $binance->secret = $db_record->getSecretKey();


            $bid = ($binance->fetch_ticker ($pair)['bid']);
            $ask = ($binance->fetch_ticker ($pair)['ask']);

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