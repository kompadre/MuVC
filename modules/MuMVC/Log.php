<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */

namespace MuMVC;

class Log extends Root { 
	/**
	 * @var resource
	 */
	const SEVERITY_NOTICE = 0;
	const SEVERITY_WARNING = 1;
	const SEVERITY_ERROR = 2;
	const SEVERITY_CRITICAL = 3;
	
	const DEFAULT_ADMIN_EMAIL = 'root@localhost';
		
	private $fd;
	private $adminEmail = DEFAULT_ADMIN_EMAIL;
	
	public function __construct() {
		if (!$logFile = Registry::get('log_file')) {
			// $logFile = $_ENV['tmp'] 	
		}
		$this->fd = fopen( Registry::get('log_file'), 'w');
		$this->adminEmail = Registry::get('admin_email');
	}
	public static function instance() {
		return parent::instance(__CLASS__);
	}
	public static function add($message, $severity) {
		$log = Log::instance();
		switch ($severity) {
			case $this->SEVERITY_CRITICAL:				
				$severity = 'Critical: ';
				break;
			case $this->SEVERITY_ERROR:
				$severity = 'Error: ';
				break;	
			case $this->SEVERITY_WARNING: 
				$severity = 'Warning: ';
				break;
			case $this->SEVERITY_NOTICE:
			default: 
				$severity = 'Notice: ';
				break;
		}
		$message = '[' . date('d/h/Y H:i') . '] ' . $severity . ' ' . $message . "\n";
	}
}