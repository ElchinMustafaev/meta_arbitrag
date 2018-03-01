<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 21.02.2018
 * Time: 18:39
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Bittrex
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
                        "exchange" => "bittrex",
                        "users" => $name,
                    )
                );

            $bittrex = new \ccxt\bittrex();
            $bittrex->apiKey = $db_record->getKey();
            $bittrex->secret = $db_record->getSecretKey();

            $bittrex->load_markets(true);
            $markets = $bittrex->market($pair);

            $bittrex_bid = $markets["info"]['bidPrice'];
            $bittrex_ask = $markets['info']['askPrice'];

            return array(
                "bid" => $bittrex_bid,
                "ask" => $bittrex_ask,
            );
        } catch (\Exception $e) {
            return array(
                "bid" => $e->getMessage(),
                "ask" => $e->getMessage(),
            );
        }

    }

}