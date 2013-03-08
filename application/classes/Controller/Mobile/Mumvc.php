<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Controller\Mobile;

use Application\Controller\Mobile;

class Mumvc extends Mobile { 
	public function __construct() {
		$this->controller = 'Mobile/Mumvc';
		parent::__construct();
	}
	public function before() {
		parent::before();
		$this->addCrumb('MuMVC', '/mobile/MuMVC/');
	}
	public function index() {  }
	public function error($code, $message) {
		$this->controller = 'Mobile';
		$this->action = 'error' . $code;
		parent::error($code, $message);
	}
}