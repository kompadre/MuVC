<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Controller\Mobile;

use Application\Controller\Mobile;
use Application\Model\Contributor;
use Application\Model\Responsability;

class Mumvc extends Mobile { 
	public function before() {
		parent::before();
		$this->addCrumb('MuMVC', '/mobile/MuMVC/');
	}
	public function licenceAction() {
		die ( '<pre>' . file_get_contents(APP_PATH . '/../licence.txt') . '</pre>' );
	}

	public function teamAction() {
		$c = new Contributor();
		$t = $this->template;
		$c->query("SELECT * FROM contributor WHERE 1");
		while($contributor = $c->fetchAssoc()) {
			$t->asigna($contributor);
			$r = new Responsability();
			$r->where('id IN (
					SELECT responsability_id
					FROM contributor_responsability
					WHERE contributor_id = :id)', array(':id' => $c->id));
			$resps = $r->fetchAll();
			if (is_array($resps)) {
				foreach($resps as $responsability) {
					$t->asigna('responsability', $responsability['description']);
					$t->parse('responsability');
				}
				$t->parse('responsabilities');
			}
			$t->parse('contributor');
		}
	}
	
	public function indexAction() {  }
	public function error($code, $message) {
		$this->controller = 'Mobile';
		$this->action = 'error' . $code;
		parent::error($code, $message);
	}
}
