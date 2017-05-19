<?php

namespace SimpleElasticSearch;

require_once __DIR__ . "/AggregationInterface.php";

class Aggregation implements AggregationInterface, \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var string
     */
    const AGGREGATION_GROUP_NAME = "group_by_%s";

    /**
     * @var string
     */
    const AGGREGATION_SUB_GROUP_NAME = "group_by_%s_%s";

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * Aggregation constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->setData($data);
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
     * @param  mixed  $field
     * @return string
     */
    public static function getGroupName($field)
    {
        return sprintf(self::AGGREGATION_GROUP_NAME, $field);
    }

    /**
     * @param  mixed  $field
     * @param  mixed  $type
     * @return string
     */
    public static function getSubGroupName($field, $type)
    {
        return sprintf(self::AGGREGATION_SUB_GROUP_NAME, $field, $type);
    }

    /**
     * @param  string $field
     * @return Aggregation
     */
    public function getAggregation($field = "")
    {
        $data = $this->getData();
        return (isset($data[self::getGroupName($field)]))
            ? new Aggregation($data[self::getGroupName($field)])
            : new Aggregation(array());
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        $data = $this->getData();
        return (isset($data["value"])) ? $data["value"] : null;
    }

    /**
     * @return array
     */
    public function getBuckets()
    {
        $data = $this->getData();
        return (isset($data["buckets"])) ? $data["buckets"] : array();
    }

    /**
     * @return mixed|null
     */
    public function getKey()
    {
        $data = $this->getData();
        return (isset($data["key"])) ? $data["key"] : null;
    }

    /**
     * @return int
     */
    public function getDocCount()
    {
        $data = $this->getData();
        return (isset($data["doc_count"])) ? $data["doc_count"] : 0;
    }


    /**
     * @return Aggregation
     */
    public function current()
    {
        $buckets = $this->getBuckets();
        return new self($buckets[$this->offset]);
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
        $buckets = $this->getBuckets();
        return isset($buckets[$this->key()]);
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
        $buckets = $this->getBuckets();
        return isset($buckets[$offset]);
    }

    /**
     * @param  mixed $offset
     * @return Aggregation
     */
    public function offsetGet($offset)
    {
        $buckets = $this->getBuckets();
        return new self($buckets[$offset]);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $buckets = $this->getBuckets();
        $buckets[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $buckets = $this->getBuckets();
        unset($buckets[$offset]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getBuckets());
    }

    /**
     * @param  mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $data = $this->getData();
        print_r($data);
        if (isset($data[$name])) {
            return $data[$name];
        }
        return null;
    }
}