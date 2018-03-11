<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 05.02.18
 * Time: 18:52
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Hitbtc
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
                        "exchange" => "hitbtc2",
                        "users" => $name,
                    )
                );

            $hitbtc = new \ccxt\hitbtc2();
            $hitbtc->apiKey = $db_record->getKey();
            $hitbtc->secret = $db_record->getSecretKey();

            $bid = ($hitbtc->fetch_ticker ($pair)['bid']);
            $ask = ($hitbtc->fetch_ticker ($pair)['ask']);

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