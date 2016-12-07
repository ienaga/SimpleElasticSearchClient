<?php

namespace SimpleElasticSearch;

interface ResultInterface
{
    /**
     * @return void
     */
    public function buildHits();

    /**
     * @return int
     */
    public function getHitCount();

    /**
     * @return bool
     */
    public function isFound();

    /**
     * @return array
     */
    public function getSource();

    /**
     * @param array $value
     * @param int   $offset
     */
    public function setSource($value = array(), $offset = null);

    /**
     * @return int
     */
    public function getAggregationHitCount();
}