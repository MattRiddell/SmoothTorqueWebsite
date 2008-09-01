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
    echo "Your website is not the latest version: ";
    echo "Local: $local_version Remote: $remote_version ";
    echo "Please go to the root of your web server and type \"svn up\"<br />";
} else {
    echo "Your website is up to date<br />";
}

echo "<br /><b>";
//$local_version = exec("cat svn_version.php");
if (!$handle = fopen("http://call.venturevoip.com/backend_version.php", 'r')) {
    echo "Can't read from remote";
} else {
    $remote_version = fread($handle,4096);
    fclose($handle);
}
$local_version = str_replace(".","",$version);
$remote_version = str_replace("M","",$remote_version);
if (trim($local_version) != trim($remote_version)) {
    echo "Your backend controller is not the latest version: ";
    echo "Local: $local_version Remote: $remote_version <br /><br />";
    echo "<a href=\"mailto:sales@venturevoip.com\">Please email VentureVoIP</a><br />";
} else {
    echo "Your controller is up to date";
}


?>
<br /><br /><a href="config.php">Go Back to Config</a>
<?


require "footer.php";
?>
