<?php
list($micro, $time) = explode(' ', microtime());
$mt1 = $micro + ($time%100);


require_once __DIR__ . '/../modules/MuMVC/Root.php';

use MuMVC\Route;
use MuMVC\Cache;
use MuMVC\Controller;

$route = new Route();
$parsedRoute = $route->parse();

if (!Cache::instance()->fetchContent($parsedRoute)) {
	Cache::instance()->saveContent(
		$parsedRoute, 
		$content = Controller::instance()->dispatch($parsedRoute)
	);
	echo $content;
}

list($micro, $time) = explode(' ', microtime());
$mt2 = $micro + ($time%100);
if ($mt2 < $mt1)
	$mt2 += 100;

echo "Executed in " . ($mt2 - $mt1) . "<br>";
echo "Memory peak usage: " . memory_get_peak_usage();
