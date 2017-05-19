<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/AggregationInterface.php";

class Aggregation implements AggregationInterface, \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $data = array();

    public function __construct($data = array())
    {
        $this->setData($data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }


}