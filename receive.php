<?
require_once("read_settings.php");
require_once("receive_helper.php");
require "header.php";
require "header_numbers.php";

ob_implicit_flush(FALSE);

if ($config_values['USE_TIMEZONES'] == "YES") {
    $new = "new_nodial";
} else {
    $new = "new";
}

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if(!empty($data)){
    if(isset($data['title'])){
        ?>
        <div class="inputhead">Title</div>
        <div class="data">
        <?
        echo $data['title']; 
        ?></div>
        <?
    }
    if(isset($data['body'])){?>
        <div class="inputhead">Body</div>
        <div class="data"><?
        echo $data['body']; ?></div>
        <?
    }
}
if(!empty($files)){
    ?>
	<div class="data">
    <?
    foreach($files as $file) {
        $filename = $file[path];
        //print_r($filename);
        //exit(0);
        $row = 0;
        $display2 = 0;
        $handle = fopen($filename, "r");
        echo "<br />Importing numbers, please wait<br /><br />";
        $campaignid = $data["id"];
        $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type, random_sort) VALUES";
        $isfirst=true;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data[0] = str_replace("(","",$data[0]);
            $data[0] = str_replace(")","",$data[0]);
            $data[0] = str_replace("-","",$data[0]);
            $data[0] = str_replace(" ","",$data[0]);
            $data[0] = str_replace("\r","",$data[0]);
            if ($isfirst) {
                $sql.="(".$campaignid.",'".$data[0]."','".$new."',0, ROUND(RAND() * 999999999))";
                $isfirst=false;
            }
            $row++;
            $display++;
            $display2++;
            if ($display > 17347) { /* Just so the chances of doing nothing  */
                /* in the last write is low.  It doesn't */
                /* really matter but makes it cleaner */
                echo "".$row." numbers imported<br />\n";
                ob_flush();flush();
                mysql_query($sql, $link) or die (mysql_error());;
				$display = 0;
                $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type,random_sort)  VALUES";
                $sql.="(".$campaignid.",'".$data[0]."','".$new."',0,ROUND(RAND() * 999999999))";
            } else {
				$sql.=",(".$campaignid.",'".$data[0]."','".$new."',0, ROUND(RAND() * 999999999))";
			}
        }
        echo "[".$row." numbers inserted]<br />\n";
        ob_flush();flush();
        mysql_query($sql, $link) or die (mysql_error());;
        echo "<br />";
        echo "<br />";
        fclose($handle);
        echo "<b>A total of $row numbers were inserted into the database</b><br /><br /><br />";
    }
    if ($config_values['USE_TIMEZONES'] == "YES") {
        echo "Please wait while we update the timezone info<br />";
    }
    ?>
    
	</div>
    <?
    
}
if ($config_values['USE_TIMEZONES'] == "YES") {
    /* Get all of the timezone prefixes and times */
    $result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");
    
    while ($row = mysql_fetch_assoc($result)) {
        $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."', status='new' WHERE phonenumber like '".$row['prefix']."%' and status = 'new_nodial'";
        $result2 = mysql_query($sql);
        echo "Done Prefix ".$row['prefix']."<br />";
        flush();
    }
}
?>
</body>
</html>
