<?php

namespace BookingApp;

use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;

/**
 * Custom Application class that hold our application specifix functionality.
 */
class Application extends SilexApplication
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->configureServices();
        $this->createDBTables();
        $this->configureControllers();
    }

    /**
     * Config app options and register services.
     */
    private function configureServices()
    {
        $this['debug'] = true;

        $this->register(new TwigServiceProvider(), [
            'twig.path' => __DIR__.'/../views',
        ]);    
               
    }

    private function createDBTables(){
        // Database configuration
        $this->register(new DoctrineServiceProvider(), [
            'db.options' => [
                'dbname' => 'silex',
                'user' => 'root',
                'password' => '',
                'host' => 'localhost',
                'driver' => 'pdo_mysql',
            ],
        ]);

        // Creating a table if it doesn't exist yet
        if (!$this['db']->getSchemaManager()->tablesExist('bookings')) {
            $this['db']->executeQuery("CREATE TABLE bookings (
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
        
    }

    private function configureControllers(){
         $this->get('/bookings/create', function () {
            return $this['twig']->render('base.html.twig');
        });
    }
}
