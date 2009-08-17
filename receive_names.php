<?php
require_once("read_settings.php");
require_once("receive_helper.php");
require "header.php";
require "header_numbers.php";
ob_implicit_flush(FALSE);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

//echo $data['order'];
$order = $data['order'];
if ($order == 'names_first') {
	$name_index = 0;
	$number_index = 1;
} else {
	$name_index = 1;
	$number_index = 0;
}

if(!empty($files)){?>
	<div class="data">
	  <?
	  foreach($files as $file) {
        //print_r($file);
        $filename = $file[path];
        $row = 0;
        $display2 = 0;
        $handle = fopen($filename, "r");
        echo "<br />Importing numbers, please wait<br /><br />";
        //print_r($_POST);
        $campaignid = $data["id"];
        //$sql2 = "LOCK TABLES number WRITE";
        //mysql_query($sql2, $link) or die (mysql_error());;
        $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type, random_sort) VALUES";
        $sql_names = "INSERT IGNORE INTO names (campaignid,phonenumber,name) VALUES";
        $isfirst=true;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data[$number_index] = str_replace("(","",$data[$number_index]);
            $data[$number_index] = str_replace(")","",$data[$number_index]);
            $data[$number_index] = str_replace("-","",$data[$number_index]);
            $data[$number_index] = str_replace(" ","",$data[$number_index]);
            $data[$number_index] = str_replace("\r","",$data[$number_index]);
            if ($isfirst) {
                $sql.="(".$campaignid.",'".$data[$number_index]."','new',0, ROUND(RAND() * 999999999))";
                $sql_names.="(".$campaignid.",'".$data[$number_index]."','".$data[$name_index]."')";
                $isfirst=false;
            }
            $row++;
            $display++;
            $display2++;
            if ($display > 17347) { 
                echo "".$row." numbers imported<br />\n";
                ob_flush();flush();
                mysql_query($sql, $link) or die (mysql_error());;
                mysql_query($sql_names, $link) or die (mysql_error());;
				$display = 0;
                $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type,random_sort)  VALUES";
                $sql.="(".$campaignid.",'".$data[$number_index]."','new',0,ROUND(RAND() * 999999999))";
		        $sql_names = "INSERT IGNORE INTO names (campaignid,phonenumber,name) VALUES";
                $sql_names.="(".$campaignid.",'".$data[$number_index]."','".$data[$name_index]."')";
            } else {
				$sql.=",(".$campaignid.",'".$data[$number_index]."','new',0, ROUND(RAND() * 999999999))";
                $sql_names.=",(".$campaignid.",'".$data[$number_index]."','".$data[$name_index]."')";
			}
			
        }
        //echo "Saving Records to the Database <br />";
        echo "[".$row." numbers inserted]<br />\n";
        ob_flush();flush();
        mysql_query($sql, $link) or die ("Error in number import: ".mysql_error());;
        mysql_query($sql_names, $link) or die ("Error in name import: ".mysql_error());;
		echo "<br />";
		echo "<br />";
		fclose($handle);
		echo "<b>A total of $row numbers were inserted into the database</b><br /><br /><br />";
}
?>

	</div>
<?}?>
</body>
</html>
