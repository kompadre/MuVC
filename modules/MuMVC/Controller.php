<?php
namespace MuMVC;

class Controller extends Root implements ICacheable {
	private $controller;
	private $route;
	/**
	 * @return MuMVC\Controller
	 */
	public static function instance() {
		return parent::instance(__CLASS__);
	}
	/**
	 * @return MuMVC\ActionController
	 */
	public function getController() {
		return $this->controller;
	}
	protected function __construct() {
		$this->route = new Route();
	}
	public function cacheSave() {
		return $this->_output;
	}
	public function cacheLoad($data) {
		$this->_output = $data;
	}
	public function getRoute() {
		return $this->route;
	}
	public function dispatch() {
		$route = $this->route->parse();
		$controller = strtolower( str_replace('_', '\\', $route['controller']));
		
		if (strpos($controller, '\\') === FALSE) {
			$controller = ucfirst($controller);
		}
		else {
			$spacesIn = explode('\\', $controller);
			$spacesOut= array();
			foreach($spacesIn as $key => $space) { $spacesOut[] = ucfirst($space); }
			$controller = implode('\\', $spacesOut);
		}
		
		try {
			$actionControllerString = 'Application\\Controller\\' . $controller;
			$actionController = new $actionControllerString( $route['action'] );
		} catch ( AutoloadException $e) {
			$actionDefaultControllerString = 'Application\\Controller\\' . ucfirst($this->route->getDefault('controller'));
			$actionController = new $actionDefaultControllerString();
		}
		
		$this->controller = $actionController;
		$actionController->before();

		if (Registry::get('caching')) {
			$cacheKey = MUMVC_CACHE_KEY_PREFFIX . '-controllerContent-' . implode('_', $route);
			$cachedContent = Cache::instance()->fetch($cacheKey, $success);
			if ($success) {			
				Log::add('Returning cached content from ' . $cacheKey, Log::SEVERITY_NOTICE);
				echo $cachedContent;
				return;
			}
		}
		
		$actionMethod = $route['action'] . 'Action';
		if (method_exists($actionController, $actionMethod)) {
			// Mark route as good for caching
			$this->route->persist();
			$this->action = $route['action'];
			$actionController->$actionMethod();
		}
		else {
			$actionController->defaultAction($actionMethod);
		}
		
		$content = $actionController->after();
		
		if (Registry::get('caching')) {
			Cache::instance()->storeIfHits($cacheKey, $content, null, 3);
		}
		echo $content;
	}
}
?>
