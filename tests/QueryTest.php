<?php

require_once __DIR__ . "/../src/ElasticSearch/Query.php";

use \SimpleElasticSearch\Client;

class QueryTest extends \PHPUnit_Framework_TestCase
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
    const TYPE = "query";

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

        sleep(1);

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

        sleep(1);
    }

    /**
     * test query search
     */
    public function testQuerySearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createQuery()
            ->match("status", 0)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 5);
    }

    /**
     * test query search
     */
    public function testQueryAndSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->createQuery()
            ->match("user_id", 1)
            ->match("status", 1)
            ->attach()
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 1);
    }
}