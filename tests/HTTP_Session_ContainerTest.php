<?php
/**
 * Unit tests for HTTP/Session/Container.php
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

// Call HTTP_Session_ContainerTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "HTTP_Session_ContainerTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

//make cvs testing work
chdir(dirname(__FILE__) . '/../');
require_once 'HTTP/Session/Container.php';

/**
 * Test class for HTTP/Session/Container.php
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
class HTTP_Session_ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constructor
     *
     * @return object
     */
    public function __construct()
    {
    }

    /**
     * Runs the test methods of this class
     *
     * @return void
     */
    public static function main()
    {
        include_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('HTTP_Session_ContainerTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Tests _parseOptions()
     *
     * @return void
     */
    public function testParseOptions()
    {
        $container = new HTTP_Session_Container();

        $container->options = array('dsn'          => null,
                                    'table'        => null,
                                    'autooptimize' => false);

        $container->_parseOptions(array('dsn'   => 'DSN',
                                        'table' => 'TABLE',
                                        'other' => 'invalid'));

        $this->assertEquals(array('dsn'          => 'DSN',
                                  'table'        => 'TABLE',
                                  'autooptimize' => false),
                            $container->options);
    }
}

// Call HTTP_Session_ContainerTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "HTTP_Session_ContainerTest::main") {
    ob_start();
    HTTP_Session_ContainerTest::main();
    ob_flush();
}
?>