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
?>
