<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace MuMVC\Db;

use Exception;

class MysqlDriver implements IDbDriver {
	private $connection;
	private $queryStack = array();
	
	public function __construct($params) {
		$this->connection = mysql_connect(
				$params['server'], $params['username'], $params['password']
		);
		if (!$this->connection) {
			throw new \Exception('Cannot connect to DB with <b>' . $params['username'] . '</b>');
		}
		if (! mysql_select_db($params['dbname'], $this->connection) ) {
			throw new \Exception('Cannot select DB <b>' . $params['dbname'] . '</b>');
		}
	}
	public function query($query) {
		array_push($this->queryStack, 
			mysql_query($query, $this->connection));
	}
	public function fetchAssoc() {
		$qh = array_pop($this->queryStack);
		if ( $row = mysql_fetch_assoc($qh) ) {
			array_push($this->queryStack, $qh); //unpop
		}
		return $row;
	}
}