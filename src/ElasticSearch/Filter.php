<?php

namespace SimpleElasticSearch;

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
        $filters = [
            "and" => $this->filters
        ];

        return array(
            "filter" => $filters,
            "from"   => $this->getFrom(),
            "size"   => $this->getSize(),
            "sort"   => $this->getSort()
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