<?php

namespace SimpleElasticSearch;

use \SimpleApi\Client as BaseClient;

class Client extends BaseClient
{

    const HOST = "127.0.0.1";
    const PORT = "9200";

    /**
     * @var Client
     */
    private static $singleton = null;

    /**
     * @return Client
     */
    public static function getClient()
    {
        if (self::$singleton === null) {
            self::$singleton = new Client(self::getConfig());
        }
        return self::$singleton;
    }

    /**
     * @return array
     */
    public static function getConfig()
    {
        return \Phalcon\DI::getDefault()->get("config")->get("elastica")->toArray();
    }

    public function __construct($config = array())
    {

    }

    /**
     * execute multi
     * @return void
     */
    public static function execute()
    {
        self::$singleton->multi();
    }

    /**
     * @param  string $database
     * @param  string $table
     * @param  array  $record
     * @return void
     */
    public static function create($database, $table, $record = array())
    {
        self::getClient()
            ->setMethod("POST")
            ->setPath(implode("/",[$database, $table]))
            ->setParameters($record)
            ->append();
    }

    /**
     * @param  string $database
     * @param  string $table
     * @param  string $query
     * @return array
     */
    public static function get($database, $table, $query = "_search")
    {
        return self::getClient()
            ->setPath(implode("/",[$database, $table, $query]))
            ->send();
    }


    public static function update()
    {
    }

    public static function delete()
    {
    }
}