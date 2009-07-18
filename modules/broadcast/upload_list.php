<?
$override_directory = dirname(__FILE__)."/../../";
require $override_directory."header.php";
$docroot = $_SERVER["DOCUMENT_ROOT"];
/* Settings file should be placed one step above document root.
 If you can't place it there, change the row below to point to the actual location of the settings file */
$settingsfile = $docroot."/../upload_settings.inc";

if(file_exists($settingsfile)) {
  eval(file_get_contents($settingsfile));
} else {
  die("Could not find settings file. Please edit read_settings.php.");
}
if(!is_writable($tmp_dir)) {
	die("Error: PHP can't write to temp dir ($tmp_dir).<br />");
}
if(!is_writable($upload_dir)) {
	die("Error: PHP can't write to upload dir ($upload_dir).<br />");
}

/* url to upload to */
function upload_url($sid) {
  global $cgi_dir;
  return $cgi_dir."/upload.cgi?sid=$sid";
}

/* Form targeting hidden iframe necessary for asynchronous uploads */
function async_upload_form($name,$title,$url="") {
	global $cgi_dir;
	$sid = md5(uniqid(rand()));
	if(empty($url)) {
		$url = $cgi_dir."/upload.cgi?sid=$sid";
	}
	?>
	<form enctype="multipart/form-data" action="<?php echo upload_url($sid) ?>" method="post" target="iframe_<?php echo $name; ?>" />
		<div class="inputhead"><?php echo $title; ?></div>
		<input class="input" type="file" name="<?php echo $name; ?>" onchange="beginAsyncUpload(this,'<?php echo $sid; ?>');" />
		<div class="progresscontainer" style="display: none;"><div class="progressbar" id="<?php echo $name ?>_progress"></div></div>
	</form>
	<iframe name="iframe_<?php echo $name; ?>" style="border: 0;width: 0px;height: 0px;"></iframe>
	<?php
}

function async_upload_value($name) {
	?>
	<input id="<?php echo $name; ?>" type="hidden" name="<?php echo $name; ?>" value="" />
	<?php
}

function normal_target($phpfile) {
  return dirname($_SERVER["SCRIPT_NAME"])."/".$phpfile;
}
$sid = md5(uniqid(rand()));
?>

<!-- Save for Web Slices (upload_lists.psd) -->
<table id="Table_01" width="608" height="157" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="5">
			<img src="images/upload_lists_01.png" width="607" height="27" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="27" alt=""></td>
	</tr>
	<tr>
		<td rowspan="4">
			<img src="images/upload_lists_02.png" width="163" height="130" alt=""></td>
		<td rowspan="3" style="background-image: url(images/upload_audio_03.png); width:268px; height:100;" valign="center">
<div id="matt2">
	<div id="matt">
        <form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?echo normal_target('receive.php');?>" method="post">
			<br /><input type="file" name="file_1" />
    	</form>
    </div>
</div>
<center>
<table>
<tr>
<td>
<div id="progressbox" style="display: none">
	Please wait while your list is uploaded.<br />
    <div class="progresscontainer">
    	<div class="progressbar" id="progress">
        </div>
    </div>
</div>
</td></tr></table>
</center>


</td>
		<td colspan="3">
			<img src="images/upload_lists_04.png" width="176" height="66" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="66" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/upload_lists_05.png" width="9" height="64" alt=""></td>
		<td>
			<a href="#" onclick="beginUpload('<?php echo $sid ?>');">
				<img src="images/upload_lists_03-07.png" width="113" height="28" border="0" alt=""></a></td>
		<td rowspan="3">
			<img src="images/upload_lists_07.png" width="54" height="64" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="28" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/upload_lists_08.png" width="113" height="36" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/upload_lists_09.png" width="268" height="30" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="30" alt=""></td>
	</tr>
</table>

<!-- End Save for Web Slices -->


<?/*
<div id="matt2">
<h2>Upload List</h2>
<p>
Please choose a phone number list from your computer by clicking the button below:<br />
<br />
</p>
	<div id="matt">
        <form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?echo normal_target('receive.php');?>" method="post">
			<input type="file" name="file_1" />
    	</form>
    	<br />
    	<br />
    	<input type="button" onclick="beginUpload('<?php echo $sid ?>');" value="Upload">
    </div>
</div>
<center>
<table>
<tr>
<td>
<div id="progressbox" style="display: none">
	Please wait while your list is uploaded.<br /><br /><br />
    <div class="progresscontainer">
    	<div class="progressbar" id="progress">
        </div>
    </div>
</div>
</td></tr></table>
</center>
*/?>

<?
require $override_directory."footer.php";
?>
