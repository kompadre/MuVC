<?php
namespace MuMVC;

require_once MUMVC_ROOT . '/Db/IDbDriver.php';

class Db {
	/**
	 * @var \MuMVC\Db\IDbDriver
	 */
	protected $driver;
	protected $table;
	protected $where;
	public $id;
	
	public function __construct($id=null, $driver='mysql') {
		$driverString = __NAMESPACE__ . '\\Db\\' . ucfirst($driver) . 'Driver';
		try {
			$this->driver = new $driverString(Registry::get('db:params'));
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
		$row = $this->driver->fetchAssoc();
		if (isset($row['id'])) {
			$this->id = $row['id'];
		}
		return $row;
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
	
	public function fetchAll() {
		$sql = 'SELECT * FROM ' . static::$TABLE . ' WHERE ' . ($this->where ? $this->where : '1');
		if ($q = $this->query($sql)) {
			$result = array();
			while ($row = $this->fetchAssoc()) { $result[] = $row; }
			return $result;
		}
		return FALSE;
	}
	
	public function where($snippet, $params) {
		if (is_array($params) ) {
			foreach (array_keys($params) as $key) {
				$snippet = str_replace($key, "'" . $this->driver->escape( $params[$key] ) . "'", $snippet);
			}
		}
		$this->where = $snippet;
	} 
	
	public function delete() {
		if (!$this->id)
			throw new \Exception('Trying to delete a record from ' . self::TABLE . ' without an ID');
		$sql = 'DELETE FROM '. self::$TABLE .' WHERE id = :id LIMIT 1';
		$sql->query($sql, array(':id' => $this->id)); 
	}
	
	public function cacheSave() { }
	public function cacheLoad($data) { }
}
?>
