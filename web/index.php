<?php

require(__DIR__.'/../vendor/autoload.php');

$app = new Silex\Application;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/heroku', function() use($app) {
  return $app['twig']->render('index.twig');
});

$app->get('/', function() use($app) {
    $json = file_get_contents('https://raw.githubusercontent.com/duckduckgo/zeroclickinfo-goodies/master/share/goodie/cheat_sheets/json/harry-potter-spells.json');
    $data = json_decode($json, $array = true);
    $spell = $data['sections']['A'][0]['key'];
    return "<pre>".\Cowsayphp\Cow::say($spell)."</pre>";
});

$app->run();
