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

            $livecoin->load_markets(true);

            $orders = $livecoin->fetch_order_book($pair);

            $i = $j = 0;
            $bids = $asks = 0;
            foreach ($orders["bids"] as $key => $value) {
                $i++;
                $bids += $value[0];
            }
            $aver_bid = $bids / $i;

            foreach ($orders["asks"] as $key => $value) {
                $j++;
                $asks += $value[0];
            }
            $aver_ask = $asks / $j;

            return array(
                "bid" => $aver_bid,
                "ask" => $aver_ask,
            );
        } catch (\Exception $e) {
            return array(
                "bid" => $e->getMessage(),
                "ask" => $e->getMessage(),
            );
        }

    }
}