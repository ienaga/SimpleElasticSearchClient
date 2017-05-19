<?php

require_once __DIR__ . "/../src/ElasticSearch/BaseSearch.php";

use \SimpleElasticSearch\Client;

class BaseSearchTest extends \PHPUnit_Framework_TestCase
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
    const TYPE = "base_search";

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

        $time = time();
        for ($i = 1; $i <= 10; $i++) {
            $client
                ->setIndex(self::INDEX)
                ->setType(self::TYPE)
                ->setBody(array(
                    "user_id"     => $i,
                    "status"      => ($i % 2),
                    "count"       => ($i * 10),
                    "create_time" => $time++
                ))
                ->create();
        }

        sleep(5);
    }

    /**
     * test sort
     */
    public function testSort()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->addAnd("status", 0)
            ->addSort("create_time", "desc")
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 5);

        $baseKey = 10;
        foreach ($result as $hit) {
            $this->assertEquals($hit->user_id, $baseKey);
            $baseKey -= 2;
        }
    }

    /**
     * test aggregation
     */
    public function testAggregation()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createFilter()
            ->setAggregation("status")
            ->addAggregation("status", "count", "sum", "count")
            ->attach()
            ->search();

        print_r($result->getData());

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 10);

        $aggregations = $result->getAggregation("status");
        foreach ($aggregations as $key => $aggregation) {
            switch ($key) {
                case 0:
                    $this->assertEquals($aggregation->getValue(), 300);
                    break;
                case 1:
                    $this->assertEquals($aggregation->getValue(), 250);
                    break;
            }
        }
    }

}