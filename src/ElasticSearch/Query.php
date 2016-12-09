<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/QueryInterface.php";
require_once __DIR__ . "/BaseSearch.php";

class Query extends BaseSearch implements QueryInterface
{
    /**
     * @var array
     */
    protected $query = array();

    /**
     * @param  string $key
     * @param  string $value
     *  @return $this
     */
    public function match($key, $value = "")
    {
        $this->query[] = array("match" => array($key => $value));
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        $range = $this->getRange();
        if (count($range)) {
            $this->query = array_merge($this->query, array(array("range" => $range)));
        }

        $results = array(
            "from"    => $this->getFrom(),
            "size"    => $this->getSize(),
            "sort"    => $this->getSort(),
            "_source" => $this->ensureSource()
        );

        if (count($this->query)) {
            $results["query"] = [
                "and" => $this->query
            ];
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
        return $this->getClient()->mergeBody($this->getQuery());
    }


}