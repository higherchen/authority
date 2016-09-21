<?php

define('ROOT', __DIR__.'/..');

require ROOT.'/vendor/autoload.php';

$socket = new Thrift\Transport\TSocket('127.0.0.1', 8091);
$transport = new Thrift\Transport\TFramedTransport($socket);
$protocol = new Thrift\Protocol\TBinaryProtocol($transport);
$transport->open();

$client = new Authority\AuthorityServiceClient($protocol);
$ret = $client->getUsers(new Authority\Search());
var_dump($ret);

$transport->close();
