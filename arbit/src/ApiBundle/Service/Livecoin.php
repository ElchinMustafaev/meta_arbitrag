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
            $livecoin = new \ccxt\livecoin();

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

    public function getBalance($name, $first_token, $second_token)
    {
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

        $balance = $livecoin->fetch_balance();

        foreach ($balance["info"] as $key => $value) {
            if ($value["currency"] == $first_token && $value['type'] == "available") {
                $first_token_balance = $value['value'];
            } elseif ($value["currency"] == $second_token && $value['type'] == "available") {
                $second_token_balance = $value['value'];
            }
        }
        if (!empty($first_token_balance) && !empty($second_token_balance)) {
            return array(
                $first_token_balance,
                $second_token_balance
            );
        } else {
            die("there are not balance");
        }
    }

    public function takeOrder($name, $pair, $balance, $side, $price)
    {
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

        return $livecoin->create_order($pair, "limit", $side, $balance, $price);

    }

    public function getName()
    {
        return "livecoin";
    }
}