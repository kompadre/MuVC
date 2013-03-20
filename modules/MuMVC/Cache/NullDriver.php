<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
namespace MuMVC\Cache;

class NullDriver { 
	public function fetch($key, &$success=null) {
		return $success = FALSE;
	}
	public function store($key, $value, $ttl) {
		return FALSE;
	}
	public function clear() {
		return FALSE;
	}
}