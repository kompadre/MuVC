<?php
$mt1 = microtime(true);

require_once __DIR__ . '/../modules/MuMVC/Root.php';

use MuMVC\Route;
use MuMVC\Cache;
use MuMVC\Controller;
use MuMVC\Registry;

Registry::instance()->set('caching', true);
Registry::instance()->set('caching_routes', true);	

try {
	Controller::instance()->dispatch();	
} catch (Exception $e) {
	echo "<h1>Some error occured</h1>";
	echo "<p>{$e->getMessage()}</p>";
}

echo '<div class="footer">' .
	 'Executed in ' . ( microtime(true) - $mt1 ) . '<br>' .
	 'Memory peak usage: ' . memory_get_peak_usage() .
	 '</div>';
