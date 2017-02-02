<?php

namespace SimpleElasticSearch;

interface BaseSearchInterface
{
    /**
     * @param  string $key
     * @param  string $sort
     * @return $this
     */
    public function addSort($key, $sort = "asc");

    /**
     * @param  string $field
     * @param  string $type
     * @return $this
     */
    public function addAggregation($field, $type = "terms");
}