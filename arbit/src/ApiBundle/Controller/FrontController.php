<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        //print_r($pair);
        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
    
    <body>
    <form action=\"test_db\" method=\"post\" id=\"data\">
        <p>first exchange: <input type=\"text\" name=\"e1\" /></p>
        <p>second exchange: <input type=\"text\" name=\"e2\" /></p>
        
        <p><select size=\"3\" name=\"p\" form=\"data\">
    " .$pair ."
    </select></p>
      
        <p><input type=\"submit\" /></p>
    </form>
    </body>
    </html>
    ";
        return new  Response($html);
    }

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

}
