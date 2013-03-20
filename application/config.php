<?php
use MuMVC\Registry;
use MuMVC\Cache;

$cache = Cache::instance('apc'); // Can be apc or null atm
$reg = Registry::instance();

include_once( __DIR__ . '/localconfig.php');

// Set to TRUE to go production
if ($reg->production == FALSE) {
	// Production config options go here
	$reg->caching = TRUE;
	$reg->caching_db = TRUE;
	$reg->caching_routes = TRUE;
}
else {
	// Development config options go here
	$reg->caching = FALSE;
	$reg->caching_db = FALSE;
	$reg->caching_routes = FALSE;
}
$reg->route_patterns = array(
	'root' => array('^/$', array('controller' => 'controller', 'action' => 'index')), 
	'mobile_mumvc' => array('/mobile/MuMVC(/:action)', array(
		'controller' => 'mobile_mumvc', 
		'action' => 'index')),	
	'mumvc' => array('/MuMVC(/:action)', array(
		'controller' => 'mumvc', 
		'action' => 'index')),
	'default' => array('/(:controller)(/:action)(/:id)', array(
		// defaults
		'controller' => 'error',
		'action'	 => 'index')),
);

$reg->admin_email 	= 'kompadre@gmail.com';
$reg->log_file 		= '/tmp/mumvc.log';
