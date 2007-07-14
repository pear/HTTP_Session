<?php
/**
 * Unit tests for HTTP/Session.php
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  HTTP
 * @package   HTTP_Session
 * @author    Torsten Roehr <troehr@php.net>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/HTTP_Session
 * @since     File available since Release 0.5.6
 */

// Call HTTP_SessionTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "HTTP_SessionTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

//make cvs testing work
chdir(dirname(__FILE__) . '/../');
require_once 'HTTP/Session.php';

/**
 * Test class for HTTP/Session.php
 *
 * @category  HTTP
 * @package   HTTP_Session
 * @author    Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/HTTP_Session
 * @since     Class available since Release 0.5.6
 */
class HTTP_SessionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor
     *
     * @return object
     */
    public function __construct()
    {
        $_SESSION = array();
    }

    /**
     * Runs the test methods of this class
     *
     * @return void
     */
    public static function main()
    {
        include_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('HTTP_SessionTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Tests useCookies()
     *
     * @return void
     */
    public function testUseCookies()
    {
        HTTP_Session::useCookies(true);
        $this->assertTrue(HTTP_Session::useCookies());

        HTTP_Session::useCookies(false);
        $this->assertFalse(HTTP_Session::useCookies());
    }

    /**
     * Tests useTransSID()
     *
     * Must be run before testStart()
     *
     * @return void
     */
    public function testUseTransSID()
    {
        HTTP_Session::useTransSID(true);
        $this->assertTrue(HTTP_Session::useTransSID());

        HTTP_Session::useTransSID(false);
        $this->assertFalse(HTTP_Session::useTransSID());
    }

    /**
     * Tests setGcMaxLifetime()
     *
     * Must be run before testStart()
     *
     * @return void
     */
    public function testSetGcMaxLifetime()
    {
        HTTP_Session::setGcMaxLifetime(60);
        $this->assertEquals(60, HTTP_Session::setGcMaxLifetime());
    }

    /**
     * Tests setGcProbability()
     *
     * Must be run before testStart()
     *
     * @return void
     */
    public function testSetGcProbability()
    {
        HTTP_Session::setGcProbability(50);
        $this->assertEquals(50, HTTP_Session::setGcProbability());
    }

    /**
     * Tests name()
     *
     * @return void
     */
    public function testName()
    {
        // HTTP_Session::name() returns previous/old session name
        $this->assertNotEquals('mySessionName', HTTP_Session::name('mySessionName'));
    }

    /**
     * Tests id()
     *
     * @return void
     */
    public function testId()
    {
        // HTTP_Session::id() returns previous/old session id
        $this->assertNotEquals('mySessionId', HTTP_Session::id('mySessionId'));
    }

    /**
     * Tests start()
     *
     * @return void
     */
    public function testStart()
    {
        HTTP_Session::start('mySessionName', 'mySessionId');
        $this->assertEquals('mySessionName', HTTP_Session::name());
        $this->assertEquals('mySessionId', HTTP_Session::id());
        $this->assertTrue(isset($_SESSION['__HTTP_Session_Info']));
    }

    /**
     * Tests regenerateId()
     *
     * @return void
     */
    public function testRegenerateId()
    {
        $oldId = HTTP_Session::id();
        HTTP_Session::regenerateId();
        $newId = HTTP_Session::id();
        $this->assertNotEquals($oldId, $newId);
    }

    /**
     * Tests setExpire()
     *
     * @return void
     */
    public function testSetExpire()
    {
        $time = time();

        HTTP_Session::setExpire($time);
        $this->assertEquals($time, $_SESSION['__HTTP_Session_Expire_TS']);

        unset($_SESSION['__HTTP_Session_Expire_TS']);

        HTTP_Session::setExpire($time, $add = true);
        $this->assertEquals($time + $time, $_SESSION['__HTTP_Session_Expire_TS']);
    }

    /**
     * Tests setIdle()
     *
     * @return void
     */
    public function testSetIdle()
    {
        $time = time();

        HTTP_Session::setIdle($time);
        $this->assertEquals(0, $_SESSION['__HTTP_Session_Idle']);

        HTTP_Session::setIdle($time, $add = true);
        $this->assertEquals($time, $_SESSION['__HTTP_Session_Idle']);
    }

    /**
     * Tests sessionValidThru()
     *
     * @return void
     */
    public function testSessionValidThru()
    {
        HTTP_Session::updateIdle();

        $this->assertEquals($_SESSION['__HTTP_Session_Idle_TS'] +
                            $_SESSION['__HTTP_Session_Idle'],
                            HTTP_Session::sessionValidThru());

        unset($_SESSION['__HTTP_Session_Idle_TS']);
        unset($_SESSION['__HTTP_Session_Idle']);
        $this->assertEquals(0, HTTP_Session::sessionValidThru());
    }

    /**
     * Tests isExpired()
     *
     * @return void
     */
    public function testIsExpired()
    {
        unset($_SESSION['__HTTP_Session_Expire_TS']);
        HTTP_Session::setExpire(time() + 1);
        $this->assertFalse(HTTP_Session::isExpired());

        unset($_SESSION['__HTTP_Session_Expire_TS']);
        HTTP_Session::setExpire(time() - 1);
        $this->assertTrue(HTTP_Session::isExpired());
    }

    /**
     * Tests isIdle()
     *
     * @return void
     */
    public function testIsIdle()
    {
        HTTP_Session::updateIdle();

        HTTP_Session::setIdle(time() + 1);
        $this->assertFalse(HTTP_Session::isIdle());

        HTTP_Session::setIdle(time() - 1);
        $this->assertTrue(HTTP_Session::isIdle());
    }

    /**
     * Tests set()
     *
     * @return void
     */
    public function testSet()
    {
        $this->assertNull(HTTP_Session::set('var', 1));
        $this->assertEquals(1, $_SESSION['var']);

        HTTP_Session::set('var', null);
        $this->assertFalse(isset($_SESSION['var']));
    }

    /**
     * Tests setRef()
     *
     * @return void
     */
    public function testSetRef()
    {
        $value = new stdClass();
        HTTP_Session::setRef('var', $value);
        $value->attr = 1;
        $this->assertEquals($value, $_SESSION['var']);
    }

    /**
     * Tests get()
     *
     * @return void
     */
    public function testGet()
    {
        HTTP_Session::set('var', null);
        $this->assertNull(HTTP_Session::get('var'));

        HTTP_Session::set('var', 1);
        $this->assertEquals(1, HTTP_Session::get('var'));

        // test default
        HTTP_Session::set('var', null);
        $this->assertEquals('default', HTTP_Session::get('var', 'default'));
    }

    /**
     * Tests getRef()
     *
     * @return void
     */
    public function testGetRef()
    {
        HTTP_Session::set('var', null);
        $this->assertNull(HTTP_Session::getRef('var'));

        $value = new stdClass();
        HTTP_Session::setRef('var', $value);
        $var = HTTP_Session::getRef('var');

        $_SESSION['var']->attr = 1;
        $this->assertEquals($var, HTTP_Session::getRef('var'));
    }

    /**
     * Tests localName()
     *
     * @return void
     */
    public function testLocalName()
    {
        HTTP_Session::localName('localName');
        $this->assertEquals('localName', HTTP_Session::localName());
    }

    /**
     * Tests setLocal()
     *
     * @return void
     */
    public function testSetLocal()
    {
        $this->assertNull(HTTP_Session::setLocal('var', 1));
        $this->assertEquals(1, $_SESSION[md5(HTTP_Session::localName())]['var']);

        HTTP_Session::setLocal('var', null);
        $this->assertFalse(isset($_SESSION[md5(HTTP_Session::localName())]['var']));
    }

    /**
     * Tests getLocal()
     *
     * @return void
     */
    public function testGetLocal()
    {
        HTTP_Session::setLocal('var', 1);
        $this->assertEquals(1, HTTP_Session::getLocal('var'));

        HTTP_Session::setLocal('var', null);
        $this->assertEquals('default', HTTP_Session::getLocal('var', 'default'));
    }
}

// Call HTTP_SessionTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "HTTP_SessionTest::main") {
    ob_start();
    HTTP_SessionTest::main();
    ob_flush();
}
?>