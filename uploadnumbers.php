<?
$row = 0;
$display = 0;
if ($_FILES[userfile][error] == 0) {
$handle = fopen($_FILES[userfile][tmp_name], "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        echo "[".$row."]".$data[0] . "<br />\n";
        $row++;
        $display++;
        if ($display > 50) {
}
$row--;
fclose($handle);
echo "A total of $row numbers was uploaded";
} else {
/* There was an error uploading the list of numbers */
echo "The file was not uploaded <br />";
switch ($_FILES[userfile][error]){
case UPLOAD_ERR_OK:
	echo "There is no error, the file uploaded with success. ";
	break;
case UPLOAD_ERR_INI_SIZE:
	echo "The uploaded file exceeds the upload_max_filesize directive in php.ini. ";
	break;
case UPLOAD_ERR_FORM_SIZE:
	echo "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. ";
	break;
case UPLOAD_ERR_PARTIAL:
	echo "The uploaded file was only partially uploaded. ";
	break;
case UPLOAD_ERR_NO_FILE:
	echo "No file was uploaded. ";
	break;
case UPLOAD_ERR_NO_TMP_DIR:
	echo "Missing a temporary folder.";
	break;
case UPLOAD_ERR_CANT_WRITE:
	echo "Failed to write file to disk.";
	break;
case UPLOAD_ERR_EXTENSION:
	echo "File upload stopped by extension.";
	break;
}
}
?>
