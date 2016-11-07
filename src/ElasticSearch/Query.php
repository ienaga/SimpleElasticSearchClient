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
     * @param  string $key
     * @param  string $value
     *  @return $this
     */
    public function addOne($key, $value = "")
    {
        $this->query = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {

        return array("query" => $this->query);
    }

    /**
     * @return Client
     */
    public function attach()
    {
        return $this->getClient()->setQuery($this->getQuery());
    }


}