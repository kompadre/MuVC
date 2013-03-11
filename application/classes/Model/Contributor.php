<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Model;

use MuMVC\Db;

class Contributor extends Db { 
	static protected $TABLE = 'contributor';
	public function addFunction($functionId) {
		$sql = 'INSERT INTO contributor_responsability(contributor_id, function_id) VALUES(:id, :f_id)';
		$this->query($sql, array(':id' => $this->id, ':f_id' => $functionId));
	}
	public function delete($id) {
		$sql = "DELETE FROM contributor_responsability WHERE contributor_id = :id";
		$this->query($sql, array(':id' => $this->id));
		parent::delete();
	}
}