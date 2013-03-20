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
	public function normalizeQuery($query, $params=array(), &$cacheKey=NULL) {
		$query = str_replace( array("\n", "\t"), array(" ", ""), $query);
		if ($cacheKey !== NULL) {
			if (is_array($params)) {
				$params = implode('_', $params);
			}
			$cacheKey = MUMVC_DB_CACHE_KEY . '-result-' . md5( $query . $params);
		}
		return $query;
	}
	public function query($query, $params=null, $hitsToCache=3) {
		$this->values = NULL;
		if (0 > $hitsToCache) {
			$query = $this->normalizeQuery($query, $params);
			if (is_array($params) ) {
				foreach (array_keys($params) as $key) {
					$query = str_replace($key, "'" . $this->driver->escape( $params[$key] ) . "'", $query);
				}
			}
			return $this->driver->query($query);
		}
		
		// Caching a query can be a little bit tricky.
		$cacheKey = '';
		$query = $this->normalizeQuery($query, $params, $cacheKey);
		$values = Cache::instance()->fetch( $cacheKey, $success);
		if ($success) { 
			Log::add('Query '. $cacheKey .' restored from cache.', Log::SEVERITY_NOTICE);
			$this->values = $values;
			switch($sizeof = sizeof($values)) {
				case 0: 
					return FALSE;
				case 1:
					return $values[0];
				default:
					return $sizeof;
			}
		}
		
		if (is_array($params) ) {
			foreach (array_keys($params) as $key) {
				$query = str_replace($key, "'" . $this->driver->escape( $params[$key] ) . "'", $query);
			}
		}
		$result = $this->driver->query($query);
		
		if (is_integer($result)) { 
			$this->values = $this->fetchAll();
		}
		else {
			$this->values = $result;
		}
		Cache::instance()->storeIfHits( $cacheKey, $this->values, null, $hitsToCache);
		return $result;
	}
	public function fetchAssoc() {
		if ($this->values !== NULL) {
			$row = array_shift($this->values);
		}
		else {
			$row = $this->driver->fetchAssoc();
		}
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
		if ($this->values)
			return $this->values;
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
		if ($q = $this->query($sql)) {
			$result = $this->fetchAll();
		}
		else {
			$result = FALSE;
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
