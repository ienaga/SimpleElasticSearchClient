<?php

namespace SimpleElasticSearch;

interface ClientInterface
{
    /**
     * @return Result
     */
    public function search();

    /**
     * @return Result
     */
    public function get();

    /**
     * @return array
     */
    public function create();

    /**
     * @return array
     */
    public function update();

    /**
     * @return array
     */
    public function delete();
}