<?php
namespace MuMVC;

require_once MUMVC_ROOT . '/Db/IDbDriver.php';

class Db implements ICacheable {
	protected $driver;
	public function __construct($driver='mysql') {
		$driverString = __NAMESPACE__ . '\\Db\\' . ucfirst($driver) . 'Driver';
		$this->driver = new $driverString(Registry::get('db:params'));
	}
	public static function instance($driver='mysql') {
		parent::instance(__CLASS__, $driver);
	}
	public function query($query) {
		return $this->driver->query($query);
	}
	public function fetchAssoc() {
		return $this->driver->fetchAssoc();
	}
	public function cacheSave() { }
	public function cacheLoad($data) { }
}
?>