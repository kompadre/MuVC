<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace MuMVC;

define ('MUMVC_ROUTES_CACHE_KEY', MUMVC_CACHE_KEY_PREFFIX . 'Route_parsedRoutes'); 
define ('MUMVC_ROUTE_REASONABLECACHESIZE', 200);
class Route {
	
	static protected $parsedRoutes = array();
	protected $currentRoute;
	protected $currentPath;
	
	protected $routePatterns = array(
		'mumvc' => array(
			'/^\/MuMVC(\/(?P<action>[a-z]+))*/', array('controller' => 'mumvc')),
		'mobile_mumvc' => array(
			'/^\/mobile\/MuMVC(\/(?P<action>[a-z]+))*/', array('controller' => 'mobile\mumvc')),
						
		'default' => array(
			'/^\/(?P<controller>[a-z]+)(\/(?P<action>[a-z]+)){0,1}(\/(?<id>[0-9]+)){0,1}/', 
				//default controller and action
				array('controller' => 'controller', 'action' => 'index')),
	);	
	/**
	 * @param string $path
	 */
	public function __construct($path=null) {
		if ( Registry::get('caching_routes') && 
				$parsedRoutes = Cache::instance()->fetch( MUMVC_ROUTES_CACHE_KEY)) {
			Route::$parsedRoutes = $parsedRoutes;
		}
		$this->parse($path);
	}
	public function parse($path=null) {
		if ($path === null) {
			$path = $this->getPathFromSuperGlobal();
		}		
		// Clean up the path		
		$first = 0; 
		$last = strlen($path);
		
		if ($pos = strpos($path, MUMVC_SITE_PREFIX) !== FALSE ) {
			$first = $pos + (strlen(MUMVC_SITE_PREFIX)-1);
		}
		
		if ($pos = strpos($path, '?') !== FALSE)
			$last = $pos;
		
		if (($last - $first) > 0)
			$path = substr($path, $first, ($last-$first));

		$path = preg_replace('/[^a-zA-Z0-9_\/]/', '', $path);		

		if (isset(Route::$parsedRoutes[$path])) {
			return $this->currentRoute = Route::$parsedRoutes[$path];
		}
		
		$this->currentPath = $path;
				
		foreach($this->routePatterns as $name => $pattern) {
			list($expression, $defaults) = $pattern;
			if (preg_match($expression, $path, $matches)) {
				foreach($matches as $key => $val) {
					if (is_string($key)) {
						if (is_array($matches[$key]) && !empty($matches[$key][0]))
							$data[$key] = array_shift($matches[$key]);
						else if (is_string($matches[$key]))
							$data[$key] = $matches[$key];
					}
				}
				foreach($defaults as $key => $val) {
					if (!isset($data[$key]))
						$data[$key] = $defaults[$key];
				}
				if (!isset($data['action'])) {
					$data['action'] = $this->routePatterns['default'][1]['action'];
				}
				else {
				}
				return $this->currentRoute = $data;
			}
		}
		if (!isset($data)) {
			$data = $this->routePatterns['default'][1];
		}
		return $this->currentRoute = $data;
	}
	protected function getPathFromSuperGlobal() {
		return $_SERVER['REQUEST_URI'];
	}
	public function getDefault($string) {
		return $this->routePatterns['default'][1][$string];
	}
	public function persist() {
		if (Registry::get('caching_routes') === FALSE)
			return;
		/*
		* FIXME: possible bottleneck here. If I store all possible paths
		* here, I'll be golden in fantasy world. But if some scriptkiddo
		* starts spamming randomly generated URLs, they all will go into
		* my little array and possibly deny me of service.
		*/
		if ( count(Route::$parsedRoutes) <= MUMVC_ROUTE_REASONABLECACHESIZE) {
			Route::$parsedRoutes[$this->currentPath] = $this->currentRoute;
			Cache::instance()->store( MUMVC_ROUTES_CACHE_KEY, Route::$parsedRoutes);
		}
	}
	public function getCurrent() {
		return $this->currentRoute;
	}
}