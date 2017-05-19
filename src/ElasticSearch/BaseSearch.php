<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/BaseSearchInterface.php";

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
     * @var null
     */
    protected $aggregation = null;

    /**
     * @var null
     */
    protected $current_aggregation_field = null;

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
    public function getAggregation()
    {
        return $this->aggregation;
    }

    /**
     * @param  string $field
     * @param  string $type
     * @return $this
     */
    public function setAggregation($field, $type = "terms")
    {
        $this->aggregation[] = array(
            "group_by_".$field => array(
                $type => array(
                    "field" => $field
                )
            )
        );
        return $this;
    }

    /**
     * @param  string $field
     * @param  string $sub_field
     * @param  string $type
     * @return $this
     */
    public function addAggregation($field, $sub_field, $type = "terms")
    {
        if (!isset($this->aggregation[$field]["aggs"])) {
            $this->aggregation[$field]["aggs"] = [];
        }

        $this->aggregation[$field]["aggs"]["group_by_".$sub_field] = array(
            $type => array(
                "field" => $field
            )
        );
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
