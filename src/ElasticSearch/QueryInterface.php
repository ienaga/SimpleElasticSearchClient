<?php

namespace SimpleElasticSearch;

interface QueryInterface
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
    public function getQuery();

    /**
     * @return Client
     */
    public function attach();
}