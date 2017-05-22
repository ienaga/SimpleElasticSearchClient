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
    public function setAggregation($field, $type = "terms");

    /**
     * @param  string $field
     * @param  string $sub_field
     * @param  string $type
     * @return $this
     */
    public function addAggregation($field, $sub_field, $type = "terms");

    /**
     * @param  array $custom
     * @return $this
     */
    public function customAggregation($custom = array());
}