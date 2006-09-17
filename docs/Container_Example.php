<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HTTP_Session container usage example
 *
 * PHP version 4
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTTP
 * @package    HTTP_Session
 * @author     Alexander Radivanovich <info@wwwlab.net>
 * @author     David Costa <gurugeek@php.net>
 * @author     Michael Metz <pear.metz@speedpartner.de>
 * @author     Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @author     Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTTP_Session
 * @since      File available since Release 0.5.0
 */

error_reporting(E_ALL);
require_once 'HTTP/Session.php';

HTTP_Session::useTransSID(false);
HTTP_Session::useCookies(false);

// Run sessiondata.sql in your database and enter your DSN
HTTP_Session::setContainer('DB', array('dsn'   => 'mysql://root:password@localhost/test',
                                       'table' => 'sessiondata'));

/*
// use an existing DB connection
require 'DB.php';
$db =& DB::connect('mysql://root:password@localhost/test');
HTTP_Session::setContainer('DB', array('dsn'   => &$db,
                                       'table' => 'sessiondata'));

// use an existing MDB connection
require 'MDB.php';
$db =& MDB::connect('mysql://root:password@localhost/test');
HTTP_Session::setContainer('MDB', array('dsn'   => &$db,
                                       'table' => 'sessiondata'));

// use an existing MDB2 connection
require 'MDB2.php';
$db =& MDB2::connect('mysql://root:password@localhost/test');
HTTP_Session::setContainer('MDB2', array('dsn'   => &$db,
                                         'table' => 'sessiondata'));
*/


HTTP_Session::start('s');
HTTP_Session::setExpire(time() + 60);   // set expire to 60 seconds
HTTP_Session::setIdle(time() + 5);      // set idle to 5 seconds

$_SESSION['counter'] = (isset($_SESSION['counter'])) ? ++$_SESSION['counter'] : 0;
echo $_SESSION['counter'];

if (HTTP_Session::isExpired()) {
    //HTTP_Session::replicate('sessiondata_backup');    // Replicate data of current session to specified table
    HTTP_Session::destroy();
}

if (HTTP_Session::isIdle()) {
    //HTTP_Session::replicate('sessiondata_backup');    // Replicate data of current session to specified table
    HTTP_Session::destroy();
}
HTTP_Session::updateIdle();

echo '<br><br><a href="Container_Example.php?' . SID . '">refresh</a><br><br>';
?>