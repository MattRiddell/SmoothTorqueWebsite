<?
$docroot = $_SERVER["DOCUMENT_ROOT"];
/* Settings file should be placed one step above document root.
 If you can't place it there, change the row below to point to the actual location of the settings file */
$settingsfile = $docroot."/../upload_settings.inc";

if(file_exists($settingsfile)) {
  eval(file_get_contents($settingsfile));
} else {
  die("Could not find settings file. Please edit read_settings.php.");
}
?>