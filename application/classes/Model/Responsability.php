<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Model;

use MuMVC\Db;

class Responsability extends Db { 
	static protected $TABLE = 'responsability';
	public function fetchResponsabilitiesByContribId($contribId) {
		$sql = "SELECT description 
				FROM responsability 
					LEFT JOIN contributor_responsability ON (responsability_id = responsability.id)
				WHERE contributor_id = :contribId";
		
		$this->query($sql, array(':contribId' => $contribId));
		return $this->fetchAll();
	}
}