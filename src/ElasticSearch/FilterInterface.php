<?php

namespace SimpleElasticSearch;

interface FilterInterface
{
    /**
     * @param  string $key
     * @param  string $value
     * @return $this
     */
    public function match($key, $value = "");

    /**
     * @return array
     */
    public function getFilters();

    /**
     * @return Client
     */
    public function attach();
}