<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ApiKey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\pClass\pData;
use ApiBundle\pClass\pChart;

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
     * @Route("test_db")
     *
     * @return Response
     */
    public function getFromDb(Request $request)
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
            $result[] = array(
                "DateTime",
                "Spread",
                "Rev Spread"
            );

            foreach ($objects as $key => $value) {
                if (is_numeric($value["spread"]) && is_numeric($value["revSpread"]))
                $result[] = array(
                    date("Y-m-d H:i:s", $value["timeStamp"]),
                    $value["spread"],
                    $value["revSpread"],
                );
            }

           // $this->chart($result);
            //return new JsonResponse($result);
            return new Response($this->googleChart($result, $pair));
        } catch (\Exception $e) {
            $err = array(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
            );
            return new JsonResponse($err);
        }
    }

    public function googleChart($array, $pair)
    {

        $array = json_encode($array);
       $html = "<html>
  <head>
    <script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type=\"text/javascript\">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var array = JSON.parse('$array');
        var i;
        
        for(i = 1; i < array.length; i++){
            array[i][1] = parseFloat(array[i][1]);
            array[i][2] = parseFloat(array[i][2]);
        }
        var data = google.visualization.arrayToDataTable(array);

        var options = {
          title: 'Spread Chart ' + '$pair',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id=\"curve_chart\" style=\"width: 100%; height: 500px\"></div>
  </body>
</html>";



        return $html;
    }
}

