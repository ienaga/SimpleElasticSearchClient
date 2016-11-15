<?php

namespace SimpleElasticSearch;

class BaseSearch implements BaseSearchInterface
{

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
     * @var array
     */
    protected $sort = array();

    /**
     * @var array
     */
    protected $range = array();

    /**
     * @var bool
     */
    protected $source = true;


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
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param  array $sort
     * @return $this
     */
    public function setSort($sort = array())
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @param  string $key
     * @param  string $sort
     * @return $this
     */
    public function addSort($key, $sort = "asc")
    {
        $this->sort[] = array($key => array("order" => $sort));
        return $this;
    }

    /**
     * @return array
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param array $range
     */
    public function setRange($range)
    {
        $this->range = $range;
    }

    /**
     * @param  string $key
     * @param  mixed  $start
     * @param  mixed  $end
     * @return $this
     */
    public function addRange($key, $start, $end)
    {
        $this->range[$key] = [
            "from" => $start,
            "to"   => $end
        ];
        return $this;
    }

    /**
     * @return $this
     */
    public function exceptSource()
    {
        $this->source = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function ensureSource()
    {
        return $this->source;
    }
}
