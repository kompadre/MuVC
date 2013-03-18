<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
namespace MuMVC\Cache;

interface ICacheDriver { 
	/**
	 * Restores a value from cache.
	 * @param string $key
	 * @return mixed
	 */
	public function fetch($key, &$success=null);
	/**
	 * Stores a value into cache 
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 */
	public function store($key, $value, $ttl);
	/**
	 * Clears the caches
	 * @return bool
	 */
	public function clear();
}