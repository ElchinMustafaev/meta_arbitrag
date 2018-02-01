<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiKey
 *
 * @ORM\Table(name="api_key")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ApiKeyRepository")
 */
class ApiKey
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
     * @ORM\Column(name="exchange", type="string", nullable=true)
     */
    private $exchange;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", nullable=true)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="secretkey", type="string", nullable=true)
     */
    private $secretkey;

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
     * Set exchange
     *
     * @param string $exchange
     *
     * @return ApiKey
     */
    public function setExchange($exchange)
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * Get exchange
     *
     * @return string
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return ApiKey
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set secretkey
     *
     * @param string $secretkey
     *
     * @return ApiKey
     */
    public function setSecretkey($secretkey)
    {
        $this->secretkey = $secretkey;

        return $this;
    }

    /**
     * Get secretkey
     *
     * @return string
     */
    public function getSecretkey()
    {
        return $this->secretkey;
    }

    /**
     * Set pair
     *
     * @param string $pair
     *
     * @return ApiKey
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
