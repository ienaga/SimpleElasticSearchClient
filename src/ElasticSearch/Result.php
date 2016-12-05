<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/ResultInterface.php";

class Result implements ResultInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * Result constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->setData($data);
    }

    /**
     * @return array
     */
    public function getData(): array
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

    /**
     * @return array
     */
    public function getHits()
    {
        $data = $this->getData();
        return (isset($data["hits"])) ? $data["hits"]["hits"] : array();
    }

    /**
     * @return int
     */
    public function getHitCount()
    {
        $data = $this->getData();
        return (isset($data["hits"])) ? $data["hits"]["total"] : 0;
    }

    /**
     * @return int
     */
    public function getAggregationHitCount()
    {
        $data = $this->getData();
        return (isset($data["aggregations"]))
         ? count($data["aggregations"]["group_by_execute_id"]["buckets"])
            : 0;
    }


}