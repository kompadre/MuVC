<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace Application\Model;

use MuMVC\Db;

class Hobby extends Db { 
	const TABLE = 'hobby';
	public function fetchAll() {
		$sql = 'SELECT * FROM '. self::TABLE. ' WHERE 1';
		$this->query($sql);
		$result = array();
		while ($result[] = $this->fetchAssoc());
		return $result;
	}
	public function fetch($id) {
		$sql = 'SELECT * FROM '. self::TABLE .' WHERE id = :id LIMIT 1';
		return $this->query($sql, array(':id' => $id));
	}
}