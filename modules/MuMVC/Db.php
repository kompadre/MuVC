<?php
namespace MuMVC;

require_once MUMVC_ROOT . '/Db/IDbDriver.php';

define('MUMVC_DB_CACHE_KEY', MUMVC_CACHE_KEY_PREFFIX . '-Db-');
define('MUMVC_DB_CACHE_DEFAULT_TTL', 300);
define('MUMVC_DB_MIN_HITS_TO_CACHE', 3);

class Db {
	/**
	 * @var \MuMVC\Db\IDbDriver
	 */
	protected $driver;
	protected $table;
	protected $where;
	protected $selecting;
	protected $limit;
	protected $values = null;
	public $id;
	
	public function __construct($id=null, $driver='mysql') {
		$driverString = __NAMESPACE__ . '\\Db\\' . ucfirst($driver) . 'Driver';
		try {
			$this->driver = new $driverString(Registry::get('db_params'));
		} catch (\Exception $e) {
			Log::add( $e->getMessage(), CRITICAL);
			die();
		}
	}
	public function query($query, $params=null, $cache=FALSE) {
		$query = str_replace( array("\n", "\t"), array(" ", ""), $query);
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
		return $this->driver->fetchAll();
	}
	public function selecting($selecting) {
		if (is_array($selecting))
			$selecting = implode(', ', $selecting);
		$this->selecting = $selecting;
		return $this;
	}
	public function where($snippet, $params) {
		if (is_array($params) ) {
			foreach (array_keys($params) as $key) {
				$snippet = str_replace($key, "'" . $this->driver->escape( $params[$key] ) . "'", $snippet);
			}
		}
		$this->where = $snippet;
		return $this;
	}
	public function limit($limit) {
		$this->limit = $limit;
		return $this;
	}
	public function select() {
		$table  = static::TABLE;
		$where  = $this->where ? $this->where : '1';
		$selecting = $this->selecting ? $this->selecting : '*';
		$limit = $this->limit ? $this->limit : 0xFFFF;
		
		$sql = "SELECT $selecting FROM $table WHERE $where LIMIT $limit";
		if (Registry::get('caching_db')) {
			$success = NULL;
			$result = Cache::instance()->fetch( MUMVC_DB_CACHE_KEY . '-results-' . md5($sql), $success);
			if ($success) {
				return $result;
			}
		}
		if ($q = $this->query($sql)) {
			$result = array();
			while ($row = $this->fetchAssoc()) { $result[] = $row; }
		}
		else {
			$result = FALSE;
		}
		if (Registry::get('caching_db')) {
			Cache::instance()->storeIfHits(MUMVC_DB_CACHE_KEY . '-results-' . md5($sql), $result, 300, 3);
		}
		return $result;
	}
	public function delete() {
		if (!$this->id)
			throw new \Exception('Trying to delete a record from ' . self::TABLE . ' without an ID');
		$sql = 'DELETE FROM '. self::$TABLE .' WHERE id = :id LIMIT 1';
		$sql->query($sql, array(':id' => $this->id)); 
	}
}
?>
