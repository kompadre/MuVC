<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuMVC\Db;

use MuMVC\Root;
use Exception;
use mysql_connect;
use mysql_query;
use mysql_fetch_assoc;
use mysql_free_result;

class MysqlDriver implements IDbDriver {
	private static $connection;
	private $qh = null;
	private $error = '';
	private $sql = '';
	
	public function __construct($params) {
		if (!self::$connection) {
			self::$connection = mysql_connect(
					$params['server'], $params['username'], $params['password']
			);
			if (!self::$connection) {
				throw new \Exception('Cannot connect to DB with username <b>' . $params['username'] . '</b>', DRIVER_ERROR);
			}
			if (! mysql_select_db($params['dbname'], self::$connection) ) {
				throw new \Exception('Cannot select DB <b>' . $params['dbname'] . '</b>', DRIVER_ERROR);
			}
		}
	}
	public function query($query) {
		$this->sql = $query;
		$qh = mysql_query($query, self::$connection);
		$error = mysql_error();
		if (!empty ($error)) {
			$this->error = $error;
			throw new \Exception('There was an error in last query: <br><pre>'. $error .'</pre>', DRIVER_ERROR);
		}
		$numRows = mysql_num_rows($qh);
		if (0 === $numRows) {
			return FALSE;
		}
		else if (1 === $numRows) {
			$row = mysql_fetch_assoc($qh);
			mysql_free_result($qh);
			return $row; 
		}
		else {
			$this->qh = $qh;
			return $numRows;
		}	
	}
	public function fetchAssoc() {
		$qh = $this->qh;
		if ($qh == null) {
			var_dump(debug_backtrace());
			throw new \Exception("Trying to fetch from a null handler." . $this->sql, DRIVER_ERROR);
		}
		
		$row = mysql_fetch_assoc($qh);
		if (!$row) {
			mysql_free_result($qh);
		}
		return $row;
	}
	public function escape($string) {
		return mysql_real_escape_string($string, self::$connection);
	}
	public function error() {
		return ($this->error . mysql_error());
	}
	public function freeResult() {
		if (is_resource($this->qh)) {
			mysql_free_result($this->qh);
			$this->qh = null;
		}
		return FALSE;
 	}
 	public function getHandler() {
 		return $this->qh;
 	}
}
