<?php
namespace MuMVC;

require_once MUMVC_ROOT . '/Db/IDbDriver.php';

class Db {
	/**
	 * @var \MuMVC\Db\IDbDriver
	 */
	protected $driver;
	public function __construct($driver='mysql') {
		$driverString = __NAMESPACE__ . '\\Db\\' . ucfirst($driver) . 'Driver';
		try {
			$this->driver = $driverString::instance(Registry::get('db:params'));
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}
	public function query($query, $params=null, $cache=FALSE) {
		if (is_array($params) ) {
			foreach (array_keys($params) as $key) {
				$query = str_replace($key, "'" . $this->driver->escape( $params[$key] ) . "'", $query);
			}
		}
		return $this->driver->query($query);
	}
	public function fetchAssoc() {
		return $this->driver->fetchAssoc();
	}
	public function error() {
		return $this->driver->error();
	}
	public function freeResult() {
		return $this->driver->freeResult();
	}
	public function getDriver() {
		return $this->driver;
	}
	public function cacheSave() { }
	public function cacheLoad($data) { }
}
?>
