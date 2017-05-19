<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/FilterInterface.php";
require_once __DIR__ . "/BaseSearch.php";

class Filter extends BaseSearch implements FilterInterface
{

    /**
     * @var string
     */
    const GREATER_THAN  = "gt"; // >
    const LESS_THAN     = "lt"; // <
    const GREATER_EQUAL = "gte"; // >=
    const LESS_EQUAL    = "lte"; // <=

    /**
     * @var array
     */
    protected $must     = array();

    /**
     * @var array
     */
    protected $must_not = array();

    /**
     * @var array
     */
    protected $should   = array();

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function addAnd($key, $value = "")
    {
        $this->must[] = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function addOr($key, $value = "")
    {
        $this->should[] = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function addNot($key, $value = "")
    {
        $this->must_not[] = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @param string $type
     * @param mixed  $value
     */
    public function setOperator($type, $value)
    {
        switch (strtoupper($type)) {
            case "AND":
                $this->must[] = $value;
                break;
            case "OR":
                $this->should[] = $value;
                break;
            case "NOT":
                $this->must_not[] = $value;
                break;
        }
    }

    /**
     * @param  string $key
     * @param  mixed  $start
     * @param  mixed  $end
     * @param  string $type
     * @return $this
     */
    public function between($key, $start, $end, $type = "AND")
    {
        $query = array(
            self::GREATER_EQUAL => $start,
            self::LESS_EQUAL    => $end
        );

        $range = array("range" => array($key => $query));
        $this->setOperator($type, $range);

        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @param  string $type
     * @return $this
     */
    public function greaterEqual($key, $value, $type = "AND")
    {
        $this->operator($key, $value, self::GREATER_EQUAL, $type);
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @param  string $type
     * @return $this
     */
    public function greaterThan($key, $value, $type = "AND")
    {
        $this->operator($key, $value, self::GREATER_THAN, $type);
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @param  string $type
     * @return $this
     */
    public function lessEqual($key, $value, $type = "AND")
    {
        $this->operator($key, $value, self::LESS_EQUAL, $type);
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @param  string $type
     * @return $this
     */
    public function lessThan($key, $value, $type = "AND")
    {
        $this->operator($key, $value, self::LESS_THAN, $type);
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @param  string $operator
     * @param  string $type
     * @return $this
     */
    public function operator($key, $value, $operator = self::GREATER_THAN, $type = "AND")
    {
        $query = array($operator => $value);
        $range = array("range" => array($key => $query));
        $this->setOperator($type, $range);
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        // base
        $results = array(
            "from"    => $this->getFrom(),
            "size"    => $this->getSize(),
            "sort"    => $this->getSort(),
            "_source" => $this->ensureSource()
        );

        // create query
        $query = array();
        if (count($this->must)) {
            $query["must"] = $this->must;
        }

        if (count($this->should)) {
            $query["should"] = $this->should;
        }

        if (count($this->must_not)) {
            $query["must_not"] = $this->must_not;
        }

        if (count($query)) {
            $results["query"]["bool"] = $query;
        }

        if ($this->getAggregation()) {
            $results = array_merge($results, array(
                "aggs" => $this->getAggregation()
            ));
        }

        return $results;
    }

    /**
     * @return Client
     */
    public function attach()
    {
        return $this->getClient()->mergeBody($this->getFilters());
    }

}