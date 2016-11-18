<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/FilterInterface.php";
require_once __DIR__ . "/BaseSearch.php";

class Filter extends BaseSearch implements FilterInterface
{

    /**
     * @var array
     */
    protected $filters = array();

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
        $range = $this->getRange();
        if (count($range)) {
            $this->filters = array_merge($this->filters, array(array("range" => $range)));
        }

        $filters = [
            "and" => $this->filters
        ];

        $results = array(
            "filter"  => $filters,
            "from"    => $this->getFrom(),
            "size"    => $this->getSize(),
            "sort"    => $this->getSort(),
            "_source" => $this->ensureSource()
        );

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
        return $this->getClient()->mergeQuery($this->getFilters());
    }

}