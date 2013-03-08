<?php
$mt1 = microtime(true);

require_once __DIR__ . '/../modules/MuMVC/Root.php';
require_once __DIR__ . '/../modules/MuCMS/autoload.php';

use MuMVC\Route;
use MuMVC\Cache;
use MuMVC\Controller;
use MuMVC\Registry;
use MuCMS\Cms;

Registry::instance()->set('caching', false);
Registry::instance()->set('caching_routes', false);	

try {
	Controller::instance()->dispatch();	
} catch (Exception $e) {
	echo "<h1>Some error occured</h1>";
	echo "<p>{$e->getMessage()}</p>";
}

if (! (Controller::instance()->getController() instanceof \Application\Controller\Mobile)) {
	echo '<div class="footer">';
		 var_dump(Controller::instance()->getRoute()->getCurrent());
	echo '<br>Executed in ' . ( microtime(true) - $mt1 ) . '<br>' .
		 'Memory peak usage: ' . memory_get_peak_usage() .
		 '</div>';
}