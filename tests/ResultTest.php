<?php

require_once __DIR__ . "/../src/ElasticSearch/Result.php";

use \SimpleElasticSearch\Client;

class ResultTest extends \PHPUnit_Framework_TestCase
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
    const TYPE = "result";

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
                    "user_id"     => $i,
                    "status"      => ($i % 2),
                    "update_flag" => 0
                ))
                ->create();
        }

        sleep(5);
    }

    /**
     * test ArrayAccess
     */
    public function testArrayAccess()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addSort("user_id")
            ->attach()
            ->search();

        $hit = $result[0];
        $this->assertEquals($hit->isFound(), true);
        $this->assertEquals($hit["user_id"], 1);
        $this->assertEquals($hit["status"], 1);
        $this->assertEquals($hit["update_flag"], 0);

        unset($result[0]);
        $this->assertEquals(count($result), 9);
    }

    /**
     * test Iterator
     */
    public function testIterator()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search();

        $this->assertEquals($result->isFound(), true);
        foreach ($result as $hit) {
            $this->assertEquals($hit->isFound(), true);
        }
    }

    /**
     * test Countable
     */
    public function testCountable()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals(count($result), 10);

        foreach ($result as $hit) {
            $this->assertEquals(count($hit), 3);
        }
    }

    /**
     * test get
     */
    public function testGet()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addSort("user_id")
            ->attach()
            ->search();

        foreach ($result as $key => $hit) {
            $this->assertEquals($hit->user_id, $key+1);
            $this->assertEquals($hit->status, 1);
            $this->assertEquals($hit->update_flag, 0);
        }
    }

    /**
     * test set
     */
    public function testSet()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search();

        foreach ($result as $hit) {
            $hit->update_flag = 1;
        }

        foreach ($result as $hit) {
            $this->assertEquals($hit->update_flag, 1);
        }
    }
}