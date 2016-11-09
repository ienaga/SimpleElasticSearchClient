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
     * @param  int $from
     * @return $this
     */
    public function setFrom($from = 0)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param  int $size
     * @return $this
     */
    public function setSize($size = 10)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return $this
     */
    public function match($key, $value = "")
    {
        $this->filters[] = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        $filters = array();
        if (count($this->filters) > 1) {
            $filters["and"] = $this->filters;
        } else {
            $filters = $this->filters[0];
        }

        return array(
            "filter" => $filters,
            "from"   => $this->getFrom(),
            "size"   => $this->getSize()
        );
    }

    /**
     * @return Client
     */
    public function attach()
    {
        return $this->getClient()->mergeQuery($this->getFilters());
    }

}