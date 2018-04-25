<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 05.02.18
 * Time: 18:07
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Cryptopia
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

            $cryptopia = new \ccxt\cryptopia();

            $bid = ($cryptopia->fetch_ticker ($pair)['bid']);
            $ask = ($cryptopia->fetch_ticker ($pair)['ask']);

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

    public function getBalance($name)
    {
        $db_record = $this
            ->em
            ->getRepository('ApiBundle:ApiKey')
            ->findOneBy(array(
                    "exchange" => "cryptopia",
                    "users" => $name,
                )
            );

        $cryptopia = new \ccxt\cryptopia();
        $cryptopia->apiKey = $db_record->getKey();
        $cryptopia->secret = $db_record->getSecretKey();

        return $cryptopia->fetch_balance();
    }

    public function takeOrder($name, $pair, $balance, $side, $price)
    {
        $db_record = $this
            ->em
            ->getRepository('ApiBundle:ApiKey')
            ->findOneBy(array(
                    "exchange" => "cryptopia",
                    "users" => $name,
                )
            );

        $cryptopia = new \ccxt\cryptopia();
        $cryptopia->apiKey = $db_record->getKey();
        $cryptopia->secret = $db_record->getSecretKey();

        return $cryptopia->create_order($pair, "limit", $side, $balance, $price);
    }

    public function getName()
    {
        return "cryptopia";
    }
}