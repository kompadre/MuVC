<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuMVC;

abstract class ActionController {
	/**
	 * Controller alias that's used to find propper view etc
	 * @var unknown
	 */
	protected $controller;
	protected $action;
	protected $template;
	protected $layout;
	protected $params;
	protected $auto_render = TRUE;
	protected static $crumbs = array();
	
	public function __construct($action='index', $params=array(), $controller=FALSE) {
		if (!isset($this->controller)) {
			$controller = $controller ? $controller : get_class($this);
			$controller = str_replace(APP_NAMESPACE . '\Controller\\', '', $controller);
			$this->controller = $controller;
		}
		$this->action = $action;
		$this->params = $params;
	}
	
	protected function findTemplate($templatePath=null) {
		if ($templatePath == null) {
			$controllerName = str_replace('\\', '/', $this->controller);
			$controllerName = strtolower($controllerName);
			$templatePath = $controllerName . '/' . $this->action . '.tpl';
		}
		if (file_exists(APP_VIEW . '/' . $templatePath)) {
			return $templatePath;
		}
		return FALSE;
	}
	public function before() {
		if ($this->auto_render) {
			$templateFile = $this->findTemplate();
			if ($templateFile) {
				$this->template = new Template($templateFile);
			}
			if ($this->layout) {
				$this->layout = new Template($this->layout);
			}
			else {
				$this->layout = new Template( APP_DEFAULT_LAYOUT);
			}
		}
	}
	public function addCrumb( $string, $link) {
		ActionController::$crumbs[$string] = $link;
		// array_push(ActionController::$crumbs, array($string, $link));		
	}
	public function after() {
		if ($this->auto_render) {
			$tpl_layout = $this->layout;
			if (is_object($this->template)) {
				$tpl_layout->asigna('CONTENT', $this->template->render());
			}
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
		$this->template = new Template( $this->findTemplate());
		if (!$this->template) {
			die('Couldn\'t find error template.');
		}
		
		if ($message) {
			$this->template->asigna('MESSAGE', $message);
		}
	}
}