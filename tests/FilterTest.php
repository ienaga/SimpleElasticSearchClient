<?php

require_once __DIR__ . "/../src/ElasticSearch/Client.php";

use \SimpleElasticSearch\Client;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    const END_POINT = "http://127.0.0.1:9200";

    /**
     * @var string
     */
    const INDEX = "client";

    /**
     * @var string
     */
    const TYPE = "filter";

    /**
     * setUP
     */
    public function setUp()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $client
            ->setMethod("DELETE")
            ->setPath(self::INDEX)
            ->send();

        sleep(5);

        for ($i = 1; $i <= 10; $i++) {

            $client
                ->setIndex(self::INDEX)
                ->setType(self::TYPE)
                ->setBody(array(
                    "user_id" => $i,
                    "status"  => ($i % 2)
                ))
                ->create();
        }

        sleep(5);
    }

    /**
     * test range
     */
    public function testRange()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->between("user_id", 1, 5)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 5);
    }

    /**
     * test filter search
     */
    public function testFilterSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addAnd("status", 0)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 5);
    }

    /**
     * test filter search
     */
    public function testFilterAndSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addAnd("user_id", 1)
            ->addAnd("status", 1)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 1);
    }

    /**
     * test filter search
     */
    public function testFilterOrSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addOr("user_id", 1)
            ->addOr("user_id", 2)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 2);
    }

    /**
     * test filter search
     */
    public function testFilterNotSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addNot("user_id", 1)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 9);
    }

    /**
     * test filter search
     */
    public function testFilterMultiSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addAnd("status", 0)
            ->between("user_id", 6)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 3);
    }
}