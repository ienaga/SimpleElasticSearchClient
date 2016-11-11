<?php

namespace SimpleElasticSearch;

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
        $query = [
            "and" => $this->query
        ];

        return array(
            "query"   => $query,
            "from"    => $this->getFrom(),
            "size"    => $this->getSize(),
            "sort"    => $this->getSort(),
            "_source" => $this->ensureSource()
        );
    }

    /**
     * @return Client
     */
    public function attach()
    {
        return $this->getClient()->mergeQuery($this->getQuery());
    }


}