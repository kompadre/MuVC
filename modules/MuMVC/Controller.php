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
			// To let actual Controller decide wether to cache or not
			$actionControllerString = 'Application\\Controller\\' . ucfirst($route['controller']);
			$actionController = new $actionControllerString();
			$actionController->before();
			if (Registry::get('caching') && ($cachedContent = Cache::instance()->fetchContent($route)) ) {
				echo $cachedContent;
				return;
			}
			$actionMethod = $route['action'] . 'Action';
			if (method_exists($actionController, $actionMethod)) {
				$actionController->$actionMethod();
			}
			else {
				$actionController->defaultAction($actionMethod);
			}
			$content = $actionController->after();
			if (Registry::instance()->get('caching')) {			
				Cache::instance()->saveContent($route, $content);
			}
			echo $content;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}
?>
