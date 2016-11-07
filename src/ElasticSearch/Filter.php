<?php

namespace SimpleElasticSearch;

class Filter
{

    /**
     * @var array
     */
    protected $filters = array();

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
        $this->filters = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return $this
     */
    public function addAnd($key, $value = "")
    {
        if (!isset($this->filters["and"])) {
            $this->filters["and"] = array();
        }
        $this->filters["and"][] = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @param  string $key
     * @param  array $values
     * @return $this
     */
    public function addOr($key, $values = array())
    {
        if (!isset($this->filters["and"])) {
            $this->filters["and"] = array();
        }
        $this->filters["and"][] = array("terms" => array($key => $values));
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {

        return array("filter" => $this->filters);
    }

    /**
     * @return Client
     */
    public function attach()
    {
        return $this->getClient()->setQuery($this->getFilters());
    }

}