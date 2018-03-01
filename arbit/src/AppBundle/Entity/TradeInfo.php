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
     * @var int
     *
     * @ORM\Column(name="command_id", type="integer")
     */
    private $commandId;

    /**
     * @var float
     *
     * @ORM\Column(name="max_lose", type="float")
     */
    private $maxLose;

    /**
     * @var float
     *
     * @ORM\Column(name="min_value", type="float")
     */
    private $minValue;

    /**
     * @var float
     *
     * @ORM\Column(name="fiat_balance", type="float")
     */
    private $fiatBalance;

    /**
     * @var float
     *
     * @ORM\Column(name="cripto_balance", type="float")
     */
    private $criptoBalance;

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
     * @ORM\Column(name="pair", type="string")
     */
    private $pair;

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
     * Set commandId
     *
     * @param integer $commandId
     *
     * @return TradeInfo
     */
    public function setCommandId($commandId)
    {
        $this->commandId = $commandId;

        return $this;
    }

    /**
     * Get commandId
     *
     * @return integer
     */
    public function getCommandId()
    {
        return $this->commandId;
    }

    /**
     * Set maxLose
     *
     * @param float $maxLose
     *
     * @return TradeInfo
     */
    public function setMaxLose($maxLose)
    {
        $this->maxLose = $maxLose;

        return $this;
    }

    /**
     * Get maxLose
     *
     * @return float
     */
    public function getMaxLose()
    {
        return $this->maxLose;
    }

    /**
     * Set minValue
     *
     * @param float $minValue
     *
     * @return TradeInfo
     */
    public function setMinValue($minValue)
    {
        $this->minValue = $minValue;

        return $this;
    }

    /**
     * Get minValue
     *
     * @return float
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     * Set fiatBalance
     *
     * @param float $fiatBalance
     *
     * @return TradeInfo
     */
    public function setFiatBalance($fiatBalance)
    {
        $this->fiatBalance = $fiatBalance;

        return $this;
    }

    /**
     * Get fiatBalance
     *
     * @return float
     */
    public function getFiatBalance()
    {
        return $this->fiatBalance;
    }

    /**
     * Set criptoBalance
     *
     * @param float $criptoBalance
     *
     * @return TradeInfo
     */
    public function setCriptoBalance($criptoBalance)
    {
        $this->criptoBalance = $criptoBalance;

        return $this;
    }

    /**
     * Get criptoBalance
     *
     * @return float
     */
    public function getCriptoBalance()
    {
        return $this->criptoBalance;
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
     * Set pair
     *
     * @param string $pair
     *
     * @return TradeInfo
     */
    public function setPair($pair)
    {
        $this->pair = $pair;

        return $this;
    }

    /**
     * Get pair
     *
     * @return string
     */
    public function getPair()
    {
        return $this->pair;
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
}
