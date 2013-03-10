<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Controller\Mobile;

use Application\Controller\Mobile;

class Mumvc extends Mobile { 
	public function before() {
		parent::before();
		$this->addCrumb('MuMVC', '/mobile/MuMVC/');
	}
	public function licenceAction() {
		die ( '<pre>' . file_get_contents(APP_PATH . '/../licence.txt') . '</pre>' );
	}
	public function teamAction() {
		$this->addCrumb('Team', '/mobile/MuMVC/team');
	}
	public function indexAction() {  }
	public function error($code, $message) {
		$this->controller = 'Mobile';
		$this->action = 'error' . $code;
		parent::error($code, $message);
	}
}
