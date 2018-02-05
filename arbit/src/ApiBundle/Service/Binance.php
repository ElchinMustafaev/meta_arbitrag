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

            $binance->load_markets(true);
            $markets = $binance->market($pair);

            $binance_bid = $markets["info"]['bidPrice'];
            $binance_ask = $markets['info']['askPrice'];

            return array(
                "bid" => $binance_bid,
                "ask" => $binance_ask,
            );
        } catch (\Exception $e) {
            return array(
                "bid" => $e->getMessage(),
                "ask" => $e->getMessage(),
            );
        }
    }

}