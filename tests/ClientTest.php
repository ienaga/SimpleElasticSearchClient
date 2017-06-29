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
     * @var string
     */
    const DELETE_TYPE = "delete";

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
                    "status" => ($i % 2),
                    "update_flag" => 0
                ))
                ->create();
        }

        // delete only data
        for ($i = 1; $i <= 10; $i++) {
            $client
                ->setIndex(self::INDEX)
                ->setType(self::DELETE_TYPE)
                ->setBody(array(
                    "user_id" => $i,
                    "status" => ($i % 2),
                    "update_flag" => 0
                ))
                ->create();
        }

        sleep(5);
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
            $result = $client
                ->setIndex($hit->getIndex())
                ->setType($hit->getType())
                ->setId($hit->getId())
                ->setBody($hit->getSource())
                ->update();

            // success
            $this->assertArrayHasKey("_version", $result);
            $this->assertEquals($result["_version"], 2);

            // not new
            $this->assertArrayHasKey("created", $result);
            $this->assertEquals($result["created"], false);
        }

        sleep(5);
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

            $result = $client
                ->setIndex($hit->getIndex())
                ->setType($hit->getType())
                ->setId($hit->getId())
                ->delete();

            // success
            $this->assertArrayHasKey("_version", $result);
            $this->assertEquals($result["_version"], 2);
        }

        sleep(5);

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search();

        $this->assertEquals($result->isFound(), false);
    }

    /**
     * test delete
     */
    public function testDeleteType()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search()
            ->getHitCount();

        if ($result > 0) {
            $result = $client
                ->setIndex(self::INDEX)
                ->setType(self::TYPE)
                ->deleteType();

            $this->assertFalse(isset($result["error"]));
        }

        sleep(5);

        $result = $client
            ->setIndex(self::INDEX)
            ->setType(self::TYPE)
            ->search()
            ->getHitCount();

        $this->assertEquals($result, 0);
    }

    /**
     * test upserts
     */
    public function testUpserts()
    {
        $client = new Client(array(
            "end_point" => self::END_POINT
        ));

        $type = "type_for_test";
        $user_id = 1;

        //1)新規Type を作成して count に 1 をセット ...1ループ目
        //2)既存Type の count をインクリメント ...残り19ループ目
        for ($i = 0; $i < 20; $i++) {

            $client
                ->setIndex(self::INDEX)
                ->setType($type)
                ->setId($user_id)
                ->setBody([
                    'script' => 'ctx._source.count += 1',
                    'upsert' => [
                        'count' => 1,
                        'update_at' => time()
                    ]
                ])
                ->upserts();
        }

        sleep(5);

        $result = $client
            ->setIndex(self::INDEX)
            ->setType($type)
            ->search()
            ->getHitCount();

        //データ件数が1件であることを確認
        //echo "----- 件数=" . $result . "\n";
        $this->assertEquals($result, 1);

        $result = $client
            ->setIndex(self::INDEX)
            ->setType($type)
            ->createFilter()
            ->attach()
            ->search();

        $source = $result[0]->getSource();

        //カウントが20までインクリメントされていることを確認
        //echo "----- カウント=" . $source['count'] . "\n";
        $this->assertEquals($source['count'], 20);
    }

}