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

    /**
     * @param $name
     * @param $pair
     * @return array
     */
    public function mainFunction(
        $name,
        $pair
    )
    {
        try {
            $livecoin = new \ccxt\livecoin();

            $tikers = $livecoin->fetch_ticker ($pair);
            $bid = ($tikers['bid']);
            $ask = ($tikers['ask']);

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

    /**
     * @param $name
     * @param $first_token
     * @param $second_token
     * @return array
     */
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
        if (!empty($first_token_balance) || !empty($second_token_balance)) {
            return array(
                $first_token_balance,
                $second_token_balance
            );
        } else {
            die("there are not balance on " . $this->getName() . "\n");
        }
    }

    /**
     * @param $name
     * @param $pair
     * @param $balance
     * @param $side
     * @param $price
     * @return mixed
     */
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

        $order = $livecoin->create_order($pair, "limit", $side, $balance, $price);
        if (is_array($order)) {
            return $order['info']['orderId'];
        }

    }

    /**
     * @return float
     */
    public function getFeePercent()
    {
        return 1.02;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "livecoin";
    }

    public function getInfoByOrder($name, $id, $pair = "", $time = "")
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
        $orders = $livecoin->fetchClosedOrders($pair, $time);
        if (is_array($orders)) {
            foreach ($orders as $key => $value) {
                if ($value['info']['id'] = $id) {
                    return "closed";
                }
            }
            return "open";
        } else {
            return "error";
        }

    }


}