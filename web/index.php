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
    return cowspell();
});

$app->get('/plain', function() use($app) {
    return cowspell($plain = true);
});

$app->get('/info', function() use($app) {
    return '<!DOCTYPE html><body><a href="https://github.com/scratchers/icanhazcowspell.gq">https://github.com/scratchers/icanhazcowspell.gq</a></body></html>';
});

$app->run();

function cowspell(bool $plain = false) : string
{
    $json = file_get_contents('https://raw.githubusercontent.com/duckduckgo/zeroclickinfo-goodies/master/share/goodie/cheat_sheets/json/harry-potter-spells.json');
    $data = json_decode($json, $array = true);

    $section = array_rand($data['sections']);
    $index = array_rand($data['sections'][$section]);
    $spell = $data['sections'][$section][$index]['key'];
    $value = $data['sections'][$section][$index]['val'];

    $cowspell = \Cowsayphp\Cow::say($spell.PHP_EOL.$value);

    if ($plain) {
        return $cowspell;
    }

    return "<!DOCTYPE html><body><pre>$cowspell</pre></body></html>";
}
