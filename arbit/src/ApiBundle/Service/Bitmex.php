<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 02.02.2018
 * Time: 16:05
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Bitmex
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
        $db_record = $this
            ->em
            ->getRepository('ApiBundle:ApiKey')
            ->findOneBy(array(
                    "exchange" => "bitmex",
                    "users" => $name,
                )
            );

        $bitmex = new \ccxt\bitmex();
        $bitmex->apiKey = $db_record->getKey();
        $bitmex->secret = $db_record->getSecretKey();

        $bitmex->load_markets(true);
        $markets = $bitmex->market($pair);

        $bitmex_bid = $markets["info"]['bidPrice'];
        $bitmex_ask = $markets['info']['askPrice'];

        return array(
            "bid" => $bitmex_bid,
            "ask" => $bitmex_ask,
        );

    }

}