Spassets - SlimPHP Asset Manager
================================

TODO:
Twig Extension
Support for Less

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

	<!DOCTYPE html>
	<html>
	  <head>
	    <title>Your Page</title>
	    {{ css }}
	  </head>
	  <body>
	    <h1>Hello, world!</h1>
	    {{ js }}
	  </body>
	</html>

Recommended:
------------

Gzip your assets by putting this in your root .htaccess file:

	<ifmodule mod_deflate.c>
	AddOutputFilterByType DEFLATE text/text text/html text/plain text/xml text/css application/x-javascript application/javascript text/javascript
	</ifmodule>
	
	