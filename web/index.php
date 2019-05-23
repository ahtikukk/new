<?php

require_once __DIR__.'/../vendor/autoload.php';
use Silex\Provider\DoctrineServiceProvider;

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/hello', function () {
    return 'Hello world!';
});

// Database configuration
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'dbname' => 'silex',
        'user' => 'root',
        'password' => '',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    ],
]);

// Creating a table if it doesn't exist yet
if (!$app['db']->getSchemaManager()->tablesExist('bookings')) {
    $app['db']->executeQuery("CREATE TABLE bookings (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstName VARCHAR(40) NOT NULL,
        lastName VARCHAR(40) NOT NULL,
        phone VARCHAR(10) NOT NULL,
        email VARCHAR(20) DEFAULT NULL,
        birthday DATE NOT NULL,
        startDate DATE NOT NULL,
        endDate DATE NOT NULL,
        arrivalTime TIME DEFAULT NULL,
        additionalInformation TEXT,
        nrOfPeople INT NOT NULL,
        payingMethod VARCHAR(10) NOT NULL
    );");
}

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__.'/../views',
]);

$app->get('/bookings/create', function () use ($app) {
    return $app['twig']->render('base.html.twig');
});


$app->run();
