<?
require "header.php";
echo "<br />";
echo "<br /><b>";
$local_version = exec("cat svn_version.php");
if (!$handle = fopen("http://call.venturevoip.com/svn_version.php", 'r')) {
    echo "Can't read from remote";
} else {
    $remote_version = fread($handle,4096);
    fclose($handle);
}
$local_version = str_replace("M","",$local_version);
$remote_version = str_replace("M","",$remote_version);
if (trim($local_version) != trim($remote_version)) {
    echo "Your copy is not the latest version: ";
    echo "Local: $local_version Remote: $remote_version ";
    echo "Please go to the root of your web server and type \"svn up\"<br />";
} else {
    echo "Your copy is up to date";
}
require "footer.php";
?>
