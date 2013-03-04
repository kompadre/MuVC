<?php
/* This file is part of the MuMVC Project (http://alex.413x31.com/Projects/MuMVC)
 * Copyright (c) 2013 MuMVC Team (http://alex.413x31.com/Projects/MuMVC/Team)
 * Full copyright and license information can be found in the file license.txt or 
   at http://alex.413x31.com/Projects/MuMVC/licence.txt */
namespace MuMVC;

define('MUMVC_ROOT', realpath(__DIR__));
define('MUMVC_SITE_PREFIX', '/index.php');
define('APP_ROOT', realpath( __DIR__ . '/../../application' ));
define('APP_PATH', realpath( __DIR__ . '/../../application' ));
define('APP_DEFAULT_TPL', realpath( APP_PATH . '/views/default/default.tpl' ));

require_once(__DIR__ . '/Cache.php');
require_once(__DIR__ . '/ICacheable.php');
require_once(__DIR__ . '/Db.php');
require_once(__DIR__ . '/View.php');
require_once(__DIR__ . '/Controller.php');
require_once(__DIR__ . '/Registry.php');
require_once(__DIR__ . '/ActionController.php');
require_once(__DIR__ . '/Template.php');
require_once(__DIR__ . '/autoload.php');
require_once(APP_ROOT. '/autoload.php');
require_once(APP_ROOT. '/config.php');

define('MUMVC_VERSION', .1);
define('MUMVC_CACHE_KEY_PREFFIX', 'TinyMVC-b0x1-');

/**
 * Provides reusable singleton
 * 
 * @author Alexey Serikov <kompadre@gmail.com>
 */
abstract class Root 
{
	private static $instances = array();
	
	public static function instance($class, $argument = null) {
		if (!isset(self::$instances[$class])) {
			return self::$instances[$class] = new $class($argument);
		}
		return self::$instances[$class];
	}
	
	final public static function getInstances() {
		return self::$instances;
	}
	final public static function setInstances($instances) {
		self::$instances = $instances;
	}
}
?>