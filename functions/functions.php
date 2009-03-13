<?
require $current_directory."/functions/read_config_file.php";

require $current_directory."/functions/bitmask.php";

require $current_directory."/functions/authentication.php";

require $current_directory."/functions/sanitize.php";

require $current_directory."/functions/urls.php";

require $current_directory."/functions/layout.php";

  function sec2hms ($sec, $padHours = false)
  {

    // holds formatted string
    $hms = "";

    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600);

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours)
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
          : $hours. ':';

    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in
    // minutes past the hour: to get that, we need to
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60);

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60);

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;

  }

function extractXML($xml) {

if (!($xml->children())) {
    return (string) $xml;
}

foreach ($xml->children() as $child) {
    $name=$child->getName();
    if (count($xml->$name)==1) {
        $element[$name] = extractXML($child);
    } else {
        $element[][$name] = extractXML($child);
    }
}

return $element;
}
function print_pre($text) {
    echo "<pre>";
    print_r( $text);
    echo "</pre>";
}

function draw_icons($section) {
if ($handle = opendir('./modules')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $xml = simplexml_load_file("./modules/".$file);
            if (isset($xml->icon)) {
                $icon = $xml->icon;
            } else {
                $icon = "application";
            }
            if ($xml->menu->section == $section) {
                box_button($xml->name, $icon,$xml->menu->link,$xml->description);
            }
        }
    }
}

}
function check_for_gd_library() {
    if (!extension_loaded('gd')) {
        echo "It looks like php-gd is not installed.  Installing it will depend ";
        echo "on the package manager you have installed.  Here are a few examples:<br /><br />";
        echo "Fedora/Centos:<br /><code>yum install -y php-gd</code><br /><br />";
        echo "Debian/Ubuntu:<br /><code>apt-get install php-gd</code><br /><br />";
        echo "Gentoo:<br /><code>emerge php-gd</code><br /><br />";
        echo "Mandriva/Mandrake:<br /><code>urpmi php-gd</code><br /><br />";
        exit(0);
    }

}
function check_for_upload_settings() {
    if (!file_exists("../upload_settings.inc")) {
        if (!file_exists("../../upload_settings.inc")) {
            echo "The file ../upload_settings.inc does not exist.  You will need to ";
            echo "copy it from the $current_directory/cron subdirectory by typing ";
            echo "the following commands<br /><br />";
            echo "<code>cp $current_directory/cron/upload_settings.inc $current_directory/../</code>";
            exit(0);
        }
    }
}
function check_for_upload_directory($whoami) {
    if (!file_exists("/var/tmp/uploads")) {
        echo "The directory /var/tmp/uploads does not exist.  You will need to create ";
        echo "it by typing the following commands<br /><br />";
        echo "<code>mkdir /var/tmp/uploads<br />";
        echo "chown $whoami /var/tmp/uploads<br />";
        echo "cp $current_directory/uploads/* /var/tmp/uploads</code>";
        exit(0);
    }

}
function get_backend_version() {
    if (file_exists("/SmoothTorque/SmoothTorque.version")) {
	    $fp2 = fopen("/SmoothTorque/SmoothTorque.version", "r");
	    while (!feof($fp2)) {
	    	$line = trim(fgets($fp2));
	    	if (strlen($line)>0){
	    		$version = substr($line,0,strlen($line)-1);
	    	}
	    }
	    fclose ($fp2);
        if($version>0){
            $version/=100;
        }
    }
    return $version;
}

function _get_browser() {
    $browser = array ( //reversed array
      "OPERA",
      "MSIE",            // parent
      "NETSCAPE",
      "FIREFOX",
      "SAFARI",
      "KONQUEROR",
      "MOZILLA"        // parent
    );

    $info[browser] = "OTHER";

    foreach ($browser as $parent) {
        if ( ($s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) !== FALSE ) {
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
            $version = preg_replace('/[^0-9,.]/','',$version);
            $info[browser] = $parent;
            $info[version] = $version;
            break; // first match wins
        }
    }
    return $info;
}

?>
