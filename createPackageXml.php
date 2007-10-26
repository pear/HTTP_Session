<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Script to create package2.xml
 *
 * PHP version 4
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  HTTP
 * @package   HTTP_Session
 * @author    Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/HTTP_Session
 */

set_include_path('c:/wwwroot/pear');
require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagefile = 'c:/wwwroot/pear_dev/Package/package2.xml';

$options = array('filelistgenerator' => 'file',
                 'baseinstalldir'    => 'HTTP',
                 'outputdirectory'   => 'c:/wwwroot/pear_dev/Package',
                 'simpleoutput'      => true,
                 'changelogoldtonew' => false,
                 'ignore'            => array('createPackageXml.php')
                );

$p2 =& PEAR_PackageFileManager2::importOptions($packagefile, $options);
$p2->setPackageType('php');
$p2->addRelease();
$p2->generateContents();
$p2->setReleaseVersion('0.5.6');
$p2->setAPIVersion('0.5.6');
$p2->setReleaseStability('beta');
$p2->setAPIStability('beta');
$p2->setNotes("- added container for Memcache (request #9715)
- implemented request #11022 (Setting session ID)
- implemented request #11025 (Replace \$_SERVER['SCRIPT_NAME'] with \$_SERVER['PHP_SELF'])
- added unit tests (PHP5)
- CS fixes");

// get a compatible version 1.0 of package xml
$p1 =& $p2->exportCompatiblePackageFile1();

// write to file
if (isset($_GET['make']) || (isset($_SERVER['argv'][1]) &&
                             $_SERVER['argv'][1] == 'make')) {
    $p1->writePackageFile();
    $e = $p2->writePackageFile();

    // output on screen
} else {
    $p1->debugPackageFile();
    $e = $p2->debugPackageFile();
}

if (PEAR::isError($e)) {
    echo $e->getMessage();
    die();
}
?>