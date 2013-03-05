<?php
list($micro, $time) = explode(' ', microtime());
$mt1 = $micro + ($time%100);

require_once __DIR__ . '/../modules/MuMVC/Root.php';
use MuMVC\Route;
use MuMVC\Cache;
use MuMVC\Controller;
use MuMVC\Registry;

Registry::instance()->set('caching', true);

$route = new Route();
$parsedRoute = $route->parse();


try {
	Controller::instance()->dispatch($parsedRoute);	
} catch (Exception $e) {
	if (404 == $e->getCode()) {
		echo "<h1>404 Page not found!</h1><br>";
	}
}

list($micro, $time) = explode(' ', microtime());
$mt2 = $micro + ($time%100);
if ($mt2 < $mt1)
	$mt2 += 100;

echo "Executed in " . ($mt2 - $mt1) . "<br>";
echo "Memory peak usage: " . memory_get_peak_usage();