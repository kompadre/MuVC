<?php
namespace MuMVC;

class Controller extends Root implements ICacheable {
	private $_output;
	private $controller;
	/**
	 * @return MuMVC\Controller
	 */
	public static function instance() {
		return parent::instance(__CLASS__);
	}
	
	protected function __construct() {
	}
	public function cacheSave() {
		return $this->_output;
	}
	public function cacheLoad($data) {
		$this->_output = $data;
	}
	public function helloWorld() {
		echo "Hello world!\n";
	}
	public function index() {
		return View::instance()->render();
	}
	public function dispatch($route) {
		try {
			$actionControllerString = 'Application\\Controller\\' . ucfirst($route['controller']);
			$actionController = new $actionControllerString();
			$actionController->before();
			$actionMethod = $route['action'] . 'Action';
			if (method_exists($actionController, $actionMethod)) {
				$actionController->$actionMethod();
			}
			else {
				$actionController->defaultAction($actionMethod);
			}
			$actionController->after();
		} catch (Exception $e) {
			
		}
	}
}
?>
