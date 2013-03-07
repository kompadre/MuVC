<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace MuMVC;

class Route {
	protected $routePatterns = array(
		'mumvc' => array(
			'/^\/MuMVC(\/(?P<action>[a-z]+))/', array('controller' => 'mumvc')),
		'default' => array(
			'/^\/(?P<controller>[a-z]+)(\/(?P<action>[a-z]+)){0,1}(\/(?<id>[0-9]+)){0,1}/', 
				//default controller and action
				array('controller' => 'controller', 'action' => 'index')),
	);	
	/**
	 * @param string $path
	 */
	public function __construct($path=null) {
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
				return $data;
			}
		}
		if (!isset($data)) {
			$data = $this->routePatterns['default'][1];
		}
		if (!isset($data['action'])) {
			echo 'It is not set';
		}
		return $data;
	}
		
	protected function getPathFromSuperGlobal() {
		return $_SERVER['REQUEST_URI'];
	}
	public function getDefault($string) {
		return $this->routePatterns['default'][1][$string];
	}
}