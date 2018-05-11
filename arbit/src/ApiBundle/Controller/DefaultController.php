<?php

namespace ApiBundle\Controller;

use ccxt\binance;
use ccxt\bittrex;
use ccxt\hitbtc;
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
        $hitbtc = $this->get("api.hitbtc");
        $first_token = "DOGE";
        $second_token = "BTC";
        $start_balance = "100";
        $id = "5266941001";
        $name = "test";
        $trade_helper = $this->get("api.trade");

        //print_r($trade_helper->analyse("livecoin", "hitbtc", "DOGE/BTC", "test"));
        print_r($livecoin->getBalance($name, $first_token, $second_token));
        print_r($hitbtc->getBalance($name, $first_token, $second_token));
        return new JsonResponse();
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
