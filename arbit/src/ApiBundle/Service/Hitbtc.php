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

    /**
     * @param $name
     * @param $pair
     * @return array
     */
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

    /**
     * @param $name
     * @param $pair
     * @param $balance
     * @param $side
     * @param $price
     * @return mixed
     */
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

        $order = $hitbtc->create_order($pair, "limit", $side, $balance, $price);
        if (is_array($order)) {
            return $order["info"]['clientOrderId'];
        }

    }

    /**
     * @param $name
     * @param $first_token
     * @param $second_token
     * @return array
     */
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
        if (!empty($first_token_balance) || !empty($second_token_balance)) {
            return array(
                $first_token_balance,
                $second_token_balance
            );
        } else {
            die("there are not balance on " . $this->getName() . "\n");
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "hitbtc";
    }

    /**
     * @return float
     */
    public function getFeePercent()
    {
        return 1.01;
    }

    /**
     * @param $name
     * @param $id
     * @param string $pair
     * @param string $time
     * @return mixed|string
     */
    public function getInfoByOrder($name, $id, $pair = "", $time = "")
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

        $order = $hitbtc->fetchOrder($id);
        if (is_array($order)) {
            return $order['status'];
        } else {
            return "error";
        }
    }
}