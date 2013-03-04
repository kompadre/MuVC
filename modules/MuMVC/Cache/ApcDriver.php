<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuMVC\Cache; 

class ApcDriver implements ICacheDriver {
	
	public function __construct() {
		if (!function_exists('apc_store')) {
			throw new Exception('APC is not enabled while trying to instantiate APC cache driver');
		}
	}
	
	public function store($key, $value, $ttl=null) {
		return apc_store($key, $value,$ttl);
	}
	
	public function fetch($key) {
		return apc_fetch($key);
	}
	
	public function clear() {
		apc_clear_cache();
	}
}