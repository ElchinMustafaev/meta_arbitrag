<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ApiKey;
use ccxt\binance;
use ccxt\bittrex;
use ccxt\cryptopia;
use ccxt\gdax;
use ccxt\hitbtc2;
use ccxt\huobi;
use ccxt\huobipro;
use ccxt\kraken;
use ccxt\poloniex;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiEndPointController extends Controller
{
    /**
     * @Route("/set_acc_info")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setAccInfo(Request $request)
    {
       $json_data = $request->getContent();
       $data = json_decode($json_data,1);

        $user_name = $data["user_name"];
        $api_key = $data["api_key"];
        $exchange = $data["exchange"];
        $secretkey = $data["secretkey"];

        $em = $this->getDoctrine()->getManager();
        $new_record = new ApiKey();

        $new_record->setUsers($user_name);
        $new_record->setKey($api_key);
        $new_record->setExchange($exchange);
        $new_record->setSecretkey($secretkey);

        $em->persist($new_record);
        $em->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/get_acc_info_by_key")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAccInfoByWallet(Request $request)
    {
        $json_data = $request->getContent();
        $data = json_decode($json_data, 1);

        $records = $this
            ->getDoctrine()
            ->getRepository("ApiBundle:ApiKey")
            ->findOneBy(array(
                "key" => $data["api_key"],
                )
            );
        $record_array = array(
            "id" => $records->getId(),
            "pair" => $records->getPair(),
            "api_key" => $records->getKey(),
            "exchange" => $records->getExchange(),
            "secret key hash" => md5($records->getSecretkey()),
        );
        return new JsonResponse($record_array);
    }

    /**
     * @Route("test")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function test(Request $request)
    {
        try {
            date_default_timezone_set("UTC");

            $cryptopia = new huobipro();
            $cryptopia->load_markets(true);
            //$market = $cryptopia->market("ETH/BTC");

            $orders = $cryptopia->fetch_order_book("ETH/BTC");

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

            return new JsonResponse(array(
                "bid" => $aver_bid,
                "ask" => $aver_ask,
                )
            );
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
            );
            return new JsonResponse($err);

        }
    }

    /**
     * @Route("test2")
     *
     * @return JsonResponse
     */
    public function test2()
    {
        try {
            date_default_timezone_set("UTC");

            $cryptopia = new poloniex();
            $cryptopia->load_markets(true);
            $market = $cryptopia->market("ETH/BTC");

            return new JsonResponse($market);
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
            );
            return new JsonResponse($err);
        }
    }
}

