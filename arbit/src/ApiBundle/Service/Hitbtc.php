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

            $hitbtc = new \ccxt\hitbtc2();

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

    public function takeOrder($name, $pair, $balance, $side, $price)
    {
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

        return $hitbtc->create_order($pair, "limit", $side, $balance, $price);

    }

    public function getBalance($name, $first_token, $second_token)
    {
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
        $balance = $hitbtc->fetch_balance();

        foreach ($balance['info'] as $key => $value) {
            if ($value['currency'] == $first_token) {
                $first_token_balance = $value['available'];
            } elseif ($value['currency'] == $second_token) {
                $second_token_balance = $value['available'];
            }
        }
        if (!empty($first_token_balance) && !empty($second_token_balance)) {
            return array(
                $first_token_balance,
                $second_token_balance
            );
        } else {
            die("there are not balance");
        }
    }

    public function getName()
    {
        return "hitbtc";
    }
}