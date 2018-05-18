<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\TradeInfo;
use ccxt\binance;
use ccxt\bittrex;
use ccxt\hitbtc;
use ccxt\hitbtc2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("add-record")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addTask(Request $request)
    {
        try {
            $stopLose = $request->get("sl");
            $takeProfit = $request->get("tpr");
            $startBalance = $request->get("sb");
            $targetPercent = $request->get("tp");
            $userName = $request->get("user");
            $firstCoin = $request->get("fc");
            $secondCoin = $request->get("sc");
            $firstExchange = $request->get("fe");
            $secondExchange = $request->get("se");

            $record = new TradeInfo();

            $record->setStatus(1);
            $record->setStopLose($stopLose);
            $record->setStartBalance($startBalance);
            $record->setFirstCoin($firstCoin);
            $record->setSecondCoin($secondCoin);
            $record->setFirstExchange($firstExchange);
            $record->setSecondExchange($secondExchange);
            $record->setStartBalance($startBalance);
            $record->setTakeProfit($takeProfit);
            $record->setUserName($userName);
            $record->setTargetPercent($targetPercent);
            $record->setTargetRevSpread($targetPercent - $takeProfit);
            $record->setFirstStepAskStatus(false);
            $record->setFirstStepBidStatus(false);
            $record->setSecondStepAskStatus(false);
            $record->setSecondStepBidStatus(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($record);
            $em->flush();
            return new JsonResponse("true");
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
}
