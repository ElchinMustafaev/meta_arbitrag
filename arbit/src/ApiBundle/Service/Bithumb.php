<?php
/**
 * Created by PhpStorm.
 * User: el
 * Date: 21.02.2018
 * Time: 18:46
 */

namespace ApiBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Bithumb
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
                        "exchange" => "bithumb",
                        "users" => $name,
                    )
                );

            $bithumb = new \ccxt\bithumb();
            $bithumb->apiKey = $db_record->getKey();
            $bithumb->secret = $db_record->getSecretKey();

            $bid = ($bithumb->fetch_ticker ($pair)['bid']);
            $ask = ($bithumb->fetch_ticker ($pair)['ask']);

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