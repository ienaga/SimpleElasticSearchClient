# SimpleElasticSearchClient

ElasticSearch Simple Library for PHP

# ElasticSearch Version

- 2.3.x


[![Build Status](https://travis-ci.org/ienaga/SimpleElasticSearchClient.svg?branch=master)](https://travis-ci.org/ienaga/SimpleElasticSearchClient)

[![Latest Stable Version](https://poser.pugx.org/ienaga/simple-elasticsearch-client/v/stable)](https://packagist.org/packages/ienaga/simple-elasticsearch-client) [![Total Downloads](https://poser.pugx.org/ienaga/simple-elasticsearch-client/downloads)](https://packagist.org/packages/ienaga/simple-elasticsearch-client) [![Latest Unstable Version](https://poser.pugx.org/ienaga/simple-elasticsearch-client/v/unstable)](https://packagist.org/packages/ienaga/simple-elasticsearch-client) [![License](https://poser.pugx.org/ienaga/simple-elasticsearch-client/license)](https://packagist.org/packages/ienaga/simple-elasticsearch-client)


# Filter Search

```php
use \SimpleElasticSearch\Client;

$client = new new Client([
    "end_point" => "URL"
]);

$result = $client
    ->setIndex("index name")
    ->setType("type name")
    ->createFilter() // filter search start
    ->match("status", $status) // match case
    ->setFrom($offset) // offset 
    ->setSize($limit) // limit
    ->addSort("price", $sort) // sort
    ->addAggregation("user_id") // group by
    ->attach() // filter search end
    ->search(); // execute search
```

# Result

```php
use \SimpleElasticSearch\Client;

$client = new new Client([
    "end_point" => "URL"
]);

$result = $client
    ->setIndex("index name")
    ->setType("type name")
    ->createFilter() // filter search start
    ->match("status", $status) // match case
    ->setFrom($offset) // offset 
    ->setSize($limit) // limit
    ->addSort("price", $sort) // sort
    ->addAggregation("user_id") // group by
    ->attach() // filter search end
    ->search(); // execute search
    
// found
if ($result->isFound()) {
    // ArrayAccess, Iterator, Countable
    foreach ($result as $hit) {
        // Result Singular 
        // $hit->getIndex();
        // $hit->getType();
        // $hit->getId();
        // $hit->property;
    }
}
```

# Data Create

```php
use \SimpleElasticSearch\Client;

$client = new new Client([
    "end_point" => "URL"
]);

$query = [
    "status"  => 0,
    "price"   => 100,
    "user_id" => 1,
];

$client
    ->setIndex("index name")
    ->setType("type name")
    ->setBody($query)
    ->create();
```


# Data Update Plural

```php
use \SimpleElasticSearch\Client;

$client = new new \SimpleElasticSearch\Client([
    "end_point" => "URL"
]);

$result = $client
    ->setIndex("index name")
    ->setType("type name")
    ->createFilter()
    ->match("user_id", $userId)
    ->attach()
    ->search();
    
if ($result->isFound()) {
    foreach ($result as $hit) {
        
        $hit->status = 1;
        
        $client
            ->setIndex("index name")
            ->setType("type name")
            ->setId($hit["_id"])
            ->setBody($hit->getSource())
            ->update();
    }
}
```

# Data Update Singular

```php
use \SimpleElasticSearch\Client;

$client = new new \SimpleElasticSearch\Client([
    "end_point" => "URL"
]);

$result = $client
    ->setIndex("index name")
    ->setType("type name")
    ->setId("id name")
    ->get();
    
if ($result->isFound()) {
    $result->status = 1;
    
    $client
        ->setIndex($result->getIndex())
        ->setType($result->getType())
        ->setId($result->getId())
        ->setBody($result->getSource())
        ->update();
}
```

# Data Delete 

```php
use \SimpleElasticSearch\Client;

$client = new new \SimpleElasticSearch\Client([
    "end_point" => "URL"
]);

$client
    ->setIndex("index name")
    ->setType("type name")
    ->setId("id name")
    ->delete();
```