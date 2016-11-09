<?php

namespace SimpleElasticSearch;

class Query
{
    /**
     * @var array
     */
    protected $query = array();

    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @var int
     */
    protected $from = 0;

    /**
     * @var int
     */
    protected $size = 10;


    /**
     * Filters constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param int $from
     */
    public function setFrom($from = 0)
    {
        $this->from = $from;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size = 10)
    {
        $this->size = $size;
    }

    /**
     * @param  string $key
     * @param  string $value
     *  @return $this
     */
    public function match($key, $value = "")
    {
        $this->query = array("match" => array($key => $value));
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        $query = $this->query;
        return array(
            "query" => $query,
            "from"  => $this->getFrom(),
            "size"  => $this->getSize()
        );
    }

    /**
     * @return Client
     */
    public function attach()
    {
        return $this->getClient()->mergeQuery($this->getQuery());
    }


}