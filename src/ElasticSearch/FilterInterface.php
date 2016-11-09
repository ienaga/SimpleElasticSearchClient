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
     * @return Client
     */
    public function attach();
}