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
	protected $params;
	protected $auto_render = TRUE;
	
	public function __construct($action='default', $params=array(), $controller=FALSE) {
		$this->controller = $controller ? $controller : get_class($this);
		$this->action = $action;
		$this->params = $params;
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
		return FALSE;
	}
	public function before() {
		if ($this->auto_render) {
			$templateFile = $this->findTemplate();
			if ($templateFile) {
				$this->template = new Template($this->findTemplate());
			}
			$this->layout = TPL_DEFAULT_LAYOUT;
		}
	}
	public function after() {
		if ($this->auto_render) {
			$tpl_layout = new Template($this->layout);
			$tpl_layout->asigna('CONTENT', $this->template->render());
			return $tpl_layout->render();
		}
	}
	public function defaultAction($originalAction) {
		if (!is_object($this->template)) {
			$this->error(404, $originalAction);
		}
	}
	public function setAction($action) {
		$this->action = $action;
	}
	public function setController($controller) {
		$this->controller = $controller;
	}
	public function error($code, $message='') {
		$this->controller = 'error';
		$this->action = 'error' . $code;
		$this->before();
		if ($message) {
			$this->template->asigna('MESSAGE', $message);
		}
	}
}