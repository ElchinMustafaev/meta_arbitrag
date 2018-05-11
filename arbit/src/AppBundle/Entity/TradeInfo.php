<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TradeInfo
 *
 * @ORM\Table(name="trade_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TradeInfoRepository")
 */
class TradeInfo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;
    
    /**
     * @var float
     *
     * @ORM\Column(name="stop_lose", type="float")
     */
    private $stopLose;

    /**
     * @var float
     *
     * @ORM\Column(name="target_rev_spread", type="float", nullable=true)
     */
    private $targetRevSpread;

    /**
     * @var float
     *
     * @ORM\Column(name="take_profit", type="float")
     */
    private $takeProfit;

    /**
     * @var float
     *
     * @ORM\Column(name="start_balance", type="float")
     */
    private $startBalance;

    /**
     * @var float
     *
     * @ORM\Column(name="target_percent", type="float")
     */
    private $targetPercent;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string")
     */
    private $userName;

    /**
     * @var string
     * @ORM\Column(name="first_coin", type="string")
     */
    private $firstCoin;

    /**
     * @var string
     * @ORM\Column(name="second_coin", type="string")
     */
    private $secondCoin;

    /**
     * @var string
     *
     * @ORM\Column(name="first_exchange", type="string")
     */
    private $firstExchange;

    /**
     * @var string
     *
     * @ORM\Column(name="second_exchange", type="string")
     */
    private $secondExchange;

    /**
     * @var bool
     *
     * @ORM\Column(name="first_step_bid_status", type="boolean", nullable=true)
     */
    private $firstStepBidStatus;

    /**
     * @var bool
     *
     * @ORM\Column(name="second_step_bid_status", type="boolean", nullable=true)
     */
    private $secondStepBidStatus;

    /**
     * @var bool
     *
     * @ORM\Column(name="first_step_ask_status", type="boolean", nullable=true)
     */
    private $firstStepAskStatus;

    /**
     * @var bool
     *
     * @ORM\Column(name="second_step_ask_status", type="boolean", nullable=true)
     */
    private $secondStepAskStatus;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stopLose
     *
     * @param float $stopLose
     *
     * @return TradeInfo
     */
    public function setStopLose($stopLose)
    {
        $this->stopLose = $stopLose;

        return $this;
    }

    /**
     * Get stopLose
     *
     * @return float
     */
    public function getStopLose()
    {
        return $this->stopLose;
    }

    /**
     * Set takeProfit
     *
     * @param float $takeProfit
     *
     * @return TradeInfo
     */
    public function setTakeProfit($takeProfit)
    {
        $this->takeProfit = $takeProfit;

        return $this;
    }

    /**
     * Get takeProfit
     *
     * @return float
     */
    public function getTakeProfit()
    {
        return $this->takeProfit;
    }

    /**
     * Set startBalance
     *
     * @param float $startBalance
     *
     * @return TradeInfo
     */
    public function setStartBalance($startBalance)
    {
        $this->startBalance = $startBalance;

        return $this;
    }

    /**
     * Get startBalance
     *
     * @return float
     */
    public function getStartBalance()
    {
        return $this->startBalance;
    }

    /**
     * Set targetPercent
     *
     * @param float $targetPercent
     *
     * @return TradeInfo
     */
    public function setTargetPercent($targetPercent)
    {
        $this->targetPercent = $targetPercent;

        return $this;
    }

    /**
     * Get targetPercent
     *
     * @return float
     */
    public function getTargetPercent()
    {
        return $this->targetPercent;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return TradeInfo
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set firstCoin
     *
     * @param string $firstCoin
     *
     * @return TradeInfo
     */
    public function setFirstCoin($firstCoin)
    {
        $this->firstCoin = $firstCoin;

        return $this;
    }

    /**
     * Get firstCoin
     *
     * @return string
     */
    public function getFirstCoin()
    {
        return $this->firstCoin;
    }

    /**
     * Set secondCoin
     *
     * @param string $secondCoin
     *
     * @return TradeInfo
     */
    public function setSecondCoin($secondCoin)
    {
        $this->secondCoin = $secondCoin;

        return $this;
    }

    /**
     * Get secondCoin
     *
     * @return string
     */
    public function getSecondCoin()
    {
        return $this->secondCoin;
    }

    /**
     * Set firstExchange
     *
     * @param string $firstExchange
     *
     * @return TradeInfo
     */
    public function setFirstExchange($firstExchange)
    {
        $this->firstExchange = $firstExchange;

        return $this;
    }

    /**
     * Get firstExchange
     *
     * @return string
     */
    public function getFirstExchange()
    {
        return $this->firstExchange;
    }

    /**
     * Set secondExchange
     *
     * @param string $secondExchange
     *
     * @return TradeInfo
     */
    public function setSecondExchange($secondExchange)
    {
        $this->secondExchange = $secondExchange;

        return $this;
    }

    /**
     * Get secondExchange
     *
     * @return string
     */
    public function getSecondExchange()
    {
        return $this->secondExchange;
    }

    /**
     * Set firstStepBidStatus
     *
     * @param boolean $firstStepBidStatus
     *
     * @return TradeInfo
     */
    public function setFirstStepBidStatus($firstStepBidStatus)
    {
        $this->firstStepBidStatus = $firstStepBidStatus;

        return $this;
    }

    /**
     * Get firstStepBidStatus
     *
     * @return boolean
     */
    public function getFirstStepBidStatus()
    {
        return $this->firstStepBidStatus;
    }

    /**
     * Set secondStepBidStatus
     *
     * @param boolean $secondStepBidStatus
     *
     * @return TradeInfo
     */
    public function setSecondStepBidStatus($secondStepBidStatus)
    {
        $this->secondStepBidStatus = $secondStepBidStatus;

        return $this;
    }

    /**
     * Get secondStepBidStatus
     *
     * @return boolean
     */
    public function getSecondStepBidStatus()
    {
        return $this->secondStepBidStatus;
    }

    /**
     * Set firstStepAskStatus
     *
     * @param boolean $firstStepAskStatus
     *
     * @return TradeInfo
     */
    public function setFirstStepAskStatus($firstStepAskStatus)
    {
        $this->firstStepAskStatus = $firstStepAskStatus;

        return $this;
    }

    /**
     * Get firstStepAskStatus
     *
     * @return boolean
     */
    public function getFirstStepAskStatus()
    {
        return $this->firstStepAskStatus;
    }

    /**
     * Set secondStepAskStatus
     *
     * @param boolean $secondStepAskStatus
     *
     * @return TradeInfo
     */
    public function setSecondStepAskStatus($secondStepAskStatus)
    {
        $this->secondStepAskStatus = $secondStepAskStatus;

        return $this;
    }

    /**
     * Get secondStepAskStatus
     *
     * @return boolean
     */
    public function getSecondStepAskStatus()
    {
        return $this->secondStepAskStatus;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return TradeInfo
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set targetRevSpread
     *
     * @param float $targetRevSpread
     *
     * @return TradeInfo
     */
    public function setTargetRevSpread($targetRevSpread)
    {
        $this->targetRevSpread = $targetRevSpread;

        return $this;
    }

    /**
     * Get targetRevSpread
     *
     * @return float
     */
    public function getTargetRevSpread()
    {
        return $this->targetRevSpread;
    }
}
