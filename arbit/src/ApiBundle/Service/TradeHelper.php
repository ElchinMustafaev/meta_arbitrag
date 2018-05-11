<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 01.03.18
 * Time: 18:51
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TradeHelper
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
     * @param $exchange1
     * @param $exchange2
     * @param $pair
     * @param $user_name
     *
     * @return array
     */
    public function analyse($exchange1, $exchange2, $pair, $user_name)
    {
        try {
            $exchanges_array = array(
                "livecoin" => $this->container->get("api.livecoin"),
                "bitmex" => $this->container->get("api.bitmex"),
                "binance" => $this->container->get("api.binance"),
                "cryptopia" => $this->container->get("api.cryptopia"),
                "gdax" => $this->container->get("api.gdax"),
                "hitbtc" => $this->container->get("api.hitbtc"),
                "huobipro" => $this->container->get("api.huobipro"),
                "kraken" => $this->container->get("api.kraken"),
                "okex" => $this->container->get("api.okex"),
                "bittrex" => $this->container->get("api.bittrex"),
                "bithumb" => $this->container->get("api.bithumb"),
            );

            $info_e1 = $exchanges_array[$exchange1]->mainFunction($user_name, $pair);
            $info_e2 = $exchanges_array[$exchange2]->mainFunction($user_name, $pair);
            unset($exchanges_array);
            if (is_numeric($info_e1["bid"]) && is_numeric($info_e2["bid"])) {
                $spreads = array(
                    $exchange1 . " bid" => $info_e1["bid"],
                    $exchange1 . " ask" => $info_e1["ask"],
                    $exchange2 . " bid" => $info_e2["bid"],
                    $exchange2 . " ask" => $info_e2["ask"],
                    "spread" => ($info_e1["bid"] / $info_e2["ask"] - 1) * 100,
                    "rev spread" => ($info_e1["ask"] / $info_e2["bid"] - 1) * 100,
                );
                return $spreads;
            } else {
                $err = array(
                    $exchange1 => $info_e1["bid"],
                    $exchange2 => $info_e2["bid"],
                );
                return $err;
            }
        } catch (\Exception $e) {
            $fatal_error = array(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile(),
            );
            return $fatal_error;
        }
    }
}