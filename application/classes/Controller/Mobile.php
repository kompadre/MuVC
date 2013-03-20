<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace Application\Controller;

use MuMVC\ActionController;
use MuMVC\Registry;
use MuMVC\Template;

class Mobile extends ActionController {
	public function before() {
		$this->layout = 'layout_mobile.tpl';
		parent::before();
		if (!is_object($this->template)) {
			$this->template = new Template('error/error404.tpl');
		}
		$this->template->asigna('path', '/mobile');
		$this->addCrumb('Home', '/mobile/');
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
		$this->showBreadcrumbs();
		return parent::after();
	}
	public function showBreadcrumbs() {
		$layout = $this->layout;
		foreach(self::$crumbs as $key => $crumb) {
			$layout->asigna('description', $key);
			$layout->asigna('link', $crumb);
			$layout->parse('crumb');
		}
	}
}
