<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application;
spl_autoload_register(function($class) {
	echo "Trying to find $class<br>";
	$path = str_replace('\\', '/', substr($class, strpos($class, '\\')+1)) . '.php';
	if (file_exists( APP_ROOT . '/classes/' . $path)) {
		include APP_ROOT . '/classes/' . $path;
	}
});