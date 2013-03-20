<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuMVC;

class Exception extends \Exception {
	public function __construct($message, $code) {
		Registry::instance()->debug_backtrace = debug_backtrace();
		parent::__construct($message, $code);
	}
}