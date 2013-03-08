<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace Application\Controller;

use MuMVC\ActionController;
use MuMVC\Registry;

class Mobile extends ActionController {
	public function before() {
		$this->layout = 'layout_mobile.tpl';
		parent::before();
		$this->addCrumb('Mobile', '/mobile/');
	}
	public function indexAction() {  }
	public function error($code, $message) {
		$this->action = 'error' . $code;
		$this->before();
		if ($message) {
			$this->template->asigna('MESSAGE', $message);
		}
	}
	
	public function after() {
		$this->getBreadcrumbs();
		return parent::after();
	}
	
	public function getBreadcrumbs() {
		foreach(self::$crumbs as $crumb) {
			$this->layout->asigna('description', $crumb[0]);
			$this->layout->asigna('link', $crumb[1]);
			$this->layout->parse('crumb');
		}
	}
}