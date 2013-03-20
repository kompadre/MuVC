<?php
namespace MuMVC;

/**
 * Registry::instance()->set('caching', false);
 * Registry::instance()->caching = false;
 * Registry::set('caching', false);
 * $r = Registry::instance();
 * $r->caching = false;
 */
class Registry extends Root implements ICacheable {
	private static $registry = array();
	/**
	 * @return MuMVC\Registry
	 */
	public static function instance() {
		return parent::instance(__CLASS__);
	}
	/**
	 * Sets something globally available. If $overwrite is FALSE, it won't
	 * overwrite existing values, only add new ones.
	 *
	 * @param string global
	 * @param mixed value
	 * @param boolean overwrite
	 * @return mixed
	 */
	public static function set($name, $value, $overwrite = TRUE) {
		if (FALSE === $overwrite && isset(self::$registry[$name]))
			return;
		self::$registry[$name] = $value;
	}
	/**
	 * Gets a value from the registry.
	 * 
	 * @param string $key
	 * @param string $subkey
	 */
	public static function get($key, $subkey=null) {
		if ($subkey != null && 
				isset(self::$registry[$key]) && 
				isset(self::$registry[$key][$subkey])) {
			return self::$registry[$key][$subkey];
		}
		else if ($subkey != null )
			return false;
		else
			return isset(self::$registry[$key]) ? self::$registry[$key] : null;
	}
	public function getRegistry() {
		return self::$registry;
	}
	public function cacheSave() {
		return self::$registry;
	}
	public function cacheLoad($data) {
		self::$registry = $data;
	}
	
	public function __set($key, $value) {
		self::$registry[$key] = $value;
	}
	public function __get($key) {
		return self::$registry[$key];
	}
}
?>