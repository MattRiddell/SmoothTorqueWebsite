<?php
require "header.php";
require "phpsvnclient.php";
echo "included<div align=\"left\">";
$svn  = new phpsvnclient;
$svn->setAuth("web","");
$svn->setRepository("http://svn.venturevoip.com");
$files_now = $svn->getDirectoryFiles("/trunk/SmoothTorqueWebsite/");
//print_pre($svn);

print_pre($files_now);
//$phpajax_now = $svn->getFile("/trunk/phpajax/phpajax.php");
/**
 *  Get all logs of /trunk/phpajax/phpajax.org from  between 2 version until the last
 */
$logs = $svn->getRepositoryLogs(2000);
//print_pre($logs);

/**
 *  Get all logs of /trunk/phpajax/phpajax.org from  between 2 version until 5 version.
 */
$logs = $svn->getRepositoryLogs(2,5);
//print_pre($logs);


?>
