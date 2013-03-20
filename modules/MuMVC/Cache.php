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
	/**
	 * Makes a cache storage that will use $driver. By default it's APC driver.
	 * @param string $driver
	 */
	protected function __construct($driver) {
		$driver = __NAMESPACE__ . '\\Cache\\' . ucfirst($driver) . 'Driver';
		try {
			$this->driver = new $driver();
		} catch (\Exception $e) {
			Log::add("Cache problem: {$e->getMessage()}, falling back to Cache\\NullDriver", Log::SEVERITY_NOTICE);
			// fallback to NullDriver
			Registry::set('caching', FALSE);
			$this->driver = new Cache\NullDriver();
		}
	}
	/**
	 * Stores something into cache and locks it with your $key. Lasts $ttl.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 */
	public function store($key, $value, $ttl=null) {
		return $this->driver->store($key, $value, $ttl);
	}
	/**
	 * Stores into cache something if it is "popular" enough. Has to be something big:
	 * there's an int being stored only to see if it's worth storing or not.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @param int $hitsToStore
	 * @return boolean
	 */
	public function storeIfHits($key, $value, $ttl=null, $hitsToStore= MUMVC_CACHE_HITS_TO_CACHE) {
		if ($hitsToStore <= $this->fetch($key . '-hits')) {
			$this->store($key, $value, $ttl);
			return TRUE;
		}
		$this->inc($key . '-hits');
		return FALSE;
	}
	/**
	 * Fetches something from cache using $key and returns it. Sets $success to TRUE if succeeds. 
	 * @param unknown $key
	 * @param string $success
	 */
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
		return $this->driver->store(implode('_', $route), $content, $ttl);
	}
	public function fetchContent($route) {
		return $this->driver->fetch(implode('_', $route));
	}
	public function clear() {
		return $this->driver->clear();
	}
	public function inc($key) {
		return $this->driver->inc($key);
	}
}
