<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Controller;
use \MuMVC\ActionController;
use \Application\Model\Album;
use \MuMVC\Registry;
use \MuMVC\Db;

class Controller extends ActionController {
	public function before() {
		parent::before();
	}
	public function indexAction() {
		try {
 			$template = $this->template;
 			$album = new Album();
 			$album->query("SELECT * FROM album WHERE 1");
 			while ($row = $album->fetchAssoc()) {
 				$template->asigna('artist', $row['artist']);
 				$template->asigna('title',  $row['title']);
 				$template->asigna('color', sprintf("%06x", mt_rand(0,0xFFFFFF)));
	 			$template->parse('row');
 			}
			$template->asigna('title', 'My first page in MuMVC');
		} catch (\Exception $e) {
				echo "There was an error while executing some query.";
		}
	}
}
