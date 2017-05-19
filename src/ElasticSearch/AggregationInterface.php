<?php

namespace SimpleElasticSearch;

interface AggregationInterface
{
    /**
     * @param  mixed  $field
     * @return string
     */
    public static function getGroupName($field);

    /**
     * @param  string $field
     * @return Aggregation
     */
    public function getAggregation($field = "");

    /**
     * @return mixed|null
     */
    public function getValue();

    /**
     * @return array
     */
    public function getBuckets();

    /**
     * @return mixed|null
     */
    public function getKey();

    /**
     * @return int
     */
    public function getDocCount();
}