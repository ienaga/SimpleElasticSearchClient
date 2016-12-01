# SimpleElasticSearchClient

ElasticSearch Simple Library for PHP


# Filter Search

```php
$client = new new Client([
    "end_point" => "URL"
]);

$data = $client
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


# Data Create

```php
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
    ->setQuery($query)
    ->create();
```


# Data Update

```php
$client = new new Client([
    "end_point" => "URL"
]);

$data = $client
    ->setIndex("index name")
    ->setType("type name")
    ->createFilter()
    ->match("user_id", $userId)
    ->attach()
    ->search();
    
$hits = $data["hits"]["hits"];
foreach ($hits as $hit) {
    
    $source = $hit["_source"];
    $source["status"] = 1;
    
    $client
        ->setIndex("index name")
        ->setType("type name")
        ->setId($hit["_id"])
        ->setQuery($source)
        ->update();
}
```