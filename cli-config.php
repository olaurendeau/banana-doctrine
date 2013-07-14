<?php

$app = require_once __DIR__.'/app/bootstrap.php';

/**
 * @var \Doctrine\ORM\EntityManager
 */
$em = $app['db.orm.em'];

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));