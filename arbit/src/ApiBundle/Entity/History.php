<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\HistoryRepository")
 */
class History
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
     * @ORM\Column(name="exchange1", type="string", nullable=true)
     */
    private $exchange1;

    /**
     * @var string
     *
     * @ORM\Column(name="exchange2", type="string", nullable=true)
     */
    private $exchange2;

    /**
     * @var int
     *
     * @ORM\Column(name="time_stamp", type="integer", nullable=true)
     */
    private $timeStamp;

    /**
     * @var string
     *
     * @ORM\Column(name="spread", type="string", nullable=true)
     */
    private $spread;

    /**
     * @var string
     *
     * @ORM\Column(name="rev_spread", type="string", nullable=true)
     */
    private $revSpread;

    /**
     * @var string
     *
     * @ORM\Column(name="pair", type="string", nullable=true)
     */
    private $pair;

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
     * Set exchange1
     *
     * @param string $exchange1
     *
     * @return History
     */
    public function setExchange1($exchange1)
    {
        $this->exchange1 = $exchange1;

        return $this;
    }

    /**
     * Get exchange1
     *
     * @return string
     */
    public function getExchange1()
    {
        return $this->exchange1;
    }

    /**
     * Set exchange2
     *
     * @param string $exchange2
     *
     * @return History
     */
    public function setExchange2($exchange2)
    {
        $this->exchange2 = $exchange2;

        return $this;
    }

    /**
     * Get exchange2
     *
     * @return string
     */
    public function getExchange2()
    {
        return $this->exchange2;
    }

    /**
     * Set timeStamp
     *
     * @param integer $timeStamp
     *
     * @return History
     */
    public function setTimeStamp($timeStamp)
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }

    /**
     * Get timeStamp
     *
     * @return integer
     */
    public function getTimeStamp()
    {
        return $this->timeStamp;
    }

    /**
     * Set spread
     *
     * @param string $spread
     *
     * @return History
     */
    public function setSpread($spread)
    {
        $this->spread = $spread;

        return $this;
    }

    /**
     * Get spread
     *
     * @return string
     */
    public function getSpread()
    {
        return $this->spread;
    }

    /**
     * Set revSpread
     *
     * @param string $revSpread
     *
     * @return History
     */
    public function setRevSpread($revSpread)
    {
        $this->revSpread = $revSpread;

        return $this;
    }

    /**
     * Get revSpread
     *
     * @return string
     */
    public function getRevSpread()
    {
        return $this->revSpread;
    }

    /**
     * Set pair
     *
     * @param string $pair
     *
     * @return History
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
}
