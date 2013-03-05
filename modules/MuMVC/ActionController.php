<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuMVC;

abstract class ActionController {
	protected $controller;
	protected $action;
	protected $template;
	protected $layout;
	public function __construct($action='default', $controller=null) {
		if ($controller == null) {
			$this->controller = get_class($this);
		}
		else {
			$this->controller = $controller;
		}
		$this->action = $action;
	}
	protected function findTemplate($templatePath=null) {
		if ($templatePath == null) {
			$controllerName = $this->controller;
			if (($pos = strrpos($controllerName, '\\')) !== false) {
				$controllerName = substr($controllerName, $pos+1);
			}
			$controllerName = strtolower($controllerName);
			$templatePath = APP_PATH . '/views/' . $controllerName . '/' . $this->action . '.tpl';
		}
		if (file_exists($templatePath)) {
			return $templatePath;
		}
		return APP_DEFAULT_TPL;
	}
	public function before() {
		$this->template = new Template($this->findTemplate());
		$this->layout = TPL_DEFAULT_LAYOUT;
	}
	public function after() {
		$tpl_layout = new Template($this->layout);
		$tpl_layout->asigna('CONTENT', $this->template->render());
		return $tpl_layout->render();
	}
}