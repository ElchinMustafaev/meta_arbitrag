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
        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"utf-8\">
    
    <body>
    <form action=\"test_db\" method=\"post\">
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

}
