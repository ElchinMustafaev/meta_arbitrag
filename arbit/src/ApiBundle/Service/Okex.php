<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 21.02.2018
 * Time: 18:44
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Okex
{
    private $contailer;

    private $em;

    /**
     * Livecoin constructor.
     *
     * @param ContainerInterface $container
     * @param \Doctrine\ORM\EntityManager $em
     */
    function __construct(ContainerInterface $container, \Doctrine\ORM\EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function mainFunction(
        $name,
        $pair
    )
    {
        try {
            $db_record = $this
                ->em
                ->getRepository('ApiBundle:ApiKey')
                ->findOneBy(array(
                        "exchange" => "okex",
                        "users" => $name,
                    )
                );

            $okex = new \ccxt\okex();
            $okex->apiKey = $db_record->getKey();
            $okex->secret = $db_record->getSecretKey();

            $bid = ($okex->fetch_ticker ($pair)['bid']);
            $ask = ($okex->fetch_ticker ($pair)['ask']);

            return array(
                "bid" => $bid,
                "ask" => $ask,
            );
        } catch (\Exception $e) {
            return array(
                "bid" => $e->getMessage(),
                "ask" => $e->getMessage(),
            );
        }

    }

}