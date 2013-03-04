<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace Application\Model;

use MuMVC\Db;

class Album extends Db { 
	const TABLE = 'album';
	public function fetchAll() {
		$sql = "SELECT * FROM {self::TABLE} WHERE 1";
		$this->query($sql);
		$result = array();
		while ($result[] = $this->fetchRow());
		return $result;
	}
}