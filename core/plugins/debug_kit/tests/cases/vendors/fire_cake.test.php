<?php
/* SVN FILE: $Id: fire_cake.test.php 235 2009-05-29 12:32:38Z rajesh_04ag02 $ */
/**
 * CakeFirePHP test case
 *
 * 
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright       Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link            http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package         debug_kit
 * @subpackage      cake.debug_kit.tests
 * @since           CakePHP v 1.2.0.4487
 * @version         
 * @modifiedby      
 * @lastmodified    
 * @license         http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Vendor', 'DebugKit.FireCake');

require_once APP . 'plugins' . DS . 'debug_kit' . DS . 'tests' . DS . 'cases' . DS . 'test_objects.php';
/**
 * Test Case For FireCake
 *
 * @package debug_kit.tests
 */
class FireCakeTestCase extends CakeTestCase {
/**
 * setup test
 *
 * Fill FireCake with TestFireCake instance.
 *
 * @access public
 * @return void
 */
	function setUp() {
		$this->firecake =& FireCake::getInstance('TestFireCake');
	}	
/**
 * test getInstance cheat.
 *
 * If this fails the rest of the test is going to fail too.
 *
 * @return void
 **/
	function testGetInstanceOverride() {
		$instance =& FireCake::getInstance();
		$instance2 =& FireCake::getInstance();
		$this->assertReference($instance, $instance2);
		$this->assertIsA($instance, 'FireCake');
		$this->assertIsA($instance, 'TestFireCake', 'Stored instance is not a copy of TestFireCake, test case is broken.');
	}	
/**
 * testsetoption
 *
 * @return void
 **/
	function testSetOptions() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		$this->assertEqual($this->firecake->options['includeLineNumbers'], false);
	}
/**
 * test Log()
 *
 * @access public
 * @return void
 */	
	function testLog() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::log('Testing');
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-Protocol-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Plugin-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Structure-1']));
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 1);
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '26|[{"Type":"LOG"},"Testing"]|');
		
		FireCake::log('Testing', 'log-info');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-2'], '45|[{"Type":"LOG","Label":"log-info"},"Testing"]|');
	}
/**
 * test info()
 *
 * @access public
 * @return void
 */	
	function testInfo() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::info('I have information');
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-Protocol-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Plugin-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Structure-1']));
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 1);
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '38|[{"Type":"INFO"},"I have information"]|');

		FireCake::info('I have information', 'info-label');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-2'], '59|[{"Type":"INFO","Label":"info-label"},"I have information"]|');
	}
/**
 * test info()
 *
 * @access public
 * @return void
 */	
	function testWarn() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::warn('A Warning');
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-Protocol-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Plugin-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Structure-1']));
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 1);
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '29|[{"Type":"WARN"},"A Warning"]|');

		FireCake::warn('A Warning', 'Bzzz');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-2'], '44|[{"Type":"WARN","Label":"Bzzz"},"A Warning"]|');
	}
/**
 * test error()
 *
 * @access public
 * @return void
 **/
	function testError() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::error('An error');
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-Protocol-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Plugin-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Structure-1']));
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 1);
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '29|[{"Type":"ERROR"},"An error"]|');
		
		FireCake::error('An error', 'wonky');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-2'], '45|[{"Type":"ERROR","Label":"wonky"},"An error"]|');
	}
/**
 * test dump()
 *
 * @return void
 **/
	function testDump() {
		FireCake::dump('mydump', array('one' => 1, 'two' => 2));
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-2-1-1'], '28|{"mydump":{"one":1,"two":2}}|');
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Structure-2']));
	}
/**
 * test table() generation
 *
 * @return void
 **/
	function testTable() {
		$table[] = array('Col 1 Heading','Col 2 Heading');
		$table[] = array('Row 1 Col 1','Row 1 Col 2');
		$table[] = array('Row 2 Col 1','Row 2 Col 2');
		$table[] = array('Row 3 Col 1','Row 3 Col 2');
		FireCake::table('myTrace', $table);
		$expected = '162|[{"Type":"TABLE","Label":"myTrace"},[["Col 1 Heading","Col 2 Heading"],["Row 1 Col 1","Row 1 Col 2"],["Row 2 Col 1","Row 2 Col 2"],["Row 3 Col 1","Row 3 Col 2"]]]|';
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], $expected);
	}
/**
 * testStringEncoding
 *
 * @return void
 **/
	function testStringEncode() {
		$result = $this->firecake->stringEncode(array(1,2,3));
		$this->assertEqual($result, array(1,2,3));

		$this->firecake->setOptions(array('maxArrayDepth' => 3));
		$deep = array(1 => array(2 => array(3)));
		$result = $this->firecake->stringEncode($deep);
		$this->assertEqual($result, array(1 => array(2 => '** Max Array Depth (3) **')));

		$obj =& FireCake::getInstance();
		$result = $this->firecake->stringEncode($obj);
		$this->assertTrue(is_array($result));
		$this->assertEqual($result['_defaultOptions']['useNativeJsonEncode'], true);
		$this->assertEqual($result['_log'], null);
		$this->assertEqual($result['_encodedObjects'][0], '** Recursion (TestFireCake) **');
	}
/**
 * test trace()
 *
 * @return void
 **/
	function testTrace() {
		FireCake::trace('myTrace');
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-Protocol-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Plugin-1']));
		$this->assertTrue(isset($this->firecake->sentHeaders['X-Wf-1-Structure-1']));
		$dump = $this->firecake->sentHeaders['X-Wf-1-1-1-1'];
		$this->assertPattern('/"Message":"myTrace"/', $dump);
		$this->assertPattern('/"Trace":\[/', $dump);
	}
/**
 * test enabling and disabling of FireCake output
 *
 * @return void
 **/
	function testEnableDisable() {
		FireCake::disable();
		FireCake::trace('myTrace');
		$this->assertTrue(empty($this->firecake->sentHeaders));
		
		FireCake::enable();
		FireCake::trace('myTrace');
		$this->assertFalse(empty($this->firecake->sentHeaders));
	}
/**
 * test correct line continuation markers on multi line headers.
 *
 * @access public
 * @return void
 */	
	function testMultiLineOutput() {
		FireCake::trace('myTrace');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 3);
		$header = $this->firecake->sentHeaders['X-Wf-1-1-1-1'];
		$this->assertEqual(substr($header, -2), '|\\');
		
		$header = $this->firecake->sentHeaders['X-Wf-1-1-1-2'];
		$this->assertEqual(substr($header, -2), '|\\');
		
		$header = $this->firecake->sentHeaders['X-Wf-1-1-1-3'];
		$this->assertEqual(substr($header, -1), '|');
	}
	
/**
 * test inclusion of line numbers
 *
 * @return void
 **/
	function testIncludeLineNumbers() {
		FireCake::setOptions(array('includeLineNumbers' => true));
		FireCake::info('Testing');
		$result = $this->firecake->sentHeaders['X-Wf-1-1-1-1'];
		$this->assertPattern('/"File"\:"APP.*fire_cake.test.php/', $result);
		$this->assertPattern('/"Line"\:\d+/', $result);
	}
/**
 * test Group messages
 *
 * @return void
 **/
	function testGroup() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::group('test');
		FireCake::info('my info');
		FireCake::groupEnd();
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '44|[{"Type":"GROUP_START","Label":"test"},null]|');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-3'], '27|[{"Type":"GROUP_END"},null]|');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 3);
	}
/**
 * test fb() parameter parsing
 *
 * @return void
 **/
	function testFbParameterParsing() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::fb('Test');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '23|[{"Type":"LOG"},"Test"]|');

		FireCake::fb('Test', 'warn');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-2'], '24|[{"Type":"WARN"},"Test"]|');

		FireCake::fb('Test', 'Custom label', 'warn');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-3'], '47|[{"Type":"WARN","Label":"Custom label"},"Test"]|');

		$this->expectError();
		$this->assertFalse(FireCake::fb('Test', 'Custom label', 'warn', 'more parameters'));

		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-Index'], 3);
	}
/**
 * Test defaulting to log if incorrect message type is used
 *
 * @return void
 **/
	function testIncorrectMessageType() {
		FireCake::setOptions(array('includeLineNumbers' => false));
		FireCake::fb('Hello World', 'foobared');
		$this->assertEqual($this->firecake->sentHeaders['X-Wf-1-1-1-1'], '30|[{"Type":"LOG"},"Hello World"]|');
	}
/**
 * testClientExtensionDetection.
 *
 * @return void
 **/
	function testDetectClientExtension() {
		$back = env('HTTP_USER_AGENT');
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4 FirePHP/0.2.1';
		$this->assertTrue(FireCake::detectClientExtension());
		
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4 FirePHP/0.0.4';
		$this->assertFalse(FireCake::detectClientExtension());
		
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4';
		$this->assertFalse(FireCake::detectClientExtension());
		$_SERVER['HTTP_USER_AGENT'] = $back;
	}	
/**
 * test of Non Native JSON encoding.
 *
 * @return void
 **/
	function testNonNativeEncoding() {
		FireCake::setOptions(array('useNativeJsonEncode' => false));
		$json = FireCake::jsonEncode(array('one' => 1, 'two' => 2));
		$this->assertEqual($json, '{"one":1,"two":2}');
		
		$json = FireCake::jsonEncode(array(1,2,3));
		$this->assertEqual($json, '[1,2,3]');
		
		$json = FireCake::jsonEncode(FireCake::getInstance());
		$this->assertPattern('/"options"\:\{"maxObjectDepth"\:\d*,/', $json);
	}
/**
 * reset the FireCake counters and headers.
 *
 * @access public
 * @return void
 */
	function tearDown() {
		TestFireCake::reset();
	}
}
?>