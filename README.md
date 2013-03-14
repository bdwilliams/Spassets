Spassets - SlimPHP Asset Manager
================================

TODO:
Twig Extension
Support for Less

Installation:
-------------

Use Composer



Example:
--------

index.php:

require 'vendor/autoload.php';

$assets = New \Spassets\Spassets();
$assets->minify = true;

$app->get('/', function () use ($app) {
	$css = $assets->printAssets('css', array('/css/bootstrap/bootstrap.min.css', '/css/bootstrap/bootstrap-responsive.min.css', '/css/custom.css'));
	$js = $assets->printAssets('js', array('/js/bootstrap/bootstrap.js', '/js/custom.js'));
    $app->render('index.html.twig', array('session' => $_SESSION, 'css' => $css, 'js' => $js));
});

index.html.twig:

