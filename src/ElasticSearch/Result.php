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
    public function getData()
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
                $this->_hits = (is_array($data["_source"]))
                    ? $data["_source"]
                    : array();
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
     * @return bool
     */
    public function isFound()
    {
        $data = $this->getData();
        return (isset($data["found"]))
            ? $data["found"]
            : $this->getHitCount() > 0;
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
     * @param  string $key
     * @return mixed
     */
    public function getAggregationValue($key = "")
    {
        $data = $this->getData();

        if (!isset($data["aggregations"])) {
            return null;
        }

        if (!isset($data["aggregations"]["group_by_". $key])) {
            return null;
        }

        if (!isset($data["aggregations"]["group_by_". $key]["value"])) {
            return null;
        }

        return $data["aggregations"]["group_by_". $key]["value"];
    }

    /**
     * @param  string $key
     * @return array
     */
    public function getAggregations($key = "")
    {
        $data = $this->getData();

        if (!isset($data["aggregations"])) {
            return array();
        }

        if (!isset($data["aggregations"]["group_by_". $key])) {
            return array();
        }

        if (!isset($data["aggregations"]["group_by_". $key]["buckets"])) {
            return array();
        }

        return $data["aggregations"]["group_by_". $key]["buckets"];
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
     * @return Result
     */
    public function offsetGet($offset)
    {
        $data = $this->_hits[$offset];
        return (is_int($offset) && is_array($data) && isset($data["_source"]))
            ? new self($data)
            : $data;
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