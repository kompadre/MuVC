<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuCMS;
spl_autoload_register(function($class) {
	
	$path = str_replace('\\', '/', substr($class, strpos($class, '\\')+1)) . '.php';
	if (file_exists( __DIR__ . '/classes/' . $path)) {
		include __DIR__ . '/classes/' . $path;
	}
});
