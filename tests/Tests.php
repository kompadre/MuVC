<?php
namespace MuMVC;
class Tests extends \PHPUnit_Framework_TestCase {
	public function testSingletonInstances() {
		$cache1 = Cache::instance();
		$cache2 = Cache::instance();
		$this->assertSame($cache1, $cache2);
	}
	public function testRegistry() {
		Registry::instance()->set('bla', TRUE);
		$this->assertSame(Registry::instance()->get('bla'), TRUE);
	}
	public function testAutoloading() {
		$testInstance = new TestClass();
	}
	public function testCaching() {
		$data = 'Data';
		if ( Cache::instance()->store('key', 'value', 1024000) ) {
			$this->assertSame( Cache::instance()->fetch('key'), $data );
		}
		$this->fail("APC is not enabled!");
		apc_clear_cache('user');
	}
	public function testTemplate() {
		$tpl = new Template(APP_PATH . '/views/controller/default.tpl');
		$tpl->parse('bla');
		$tpl->parse('bla');
		echo $tpl->render();
		die();
	}
}
