<?php
namespace MuMVC;
use Application\Model\Hobby;

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
		$this->assertTrue(is_object($testInstance));
	}
	public function testCaching() {
		$data = 'Data';
		if ( Cache::instance()->store('key', 'value', null) ) {
			$this->assertSame( Cache::instance()->fetch('key'), $data );
		}
		else {
			$this->markTestSkipped("APC is not enabled!");
		}
	}
	public function testTemplate() {
		$tpl = new Template(APP_PATH . '/views/controller/default.tpl');
		$tpl->parse('bla');
		$tpl->parse('bla');
		$this->assertTrue(is_string($tpl->render()));
	}
	public function testTwoModels() {
		$modelAlbum = new \Application\Model\Album();
		$modelHobby = new \Application\Model\Hobby();
		$this->assertTrue( is_array($modelAlbum->fetchAll()) );
		$this->assertTrue( is_array($modelHobby->fetchAll()) );
		$row = $modelHobby->fetch(1);
		$this->assertTrue( is_array($row));
		$this->assertSame($row['name'], 'Bassoon');
	}
	public function testToSqlInjectStuff() {
		$model = new \Application\Model\Hobby();
		$result = $model->query('SELECT id FROM ' . Hobby::TABLE . ' WHERE id = :id LIMIT 1', array(
			':id' => "1'" 
		));
		$this->assertTrue( ! $model->error());
		$this->assertSame( $result, array('id' => '1'));
		$result = $model->query('SELECT * FROM ' . Hobby::TABLE . ' WHERE 1');
		$this->assertTrue( is_numeric($result));
		$model->freeResult();
	}
	public function testFreeNullsPointer() {
		$model = new \Application\Model\Album();
		$model->query('SELECT * FROM ' . $model::TABLE . ' LIMIT 10');
		$model->freeResult();
		$qh = $model->getDriver()->getHandler();
		$this->assertSame($qh, null); 
	}
	public function testRouting() {
		$router = new Route();
		$router->parse('/c/a/id');
		$this->assertSame($router->getCurrent(), array('controller'=>'c', 'action'=>'a'));
	}
	public function testRouteCompile() {
		$router = new Route();
		$compiledRoute = $router->compileRoute('/:controller(/:action)(/:id)');
		$patterns = array('default' => array( $compiledRoute, array('controller' => 'controller', 'action' => 'index') ) );
		$router->setPatterns($patterns);
		var_dump( $router->parse('/c/a/id') );
	}
}
