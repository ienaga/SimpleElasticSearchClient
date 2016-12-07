<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/ResultInterface.php";

class Result implements ResultInterface, \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $_hits = array();

    /**
     * Result constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->setData($data);
        $this->buildHits();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return void
     */
    public function buildHits()
    {
        $data = $this->getData();

        switch (true) {
            case isset($data["hits"]):
                $this->_hits = $data["hits"]["hits"];
                break;
            case isset($data["_source"]):
                $this->_hits = $data["_source"];
                break;
        }
    }

    /**
     * @return int
     */
    public function getHitCount()
    {
        $data = $this->getData();
        return (isset($data["hits"])) ? $data["hits"]["total"] : 0;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->_hits;
    }

    /**
     * @param array $value
     * @param int   $offset
     */
    public function setSource($value = array(), $offset = null)
    {
        if ($offset === null) {
            $this->_hits = $value;
        } else {
            if (isset($this->_hits[$offset])) {
                $this->_hits[$offset]["_source"] = $value;
            }
        }
    }

    /**
     * @return string|null
     */
    public function getIndex()
    {
        $data = $this->getData();
        return (isset($data["_index"])) ? $data["_index"] : null;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        $data = $this->getData();
        return (isset($data["_type"])) ? $data["_type"] : null;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        $data = $this->getData();
        return (isset($data["_id"])) ? $data["_id"] : null;
    }

    /**
     * @return int
     */
    public function getAggregationHitCount()
    {
        $data = $this->getData();
        return (isset($data["aggregations"]))
         ? count($data["aggregations"]["group_by_execute_id"]["buckets"])
            : 0;
    }

    /**
     * @return Result
     */
    public function current()
    {
        return new self($this->_hits[$this->offset]);
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->offset++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->_hits[$this->key()]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_hits[$offset]);
    }

    /**
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_hits[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_hits[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_hits[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_hits);
    }

    /**
     * @param  mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }
        return null;
    }

    /**
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
}