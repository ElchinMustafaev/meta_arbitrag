<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 05.02.18
 * Time: 19:30
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Huobipro
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
                        "exchange" => "huobipro",
                        "users" => $name,
                    )
                );

            $huobipro = new \ccxt\huobipro();
            $huobipro->apiKey = $db_record->getKey();
            $huobipro->secret = $db_record->getSecretKey();

            $bid = ($huobipro->fetch_ticker ($pair)['bid']);
            $ask = ($huobipro->fetch_ticker ($pair)['ask']);

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