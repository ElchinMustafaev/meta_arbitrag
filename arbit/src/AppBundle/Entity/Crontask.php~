<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Crontask
 *
 * @ORM\Table(name="crontask")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CrontaskRepository")
 */
class Crontask
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
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="task", type="string", nullable=false)
     */
    private $task;

    /**
     * @var int
     *
     * @ORM\Column(name="period", type="integer", nullable=false)
     */
    private $period;

    /**
     * @var int
     *
     * @ORM\Column(name="last_start", type="integer", nullable=false)
     */
    private $lastStart;


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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Crontask
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set task
     *
     * @param string $task
     *
     * @return Crontask
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set period
     *
     * @param integer $period
     *
     * @return Crontask
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return integer
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set lastStart
     *
     * @param integer $lastStart
     *
     * @return Crontask
     */
    public function setLastStart($lastStart)
    {
        $this->lastStart = $lastStart;

        return $this;
    }

    /**
     * Get lastStart
     *
     * @return integer
     */
    public function getLastStart()
    {
        return $this->lastStart;
    }
}
