<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
 
namespace Application\Controller\Mobile;

use MuMVC\Template;
use Application\Controller\Mumvc as ParentMumvc;


class Mumvc extends ParentMumvc { 
	public function before() {
		$this->templatePath = 'mumvc';
		$this->layout = 'layout_mobile.tpl';
		parent::before();
		$this->addCrumb('MuMVC', '/mobile/MuMVC/');
	}
}
