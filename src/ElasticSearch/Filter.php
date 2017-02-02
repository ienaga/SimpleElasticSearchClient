<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/FilterInterface.php";
require_once __DIR__ . "/BaseSearch.php";

class Filter extends BaseSearch implements FilterInterface
{

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
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function addNot($key, $value = "")
    {
        $this->must_not[] = array("term" => array($key => $value));
        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $start
     * @param  mixed  $end
     * @param  string $type
     * @return $this
     */
    public function between($key, $start, $end = null, $type = "AND")
    {
        $query = array("gte" => $start);
        if ($end) {
            $query["lte"] = $end;
        }

        $range = array("range" => array($key => $query));
        switch (strtoupper($type)) {
            case "AND":
                $this->must[]     = $range;
                break;
            case "OR":
                $this->should[]   = $range;
                break;
            case "NOT":
                $this->must_not[] = $range;
                break;
        }

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
            $results = array_merge($results, array("aggs" => $this->getAggregation()));
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