<?php

require_once __DIR__.'/../vendor/autoload.php';

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Knp\Provider\ConsoleServiceProvider;
use Knp\Console\ConsoleEvents;
use Knp\Console\ConsoleEvent;
use Banana\Doctrine\Command\PersistenceCommand;

$app = new Silex\Application();

//Console
$app->register(new ConsoleServiceProvider(), array(
    'console.name'              => 'Banana Doctrine',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/../src'
));

$app['dispatcher']->addListener(ConsoleEvents::INIT, function(ConsoleEvent $event) {
    $app = $event->getApplication();
    $app->add(new PersistenceCommand());
});


//Doctrine
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'dbname'   => 'banana_doctrine',
        'password' => 'vagrant',
        'charset'  => 'UTF8'
    ),
));

//Doctrine ORM
$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.proxies_dir'           => __DIR__ . '/cache/doctrine/proxy',
    'db.orm.proxies_namespace'     => 'DoctrineProxy',
    'db.orm.cache'                 => new ArrayCache(),
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type'      => 'annotation',       // entity definition
        'path'      => __DIR__ . '/../src',   // path to your entity classes
        'namespace' => 'Banana\Doctrine\Entity', // your classes namespace
    )),
));

return $app;