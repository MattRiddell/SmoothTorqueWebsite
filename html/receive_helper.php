<?php
$data = array();
if(!empty($_GET["sid"])) { // Normal upload
  $sid = $_GET["sid"];
} else { // Asynchronous upload
  $sid = $_POST["file_1"];
  $data["title"] = $_POST["title"];
  $data["body"] = $_POST["body"];
}
$files = receive($sid,$data);


/**
* Receive uploaded file and clean up temp files
*/
function receive($sid, &$data) {
	global $tmp_dir,$upload_dir;
	$sid = ereg_replace("[^a-zA-Z0-9]","",$sid);
	$file = $tmp_dir.'/'.$sid.'_qstring';
	if(!file_exists($file)) {
		return false;
	}
	$qstr = join("",file($file));
	unlink("$tmp_dir/{$sid}_qstring");

	$q = array();
	parse_str($qstr,$q);
	
	$files = array();
	$num_files = count($q['file']['name']);
	for($i=0;$i<$num_files;$i++) {
  	$fn = $q['file']['name'][$i];
  	$b_pos = strrpos($fn, '\\');$f_pos = strrpos($fn, '/');
  	if($b_pos == false and $f_pos == false) {
  		$file_name = $fn;
  	} else {
  		$file_name = substr($fn, max($b_pos,$f_pos)+1);
  	}
  	/*****
  	Before moving the file to its final destination, you might want to check that the file
  	is what you expect it to be, for example check that it really is an image file if you are
  	building an image uploader.
  	******/
  	rename($q['file']['tmp_name'][$i], "$upload_dir/$file_name");
  	$files[] = array("name"=>$file_name,"size" => $q['file']['size'][$i],"path" => "$upload_dir/$file_name");
  }
  foreach($q as $key=>$value) {
    if($key != 'file') {
      $data[$key] = $value;
    }
  }
	cleanup($sid);
	return $files;
}

/**
* Clean up temporary files
*/
function cleanup($sid) {
	global $tmp_dir;
	$files = array("_flength","_postdata","_err","_signal","_qstring");
	foreach($files as $file) {
		if(file_exists("$tmp_dir/$sid$file")) {
			unlink("$tmp_dir/$sid$file");
		}
	}
}

/* By hp dot net at alan-smith dot no-ip dot com
Found here: http://php.net/filesize */
function size_hum_read($size){
/*
Returns a human readable size
*/
  $i=0;
  $iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
  while (($size/1024)>1) {
   $size=$size/1024;
   $i++;
  }
  return substr($size,0,strpos($size,'.')+4).$iec[$i];
}
// Usage : size_hum_read(filesize($file));
?>