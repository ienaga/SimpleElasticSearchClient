<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/ClientInterface.php";
require_once __DIR__ . "/Result.php";

use \SimpleApi\Client as BaseClient;

class Client extends BaseClient implements ClientInterface
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
     * @var string
     */
    protected $id    = "";

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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  string $id
     * @return $this
     */
    public function setId($id = "")
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Filter
     */
    public function createFilter()
    {
        return new Filter($this);
    }

    /**
     * @return Query
     */
    public function createQuery()
    {
        return new Query($this);
    }

    /**
     * @return Result
     */
    public function search()
    {
        $data = $this
            ->setMethod("GET")
            ->setPath(implode("/", [$this->getIndex(), $this->getType(), "_search"]))
            ->send();

        return new Result($data);
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
    public function create()
    {
        return $this
            ->setMethod("POST")
            ->setPath(implode("/", [$this->getIndex(), $this->getType()]))
            ->send();
    }

    /**
     * @return array
     */
    public function update()
    {
        return $this
            ->setMethod("PUT")
            ->setPath(implode("/", [$this->getIndex(), $this->getType(), $this->getId()]))
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