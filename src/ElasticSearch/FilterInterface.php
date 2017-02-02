<?php

namespace SimpleElasticSearch;

interface FilterInterface
{

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function addAnd($key, $value = "");

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function addOr($key, $value = "");

    /**
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function addNot($key, $value = "");

    /**
     * @param  string $key
     * @param  mixed  $start
     * @param  mixed  $end
     * @param  string $type
     * @return $this
     */
    public function between($key, $start, $end, $type = "AND");

    /**
     * @return array
     */
    public function getFilters();

    /**
     * @return Client
     */
    public function attach();
}