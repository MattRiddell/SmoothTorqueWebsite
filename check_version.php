<?
$local_version = exec("cat svn_version.php");
if (!$handle = fopen("http://call.venturevoip.com/svn_version.php", 'r')) {
    echo "Can't read from remote";
} else {
    $remote_version = fread($handle,4096);
    fclose($handle);
}
echo "Local: $local_version Remote: $remote_version<br />";
?>
