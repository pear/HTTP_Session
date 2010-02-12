<?php
/**
 * Runs all unit tests for HTTP_Session
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

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'HTTP_Session_AllTests::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'HTTP_SessionTest.php';
require_once 'HTTP_Session_ContainerTest.php';

/**
 * All tests for HTTP_Session
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
class HTTP_Session_AllTests
{
    /**
     * Runs tests
     *
     * @return void
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Sets up and returns test suite
     *
     * @return object
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('HTTP_Session Tests');
        $suite->addTestSuite('HTTP_SessionTest');
        $suite->addTestSuite('HTTP_Session_ContainerTest');
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'HTTP_Session_AllTests::main') {
    ob_start();
    HTTP_Session_AllTests::main();
    ob_flush();
}
?>
