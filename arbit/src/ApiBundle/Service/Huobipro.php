<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 05.02.18
 * Time: 19:30
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Huobipro
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
                        "exchange" => "huobipro",
                        "users" => $name,
                    )
                );

            $huobipro = new \ccxt\huobipro();
            $huobipro->apiKey = $db_record->getKey();
            $huobipro->secret = $db_record->getSecretKey();

            $huobipro->load_markets(true);

            $orders = $huobipro->fetch_order_book($pair);

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