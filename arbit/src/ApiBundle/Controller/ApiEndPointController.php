<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ApiKey;
use ccxt\bitfinex;
use ccxt\bitfinex2;
use ccxt\huobi;
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

        $exchange = $data["exchange"];
        $api_key = $data["api_key"];
        $wallet = $data["wallet"];
        $secretkey = $data["secretkey"];

        $em = $this->getDoctrine()->getManager();
        $new_record = new ApiKey();

        $new_record->setExchange($exchange);
        $new_record->setKey($api_key);
        $new_record->setWallet($wallet);
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
            "exchange" => $records->getExchange(),
            "api_key" => $records->getKey(),
            "wallet" => $records->getWallet(),
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
        date_default_timezone_set("UTC");

        $bitfinex = new bitfinex2();
        $bitfinex->load_markets();
        $markets = $bitfinex->market("BTC/USD");

        return new JsonResponse($markets);

    }
}
