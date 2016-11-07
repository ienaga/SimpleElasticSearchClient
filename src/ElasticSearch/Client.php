<?php

namespace SimpleElasticSearch;

use \SimpleApi\Client as BaseClient;

class Client extends BaseClient
{
    /**
     * @var null
     */
    protected $index = null;

    /**
     * @var string
     */
    protected $type  = "";


    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     * @return null
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param  string $index
     * @return $this
     */
    public function setIndex($index = "")
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return $this
     */
    public function setType($type = "")
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Filters
     */
    public function createFilter()
    {
        return new Filter($this);
    }

    /**
     * @return Queries
     */
    public function createQuery()
    {
        return new Query($this);
    }

    /**
     * @return array
     */
    public function search()
    {
        return $this
            ->setMethod("GET")
            ->setPath(implode("/", [$this->getIndex(), $this->getType(), "_search"]))
            ->send();
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this
            ->setMethod("GET")
            ->setPath(implode("/",[$this->getIndex(), $this->getType()]))
            ->send();
    }

    /**
     * @return array
     */
    public function post()
    {
        return $this
            ->setMethod("POST")
            ->setPath(implode("/", [$this->getIndex(), $this->getType()]))
            ->send();
    }

    /**
     * @return array
     */
    public function put()
    {
        return $this
            ->setMethod("PUT")
            ->setPath(implode("/", [$this->getIndex(), $this->getType()]))
            ->send();
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this
            ->setMethod("DELETE")
            ->setPath(implode("/", [$this->getIndex(), $this->getType()]))
            ->send();
    }
}