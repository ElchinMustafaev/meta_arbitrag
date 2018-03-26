<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\TradeInfo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{

    /**
     * @Route("form")
     *
     * @return Response
     */
    public function form()
    {
        $pair = $this->getList();
        $exchange = $this->getExchanges();
        //print_r($pair);
        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
        <link rel='stylesheet' href='css/styles.css'>
    <body>
    <div class='formex'>
    <form action=\"test_db\" method=\"post\" id=\"data\">
        <table style='border: 1px'>
        <tr>
        <td style='vertical-align: top'>
        <p>first exchange: </p>
        <p>second exchange: </p>
        </td>
        <td>
        
        <p><select name=\"e1\" form=\"data\">
    " .$exchange ."
    </select></p>
        
        <p><select name=\"e2\" form=\"data\">
    " .$exchange ."
    </select></p>
        <p><select name=\"p\" form=\"data\">
    " .$pair ."
    </select></p>
      
        <p><input type=\"submit\" /></p>
        </td>
        </tr>
        </table>
    </form>
    <div>
    </body>
    </html>
    ";
        return new  Response($html);
    }

    /**
     * @Route("table")
     *
     * @return Response
     */
    public function table()
    {
        $dash_board_helper = $this->get('api.dash');
        $result = $dash_board_helper->getInfoForTable();
        $html = '<table cellpadding="5" cellspacing="1" border="0">';
        foreach ($result as $key => $value) {
            $html .= "<tr>";
            foreach ($value as $data)
                $html .= "<td>".$data."</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        return new Response($html);
    }

    /**
     * @return string
     */
    public function getList()
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $qb = $em->createQueryBuilder("l");
        $qb->select("l.pair")
            ->from("ApiBundle:History", "l")
            ->groupby("l.pair")
        ;
        $query = $qb->getQuery();
        $pairs = $query->getResult();
        $clear_pair = "";
        foreach ($pairs as $pair) {
            $clear_pair .= '<option value="' . $pair["pair"] . '">' . $pair["pair"] . "</option>\n";
        }
        return $clear_pair;
    }

    /**
     * @return string
     */
    public function getExchanges()
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $qb = $em->createQueryBuilder("l");
        $qb->select("l.exchange1")
            ->from("ApiBundle:History", "l")
            ->groupby("l.exchange1")
        ;
        $query = $qb->getQuery();
        $exchange = $query->getResult();
        $clear_pair = "";
        foreach ($exchange as $e) {
            $clear_pair .= '<option value="' . $e["exchange1"] . '">' . $e["exchange1"] . "</option>\n";
        }
        return $clear_pair;
    }

    /**
     * @Route("set-trade")
     *
     * @return Response
     */
    public function setTrades()
    {
        $pair = $this->getList();
        $exchange = $this->getExchanges();
        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
        <link rel='stylesheet' href='css/styles.css'>
    <body>
    <div class='formex'>
    <form action=\"set-trade-to-db\" style='color: #FFFFFF;' method=\"post\" id=\"data\">
       First Exchange  <select name=\"e1\" form=\"data\">
    " .$exchange ."
    </select><br>
    Second Exchange <select name=\"e2\" form=\"data\">
    " .$exchange ."
    </select><br>
    Pair <select name=\"p\" form=\"data\">
    " .$pair ."
    </select><br>
    Target Percent <input type=\"text\" name=\"target p\"><br>
    Take Profit <input type=\"text\" name=\"take p\"><br>
    Stop Loss<input type=\"text\" name=\"stop l\"><br>
    Crypto Balance<input type=\"text\" name=\"crypto b\"><br>
    User Name<input type=\"text\" name=\"crypto b\"><br>
    User Name<input type=\"text\" name=\"crypto b\"><br>
    
      
      
        <input type=\"submit\" />
        
    </form>
    <div>
    </body>
    </html>
    ";

        return new  Response($html);
    }

    /**
     * @Route("set-trade-to-db")
     */
    public function setTradeToDb(Request $request)
    {
        try {
            $content = $request->getContent();
            $new_record = new TradeInfo();

            $new_record->setFirstExchange($content['e1']);
            $new_record->setSecondExchange($content['e2']);
            $new_record->setPair($content['p']);
            $new_record->setTargetPercent($content['target p']);
            $new_record->setMaxLose($content['stop l']);
            $new_record->setMinValue($content['take p']);
            $new_record->setCriptoBalance($content['crypto b']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($new_record);
            $em->flush();

            return new Response("true");
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }


}
