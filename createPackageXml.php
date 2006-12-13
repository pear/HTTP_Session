<?php
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
$p2->setReleaseVersion('0.5.4');
$p2->setAPIVersion('0.5.4');
$p2->setReleaseStability('beta');
$p2->setAPIStability('beta');
$p2->setNotes('- fixed bug #8815: Notices with error_reporting(E_ALL)
- implemented request #8911: Add the possibilty to change the session id after destroy of old one
- implemented request #9261: Add destructor to allow usage in PHP > 5.0.5
- added End-user Documentation');

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