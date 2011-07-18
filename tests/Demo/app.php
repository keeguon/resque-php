<?php

if (false === class_exists('Symfony\Component\ClassLoader\UniversalClassLoader', false)) {
  require_once __DIR__.'/../../vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';
}

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony' => __DIR__.'/../../vendor'
  , 'Resque'  => __DIR__.'/../../src'
  , 'Demo'    => __DIR__.'/..'
));
$loader->register();

// Namespaces
use Symfony\Component\Yaml\Yaml;
use Resque\Resque;

// Globals
$APP_ENV = (isset($_ENV['APP_ENV'])) ? $_ENV['APP_ENV'] : 'development';

// Redis config
$redis_config = Yaml::parse(__DIR__.'/config/redis.yml');
Resque::redis("{$redis_config[$APP_ENV]['hostname']}:{$redis_config[$APP_ENV]['port']}");

// Push a job to a queue
Resque::push('broadcasts', array('class' => 'Process', 'args' => array("php /var/www/shopwiz.me/core/symfony shopwiz:send-brodcast")));

