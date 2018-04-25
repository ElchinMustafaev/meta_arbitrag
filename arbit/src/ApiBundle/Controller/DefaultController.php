<?php

namespace ApiBundle\Controller;

use ccxt\binance;
use ccxt\bittrex;
use ccxt\hitbtc2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }

    /**
     * @Route("/main-test")
     */
    public function mainTest()
    {
        $livecoin = $this->get("api.livecoin");
        $cryptopia = $this->get("api.hitbtc");
        $first_token = "DOGE";
        $second_token = "BTC";
        $start_balance = "100";

        $rate = $this->getPercentRate("livecoin", "hitbtc", $first_token, $second_token);
        $first_balance = $livecoin->getBalance("test");

        foreach ($first_balance["info"] as $key => $value) {
            if ($value["currency"] == $first_token && $value['type'] == "available") {
                $first_ex_first_token_balance = $value['value'];
            } elseif ($value["currency"] == $second_token && $value['type'] == "available") {
                $first_ex_second_token_balance = $value['value'];
            }
        }


        if ($start_balance <= $first_ex_first_token_balance) {
            $second_balace = $cryptopia->getBalance("test");
            print_r($second_balace);
            die;

            foreach ($second_balace['info']['Data'] as $key => $value) {
                if ($value['Symbol'] == $first_token) {
                    $second_ex_first_token_balance = $value['Available'];
                } elseif ($value['Symbol'] == $second_token) {
                    $second_ex_second_token_balance = $value['Available'];
                }
            }


            if (!empty($first_ex_first_token_balance) && !empty($first_ex_second_token_balance)
                && !empty($second_ex_first_token_balance) && !empty($second_ex_second_token_balance)
            ) {
                $second_ex_order_balance = $start_balance * $rate["first rate"]["bid"];
                if ($second_ex_order_balance <= $second_ex_second_token_balance) {
                    return new Response("true");
                } else {
                    die("low balance ex2 token2");
                }
            } else {
                die("balance = 0?");
            }
        } else {
            return new Response("invalid balance, u don't have money");
        }
    }

    public function getPercentRate($first_exchange, $second_exchange, $first_token, $second_token)
    {
        $exchanges_array = array(
            "livecoin" => $this->get("api.livecoin"),
            "bitmex" => $this->get("api.bitmex"),
            "binance" => $this->get("api.binance"),
            "cryptopia" => $this->get("api.cryptopia"),
            "gdax" => $this->get("api.gdax"),
            "hitbtc" => $this->get("api.hitbtc"),
            "huobipro" => $this->get("api.huobipro"),
            "kraken" => $this->get("api.kraken"),
            "okex" => $this->get("api.okex"),
            "bittrex" => $this->get("api.bittrex"),
            "bithumb" => $this->get("api.bithumb"),
        );

        $first_exchange = $exchanges_array[$first_exchange];
        $second_exchange = $exchanges_array[$second_exchange];

        $first_rate = $first_exchange->mainFunction("", $first_token . "/" . $second_token);
        $second_rate = $second_exchange->mainFunction("", $first_token . "/" . $second_token);

        return array(
            "first rate" => $first_rate,
            "second rate" => $second_rate,
        );
    }

    /**
     * @Route("orders")
     */
    public function getOrders()
    {
        $hitbtc = $this->get("api.hitbtc");
        print_r($hitbtc->getBalance("test"));
        return new JsonResponse();
    }
}
