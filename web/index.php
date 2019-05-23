<?php

require_once __DIR__.'/../vendor/autoload.php';
use Silex\Provider\DoctrineServiceProvider;
use BookingApp\Application;

$app = new Application();

$app['debug'] = true;

$app->run();
