<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/BaseSearchInterface.php";
require_once __DIR__ . "/Aggregation.php";

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
     * @param  string      $field
     * @param  string      $type
     * @param  string|null $order
     * @param  string      $sort
     * @param  string|null $sub_type
     * @return $this
     */
    public function setAggregation(
        $field, $type = "terms",
        $order = null, $sort = "asc", $sub_type = null
    )
    {
        // condition
        $conditions = array("field" => $field);
        if ($order) {
            $conditions["order"] = array($order => $sort);
        }

        // set
        $this->aggregation = array(
            Aggregation::getGroupName($field) => array(
                $type => $conditions
            )
        );

        // sub aggregation
        if ($sub_type) {
            $this->addAggregation($field, $order, $sub_type);
        }

        return $this;
    }

    /**
     * @param  string      $field
     * @param  string      $sub_field
     * @param  string      $type
     * @param  string|null $order
     * @param  string      $sort
     * @return $this
     */
    public function addAggregation(
        $field, $sub_field, $type = "terms",
        $order = null, $sort = "asc"
    )
    {
        $name = Aggregation::getGroupName($field);

        if (!isset($this->aggregation[$name]["aggs"])) {
            $this->aggregation[$name]["aggs"] = [];
        }

        $subName = Aggregation::getSubGroupName($sub_field, $type);

        // condition
        $conditions = array("field" => $sub_field);
        if ($order) {
            $conditions["order"] = array($order => $sort);
        }

        // set
        $this->aggregation[$name]["aggs"][$subName] = array(
            $type => $conditions
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
