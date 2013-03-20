<?php
$mt1 = microtime(true);

require_once __DIR__ . '/../modules/MuMVC/Root.php';

use MuMVC\Route;
use MuMVC\Cache;
use MuMVC\Controller;
use MuMVC\Registry;
use MuMVC\Log;

Log::add('Starting UP', Log::SEVERITY_NOTICE);

try {
	Controller::instance()->dispatch();	
} catch (Exception $e) {
	if (!Registry::get('production')) {
		echo "<h1>Some error occured</h1>";
		echo "<p>{$e->getMessage()}</p>";
		foreach( Registry::get('debug_backtrace') as $stackedCall) {
			echo $stackedCall['class'] . '::' . $stackedCall['function'] . 
				'(' . print_r( $stackedCall['args'], true) . ')<br>';
		}
	}
}

if ( stripos(get_class( Controller::instance()->getController()), 'mobile') === FALSE) {
	echo '<div class="footer">';
	echo 'Executed in ' . ( microtime(true) - $mt1 ) . '<br>' .
		 'Memory peak usage: ' . memory_get_peak_usage() . '<br>' .
		 'Logged messages: <br>' . Log::instance()->showMessages() . '<br>';
		 var_dump( Controller::instance()->getRoute()->getCurrent());	
	echo '</div>';
}
