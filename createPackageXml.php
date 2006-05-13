<?php
set_include_path('c:/xamppnew/php/pear');
require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

// set values
$channel           = 'pear.php.net';
$package           = 'HTTP_Session';
$licence           = 'PHP Licence';
$summary           = 'Object-oriented interface to the session_* family functions';
$description       = 'Object-oriented interface to the session_* family functions.
It provides extra features such as database storage for
session data using the DB, MDB and MDB2 package. It introduces new methods
like isNew(), useCookies(), setExpire(), setIdle(),
isExpired(), isIdled() and others.';
$baseinstalldir    = 'HTTP';
$version           = '0.5.2';
$packagedirectory  = 'c:/xamppnew/htdocs/pear_dev/HTTP';
$state             = 'beta';
$filelistgenerator = 'file'; // generate from cvs or file
$notes             = '- fixed bug #6778 DB container problem
- fixed bug #7543 bug in sessionValidThru()
- replaced @ with isset() in set() and localName()
- increased optional dependency for MDB2 to 2.0.1';

$packagexml =& new PEAR_PackageFileManager2();
$packagexml->setOptions(array('baseinstalldir' => $baseinstalldir,
                              'packagedirectory' => $packagedirectory,
                              'filelistgenerator' => $filelistgenerator
                             )
                       );
$packagexml->setPackage($package);
$packagexml->setSummary($summary);
$packagexml->setDescription($description);
$packagexml->setChannel($channel);
$packagexml->setAPIVersion($version);
$packagexml->setReleaseVersion($version);
$packagexml->setReleaseStability($state);
$packagexml->setAPIStability($state);
$packagexml->setNotes($notes);
$packagexml->setPackageType('php'); // this is a PEAR-style php script package
$packagexml->addRelease(); // set up a release section
$packagexml->setPhpDep('4.2.0');
$packagexml->setPearinstallerDep('1.4.0');
$packagexml->addPackageDepWithChannel('optional', 'DB', 'pear.php.net', '1.7.6');
$packagexml->addPackageDepWithChannel('optional', 'MDB', 'pear.php.net', '1.1.4');
$packagexml->addPackageDepWithChannel('optional', 'MDB2', 'pear.php.net', '2.0.1');
$packagexml->addMaintainer('lead', 'lexxx', 'Alexander Radivanovich', 'info@wwwlab.net');
$packagexml->addMaintainer('lead', 'gurugeek', 'David Costa', 'gurugeek@php.net');
$packagexml->addMaintainer('lead', 'neufeind', 'Stefan Neufeind', 'pear.neufeind@speedpartner.de');
$packagexml->addMaintainer('lead', 'metz', 'Michael Metz', 'pear.metz@speedpartner.de');
$packagexml->addMaintainer('lead', 'troehr', 'Torsten Roehr', 'troehr@php.net');
$packagexml->addMaintainer('developer', 'negora', 'Radu Negoescu', 'php@dawnidas.com');
$packagexml->addMaintainer('developer', 'tbibbs', 'Tony Bibbs', 'tony@geeklog.net');
$packagexml->setLicense($licence, 'http://www.php.net/license');
$packagexml->generateContents(); // create the <contents> tag
$pkg =& $packagexml->exportCompatiblePackageFile1(); // get a PEAR_PackageFile object

// write to file
if (isset($_GET['make']) || (isset($_SERVER['argv'][1]) &&
                             $_SERVER['argv'][1] == 'make')) {
    $pkg->writePackageFile();
    $e = $packagexml->writePackageFile();

  // output on screen
} else {
    $pkg->debugPackageFile();
    $e = $packagexml->debugPackageFile();
}

if (PEAR::isError($e)) {
    echo $e->getMessage();
    die();
}
?>