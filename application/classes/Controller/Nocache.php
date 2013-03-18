<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Controller;
use \MuMVC\ActionController;
use \Application\Model\Album;
use \MuMVC\Registry;
use \MuMVC\Template;
use \MuMVC\Cache;

class Nocache extends ActionController {
	public function indexAction() {
		$album = new Album();
		$album->query("SELECT * FROM album WHERE 1");
		$template = $this->template;
		while ($row = $album->fetchAssoc()) {
			$template->asigna('artist', $row['artist']);
			$template->asigna('title',  $row['title']);
			$template->asigna('color', sprintf("%06x", mt_rand(0,0xFFFFFF)));
			$template->parse('row');
		}		
		$template->asigna('title', 'My first page in MuMVC');
	}
	public function robotAction() {
		$this->template->asigna(array(
			'title' => 'ADSL',
			'artist' => 'I WANT IT NOW'
		));
		$this->template->parse('row');
	}
	public function clearcacheAction() {
		Cache::instance()->clear();
	}
}