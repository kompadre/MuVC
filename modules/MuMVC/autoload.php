<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace MuMVC;
$result = spl_autoload_register(function($class) {
	$path = str_replace('\\', '/', $class) . '.php';
	if (file_exists( MUMVC_ROOT . '/../../modules/' . $path)) {
		include MUMVC_ROOT . '/../../modules/' . $path;
	}
});
