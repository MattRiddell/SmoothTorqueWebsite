<?
require "header.php";
require "header_numbers.php";

include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

if ($config_values['USE_TIMEZONES'] == "YES") {
    $new = "new_nodial";
} else {
    $new = "new";
}


$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (!isset($_POST[campaignid])){
    ?>
    
    <br /><br /><br /><br />
    <center>
    <table background="images/sdbox.png" width="300" height="200" class="dragme22">
    <tr>
    <td>
    </td>
    <td width="260">
    Which campaign would you like to add numbers to?<br /><br />
    <FORM ACTION="addnumbers.php" METHOD="POST">
    <table class="tborderxxx2" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
    <SELECT NAME="campaignid">
    <?
    //
    if ($level==sha1("level100")) {
        $sql = 'SELECT id,name FROM campaign';
    } else {
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
    }
    $result=mysql_query($sql, $link) or die (mysql_error());;
    //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
    while ($row = mysql_fetch_assoc($result)) {
        echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
    }
    ?>
    </SELECT>
    
    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <br />
    <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Select Campaign">
    </TD>
    </TR></table>
    </FORM></td>
    <td>
    </td></tr>
    </table>
    </center>
    
    
    
    
    
    <?
} else {
    if (isset($_POST[start])){?>
        <br /><br /><br /><br /><br />
        <table background="images/sdbox.png" align="center" width="300" height="200" cellpadding="0" cellspacing="0" class="dragme22">
        <tr><td>
        <div id="hideShow">
        Please Wait, saving Phone Numbers<br />
        <br />
        <img src="images/ajax-loader.gif"><br />
        <br />
        This may take some time...
        </div>                  <?/*for ($i=$_POST[start];$i<=$_POST[end];$i++){       */
        $split= explode("\n",$_POST[start]);
        foreach ($split as $number){
            //$myarray[$count]=$i;
            if (strlen($number>0)){
                $numbers = explode("\t",$number);
                $number = $numbers[0];
                $numbers = explode(",",$number);
                $number = $numbers[0];
                
                $number = str_replace("\r","",$number);
                $number = str_replace(" ","",$number);
                $number = str_replace("-","",$number);
                $number = str_replace("(","",$number);
                $number = str_replace(")","",$number);
                if (strlen(trim($number)) > 0) {
                    $sql="INSERT IGNORE INTO number (campaignid,phonenumber,status,type, random_sort) VALUES ($_POST[campaignid],'$number','".$new."',0, ROUND(RAND() * 999999999))";
                    $result=mysql_query($sql, $link) or die (mysql_error());;
                }
                echo "<!-- . -->";
                flush();
            }
        }
        if ($config_values['USE_TIMEZONES'] == "YES") {
            /* Get all of the timezone prefixes and times */
            $result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");
            
            while ($row = mysql_fetch_assoc($result)) {
                $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."', status='new' WHERE phonenumber like '".$row['prefix']."%' and status = 'new_nodial'";
                $result2 = mysql_query($sql) or die(mysql_error());
                echo "Done Prefix ".$row['prefix']."<br />";
                flush();
            }
        }
        
        echo "</div><img src=\"images/tick.gif\">Completed Saving";
        ?>
        <script language="javascript">
        function delayer(){
            window.location = "numbers.php"
        }
        setTimeout('delayer()', 1000);
        </script>
        <?
        echo "<BR></TD></TR>
        </TABLE>";
    } else {
        ?>
        
        <FORM ACTION="addnumbers.php" METHOD="POST">
        <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
        <?
        ?>
        <TR><TD CLASS="thead">Enter Numbers (One Per Line)</TD></TR>
        <TR><TD>
        <INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
        <TEXTAREA NAME="start" COLS="20" ROWS="20"></TEXTAREA>
        </TD>
        </TR>
        <TR><TD COLSPAN=2 ALIGN="RIGHT">
        <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Add Numbers">
        </TD>
        </TR>
        <?
        ?>
        
        </TABLE>
        </FORM><?
    }}
require "footer.php";
?>
