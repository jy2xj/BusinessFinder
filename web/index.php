<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.html');
  //return str_repeat('Hello', getenv('TIMES'));
});

$app->get('/about.html', function() use($app) {
  $app['monolog']->addDebug('cowsay');
  return $app['twig']->render('about.html');
  //return "<pre>".\Cowsayphp\Cow::say("Cool beans")."</pre>";
});

$app->run();
