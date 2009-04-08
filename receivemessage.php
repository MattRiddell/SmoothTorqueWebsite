<?php
/* When you upload a file, you are uploading to the perl file. Once this
   is complete it is sent to this file.  The purpose of receivemessage.php
   is to create a few files:
        1. /var/tmp/uploads/x-SHA_OF_FILE.sln - for playing on Asterisk
        2. /var/tmp/uploads/x-SHA_OF_FILE.wav - for asterisk but wav
        3. ./uploads/x-SHA_OF_FILE.wav - for the web interface
   Once it has created these files (by using sox to convert), it redirects
   to addmessage.php which allows you to specify details for it. */

require_once("read_settings.php");
require_once("receive_helper.php");

require "header.php";
require "header_numbers.php";

ob_implicit_flush(FALSE);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

?>
<?php if(!empty($data)){?>
<?php if(isset($data['title'])){?>
<div class="inputhead">Title</div>
<div class="data"><?php echo $data['title']; ?></div>
<?}?>
	<?php if(isset($data['body'])){?>
	<div class="inputhead">Body</div>
	<div class="data"><?php echo $data['body']; ?></div>
  <?}?>
  <?}?>
  <?if(!empty($files)){?>
	<div class="data">
	  <?foreach($files as $file) {
        //print_r($file);

        //TODO: This file path needs to be sanitised to make sure that
        //the input doesn't get a command execution injection
        $hash=sha1(date('l dS \of F Y h:i:s A'));
        exec($config_values['SOX']." ".str_replace(" ","\ ",$file[path]).' -t raw -r 8000 -w -s -c 1 /var/tmp/uploads/x-'.$hash.'.sln');
        exec($config_values['SOX']." ".str_replace(" ","\ ",$file[path]).' -r 8000 -s -w -c 1 /var/tmp/uploads/x-'.$hash.'.wav');
        exec($config_values['SOX']." ".str_replace(" ","\ ",$file[path]).' -r 8000 -s -w -c 1 ./uploads/x-'.$hash.'.wav');
        ?>
<img src="/images/tick.png" onLoad="window.location = '/addmessage.php?filename=<?echo "x-".$hash.".sln";?>';">
        <?
        exit(0);
        $filename = $file[path];
        $row = 0;
        $display2 = 0;
        $handle = fopen($filename, "r");
        echo "<br />Importing numbers, please wait<br /><br />";
        //print_r($_POST);
        $campaignid = $data["id"];
        $sql2 = "LOCK TABLES number WRITE";
        mysql_query($sql2, $link) or die (mysql_error());;
        $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type) VALUES";
        $isfirst=true;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            //echo "Inside Loop: ".$data[0]."<br />";
            $data[0] = str_replace("(","",$data[0]);
            $data[0] = str_replace(")","",$data[0]);
            $data[0] = str_replace("-","",$data[0]);
            $data[0] = str_replace(" ","",$data[0]);
            $data[0] = str_replace("\r","",$data[0]);
            if ($isfirst) {
                $sql.="(".$campaignid.",'".$data[0]."','new',0)";

//                $sql2 = "SET AUTOCOMMIT=0;";//BEGIN";
//                mysql_query($sql2, $link) or die(mysql_error());
//                echo mysql_error();

                $isfirst=false;
            }
            $row++;
            $display++;
            $display2++;
            /*if ($display2>500){
                //echo "<!-- -->";
                //ob_flush();flush();

                $display2=0;
            }*/
            if ($display > 17347) { /* Just so the chances of doing nothing  */
                                   /* in the last write is low.  It doesn't */
                                   /* really matter but makes it cleaner */
                echo "".$row." numbers imported<br />\n";
                ob_flush();flush();
                //echo "saving $sql";
                mysql_query($sql, $link) or die (mysql_error());;
                $sql2="COMMIT";
                mysql_query($sql2, $link) or die (mysql_error());;
                $sql2="UNLOCK TABLES";
                mysql_query($sql2, $link) or die (mysql_error());;


				$display = 0;
                $sq2 = "LOCK TABLES number WRITE";
                mysql_query($sql2, $link) or die (mysql_error());;
                $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type)  VALUES";
                $sql.="(".$campaignid.",'".$data[0]."','new',0)";
            } else {
				$sql.=",(".$campaignid.",'".$data[0]."','new',0)";
			}
        }
        //echo "Saving Records to the Database <br />";
        echo "[".$row." numbers inserted]<br />\n";
        ob_flush();flush();
        mysql_query($sql, $link) or die (mysql_error());;
                $sql2="COMMIT";
                mysql_query($sql2, $link) or die (mysql_error());;
                $sql2="UNLOCK TABLES";
                mysql_query($sql2, $link) or die (mysql_error());;

			/*$sql2 = "SET AUTOCOMMIT=1;";
		mysql_query($sql2, $link) or die (mysql_error());;*/
//$row--;
echo "<br />";
echo "<br />";
fclose($handle);
echo "<b>A total of $row numbers were inserted into the database</b><br /><br /><br />";
/*echo "A total of $row numbers was read.  Inserting into database<br />";
    for ($i = 1;$i<$row;$i++){
        echo $i.":".$number[$i]."<br />";
    }*/
}?>

	</div>
<?}?>
</body>
</html>
