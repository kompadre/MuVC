<?php
namespace MuMVC;
class View extends Root {
	protected $template, $templateFile;
	
	public static function instance($action='index') {
		return parent::instance(__CLASS__, $action);
	}
	protected function __construct($controller='index', $action='index') {
		$this->templateFile = APP_ROOT . '/views/' . $controller . '/' . $action . '.tpl';
	}
	public function render() {
		return $this->template->render();
	}
}
?>