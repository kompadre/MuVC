<?php
/**
 * Implements a facade for caching subsystem.
 * 
 * @author Alexey Serikov
 */
namespace MuMVC;

require_once MUMVC_ROOT . '/Cache/ICacheDriver.php';
require_once MUMVC_ROOT . '/ICacheable.php';

define('MUMVC_CACHE_HITS_TO_CACHE', 3);

class Cache extends Root 
{
	/**
	 * @var ICacheDriver
	 */
	private $driver = null;
	/**
	 * @param string $driver
	 * @return MuMVC\Cache
	 */
	public static function instance($driver='apc') {
		return parent::instance(__CLASS__, $driver);
	}
	
	protected function __construct($driver) {
		$driver = __NAMESPACE__ . '\\Cache\\' . ucfirst($driver) . 'Driver';
		try {
			$this->driver = new $driver();
		}	
		catch (Exception $e) {
			echo "Couldn't load the driver.";	
		}
	}

	public function store($key, $value, $ttl=null) {
		return $this->driver->store($key, $value, $ttl);
	}
	public function storeIfHits($key, $value, $ttl=null, $hitsToStore= MUMVC_CACHE_HITS_TO_CACHE) {
		if ($hitsToStore <= $this->fetch($key . '-hits')) {
			$this->store($key, $value, $ttl);
			return TRUE;
		}
		$this->inc($key . '-hits');
		return FALSE;
	}
	public function fetch($key, &$success=null) {
		return $this->driver->fetch($key, $success);
	}
	public function saveInstances() {
		$instances = Root::getInstances();
		$this->store('Instances', serialize($instances), 2048);
	}
	public function loadInstances() {
		$instances = unserialize ( $this->fetch('Instances') );
		Root::setInstances( $instances );
		Controller::instance()->helloWorld();
	}
	public function saveContent($route, $content, $ttl=null) {
		echo "Saving " . implode('_', $route) . "<br>";
		return $this->driver->store(implode('_', $route), $content, $ttl);
	}
	public function fetchContent($route) {
		echo "Fetching " . implode('_', $route) . "<br>";
		return $this->driver->fetch(implode('_', $route));
	}
	public function clear() {
		return $this->driver->clear();
	}
	public function inc($key) {
		return $this->driver->inc($key);
	}
}
