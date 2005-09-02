<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * HTTP_Session example file
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 * MA  02111-1307  USA
 *
 * @category   HTTP
 * @package    HTTP_Session
 * @author     David Costa <gurugeek@php.net>
 * @author     Michael Metz <pear.metz@speedpartner.de>
 * @author     Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @author     Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.gnu.org/licenses/lgpl.txt
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTTP_Session
 * @since      File available since Release 0.4.0
 */

//ob_start(); //-- For easy debugging --//

require_once ("PEAR.php");
require_once ("HTTP/Session.php");

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//PEAR::setErrorHandling(PEAR_ERROR_DIE);

//HTTP_Session::setContainer('DB', array('dsn' => 'mysql://root@localhost/database', 'table' => 'sessiondata'));
//HTTP_Session::useTransSID(false);    // set value for session.use_trans_sid
//HTTP_Session::setGcMaxLifetime(120); // set value for session.gc_maxlifetime
//HTTP_Session::setGcProbability(10);  // set value for session.gc_probability
HTTP_Session::useCookies(true);
HTTP_Session::start('SessionID', uniqid('MyID'));

?>
<html>
<head>
<style>
body, td {
    font-family: Verdana, Arial, sans-serif;
    font-size: 11px;
}
A:link { color:#003399; text-decoration: none; }
A:visited { color:#6699CC; text-decoration: none; }
A:hover { text-decoration: underline; }
</style>
<title>HTTP Session</title>
</head>
<body style="margin: 5px;">
<?php

/*
if (!isset($variable)) {
    $variable = 0;
    echo("The variable wasn't previously set<br>\n");
} else {
    $variable++;
    echo("Yes, it was set already<br>\n");
}
*/

switch (@$_GET['action']) {
    case 'setvariable':
        HTTP_Session::set('variable', 'Test string');
        //HTTP_Session::register('variable');
        break;
    case 'unsetvariable':
        HTTP_Session::set('variable', null);
        //HTTP_Session::unregister('variable');
        break;
    case 'clearsession':
        HTTP_Session::clear();
        break;
    case 'destroysession':
        HTTP_Session::destroy();
        break;
}

HTTP_Session::setExpire(60); // if value is bigger than current value of session.gc_maxlifetime, session.gc_maxlifetime will be set/increased to value
HTTP_Session::setIdle(5);

//echo("session_is_registered('variable'): <b>'" . (session_is_registered('variable') ? "<span style='color: red;'>yes</span>" : "no") . "'</b><br>\n");
//echo("isset(\$GLOBALS['variable']): <b>'" . (isset($GLOBALS['variable']) ? "<span style='color: red;'>yes</span>" : "no") . "'</b><br>\n");

echo("------------------------------------------------------------------<br>\n");
echo("Session name: <b>'" . HTTP_Session::name() . "'</b><br>\n");
echo("Session id: <b>'" . HTTP_Session::id() . "'</b><br>\n");
echo("Is new session: <b>'" . (HTTP_Session::isNew() ? "<span style='color: red;'>yes</span>" : "no") . "'</b><br>\n");
echo("Is expired: <b>'" . (HTTP_Session::isExpired() ? "<span style='color: red;'>yes</span>" : "no") . "'</b><br>\n");
echo("Is idle: <b>'" . (HTTP_Session::isIdle() ? "<span style='color: red;'>yes</span>" : "no") . "'</b><br>\n");
//echo("Variable: <b>'" . HTTP_Session::get('variable') . "'</b><br>\n");
echo("Session valid thru: <b>'" . (HTTP_Session::sessionValidThru() - time()) . "'</b><br>\n");
echo("------------------------------------------------------------------<br>\n");

if (HTTP_Session::isNew()) {
    //HTTP_Session::set('var', 'value');
    //HTTP_Session::setLocal('localvar', 'localvalue');
    //blah blah blah
}

?>
<div style="background-color: #F0F0F0; padding: 15px; margin: 5px;">
<pre>
$_SESSION:
<?php
var_dump($_SESSION);
?>
</pre>
</div>
<?php

HTTP_Session::updateIdle();

?>
<p><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?action=setvariable">Set variable</a></p>
<p><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?action=unsetvariable">Unset variable</a></p>
<p><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?action=destroysession">Destroy session</a></p>
<p><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?action=clearsession">Clear session data</a></p>
<p><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>">Reload page</a></p>
</body>
</html>