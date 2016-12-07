<?php

require_once __DIR__ . "/../src/ElasticSearch/Client.php";

use \SimpleElasticSearch\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
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
    const TYPE = "user";

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
     * test create
     */
    public function testSearch()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search();

        $this->assertEquals($result->isFound(), true);
        $this->assertEquals($result->getHitCount(), 10);
    }

    /**
     * test update
     */
    public function testUpdate()
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

            $result = $client
                ->setIndex($hit->getIndex())
                ->setType($hit->getType())
                ->setId($hit->getId())
                ->setBody($hit->getSource())
                ->update();

            var_dump($result->getData());

//            // success
//            $this->assertArrayHasKey("_shards", $result);
//            $this->assertArrayHasKey("successful", $result["_shards"]);
//            $this->assertEquals($result["_shards"]["successful"], 1);
//
//            // not new
//            $this->assertArrayHasKey("created", $result);
//            $this->assertEquals($result["created"], false);
        }

        sleep(1);
    }

    /**
     * test delete
     */
    public function testDelete()
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

            $result = $client
                ->setIndex($hit->getIndex())
                ->setType($hit->getType())
                ->setId($hit->getId())
                ->delete();

            var_dump($result->getData());

//            // success
//            $this->assertArrayHasKey("_shards", $result);
//            $this->assertArrayHasKey("successful", $result["_shards"]);
//            $this->assertEquals($result["_shards"]["successful"], 1);
        }

        sleep(1);

//        $result = $client
//            ->setIndex(self::INDEX)
//            ->setType(self::TYPE)
//            ->search();
//
//        $this->assertEquals($result->isFound(), false);
    }
}