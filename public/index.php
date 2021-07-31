<?php

require_once 'vendor/autoload.php';

use Nyholm\Psr7\Response;
use Spiral\RoadRunner;
use Nyholm\Psr7\Factory\Psr17Factory;

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr17Factory();

$worker = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

$shmop = shmop_open(1123, 'c', 0777, 1024);

while ($req = $worker->waitRequest()) {
    try {
        $rsp = new Response();

        $iterator = shmop_read($shmop, 0, 100);
        $iterator = str_from_mem($iterator);
        $rsp->getBody()->write('iterator: ' . $iterator);

        $iterator = (int)$iterator;
        $iterator++;
        shmop_write($shmop, str_to_nts($iterator), 0);

        $worker->respond($rsp);
    } catch (\Throwable $e) {
        $worker->getWorker()->error((string)$e);
    }
}

function str_from_mem($value) {
    $i = strpos($value, "\0");
    if ($i === false) {
        return $value;
    }

    return substr($value, 0, $i);
}

function str_to_nts($value) {
    return "$value\0";
}

function getShmop(): Shmop
{
//    $shmKey = ftok(__FILE__, 't');
    $shmop = shmop_open(1, 'n', 0777, 1024);
    if ($shmop === false) {
        return shmop_open(1, 'c', 0777, 1024);
    }

    shmop_write($shmop, str_to_nts(1), 0);
    return $shmop;
}
