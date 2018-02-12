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
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("test_bd")
     *
     * @return Response
     */
    public function test2(Request $request)
    {
        try {
            $exchange1 = $request->get("e1");
            $exchange2 = $request->get("e2");
            $pair = $request->get('p');

            $em = $this->getDoctrine()->getManager();

            $qb = $em->createQueryBuilder('h');
            $qb
                ->select("h.exchange1, h.exchange2, h.timeStamp, h.spread, h.revSpread")
                ->from("ApiBundle:History", 'h')
                ->where("h.exchange1 = :e1")
                ->andWhere("h.exchange2 = :e2")
                ->andWhere("h.pair = :p")
                ->setParameter("e1", $exchange1)
                ->setParameter("e2", $exchange2)
                ->setParameter("p", $pair)
            ;

            $query = $qb->getQuery();
            $objects = $query->getResult();
            $result = array();

            foreach ($objects as $key => $value) {
                if (is_numeric($value["spread"]) && is_numeric($value["revSpread"]))
                $result[] = array(
                    date("Y-m-d H:i:s", $value["timeStamp"]),
                    $value["spread"],
                    $value["revSpread"],
                );
            }

            $this->testgraph($result);
            //return new Response($html);
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
     * @Route("form")
     *
     * @return Response
     */
    public function form()
    {
        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
    
    <body>
    <form action=\"test_bd\" method=\"post\">
        <p>first exchange: <input type=\"text\" name=\"e1\" /></p>
        <p>second exchange: <input type=\"text\" name=\"e2\" /></p>
        <p>pair: <input type=\"text\" name=\"p\" /></p>
        <p><input type=\"submit\" /></p>
    </form>
    </body>
    </html>
    ";
        return new  Response($html);
    }

    public function testgraph($array)
    {

    }
}

