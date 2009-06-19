<?
$level=$_COOKIE[level];
/* Find out what the base directory name is for two reasons:
    1. So we can include files
    2. So we can explain how to set up things that are missing */
$current_directory = dirname(__FILE__);

/* What page we are currently on - this is used to highlight the menu
   system as well as to not cache certain pages like the graphs */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
   custom functions - for more information, read the comments in the
   functions.php file - most functions are in their own file in the
   functions subdirectory */
require "/".$current_directory."/functions/functions.php";

$url = "default";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link) or die("Unable to connect: ".mysql_error());

?>
<?/* start of shadow */?>
<script>
	    dojo.addOnLoad(function(){
		    // get each element with class="chans"
		    dojo.query(".chans")
			.addClass("testClass")
			.fadeOut({ duration: 5500 }).play();
		    // get each element with class="para"
		    // dojo.query(".chans")
		    //.addClass("testClass2")
		    //.animateProperty({duration:5000, properties:{color: {start: "#000", end:"#00ff00"}}}).play();
	    });
</script>
<table align="center"><tr><td><div class="example" id="v6"><div id="main"><div class="wrap1"><div class="wrap2"><div class="wrap3" align="center">

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
    <TR>
        <TD CLASS="thead">
            Name
        </TD>
        <TD CLASS="thead">
            Username
        </TD>
        <TD CLASS="thead">
            Address
        </TD>
        <TD CLASS="thead">
            Status
        </TD>
        <TD CLASS="thead">
        </TD>
        <TD CLASS="thead">
            Current Status
        </TD>
    </TR>
    <?
    $sql = 'SELECT * FROM servers order by name';
    $result=mysql_query($sql, $link) or die (mysql_error());;
    while ($row = mysql_fetch_assoc($result)) {
        if ($toggle){
            $toggle=false;
            $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
        } else {
            $toggle=true;
            $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
        }
        ?>
        <TR <?echo $class;?>>
            <TD>
                <?
                if (strlen($row[name])<25){
                    echo "<A HREF=\"editserver.php?id=".$row[id]."\">".$row[name]."&nbsp;<img src=\"/images/pencil.png\" border=\"0\" title=\"Edit\"></A>";
                } else {
                    echo "<A HREF=\"editserver.php?id=".$row[id]."\">".trim(substr($row[name],0,15))."...&nbsp;<img src=\"/images/pencil.png\" border=\"0\" title=\"Edit\"></A>";
                }
                ?>
            </TD>
            <TD>
                <?echo $row[username];?>
            </TD>
            <TD>
                <?echo $row[address];?>
            </TD>
            <TD>
                <?
                if ($row[status] == 0){
                    echo "<img src=\"/images/cross.png\">";
                    echo "<span id=\"play_".$row[id]."\"><a href=\"resetserver.php?id=$row[id]\" onclick=\"dojo.query('#play_".$row[id]."').fadeOut().play();\"><img src=\"/images/control_play_blue.png\" border=\"0\"></a></span>";
                } else if ($row[status] == 1){
                    echo "<img src=\"/images/tick.png\">";
                    echo "<span id=\"play_".$row[id]."\"><a href=\"resetserver2.php?id=$row[id]\" onclick=\"dojo.query('#play_".$row[id]."').fadeOut().play();\"><img src=\"/images/control_stop_blue.png\" border=\"0\"></a></span>";
                } else {
                    echo "<img src=\"/images/clock.png\">";
                    echo "<span id=\"play_".$row[id]."\"><a href=\"resetserver.php?id=$row[id]\" onclick=\"dojo.query('#play_".$row[id]."').fadeOut().play();\"><img src=\"/images/control_play_blue.png\" border=\"0\"></a></span>";
                }
                ?>
            </TD>
            <TD>
                <a href="#" onclick="displaySmallMessage('includes/confirmDeleteServer.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
            </TD>
            <td class="chans">
                <?
                if ($row[status] != 0){
                    //$resultx = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 's_".$row[name]."_connected"'");
                    $sql = "SELECT value FROM SineDialer.config WHERE parameter = 's_".$row[name]."_connected'";
                    $resultx = mysql_query($sql) or die(mysql_error());
                    if (mysql_num_rows($resultx) > 0) {
                        switch( mysql_result($resultx,0,0)) {
                            case 1:
                                break;
                            case 0:
                                echo "Connecting...";
                                break;
                            default:
                                echo "Status: ".mysql_result($resultx,0,0);
                                break;
                        }
                    } else {
                        //echo "<font color=\"blue\"><img src=\"/images/sq_progress.gif\"></font>";
                    }
                    $sql = "SELECT value FROM SineDialer.config WHERE parameter = 's_".$row[name]."_calls'";
                    $resultx = mysql_query($sql) or die(mysql_error());
                    if (mysql_num_rows($resultx) > 0) {
                        $num_chans =  mysql_result($resultx,0,0);
                        $total_chans += $num_chans;
                        if ($num_chans > 0) {
                            echo " (<b>".round($num_chans)." channels</b>)";
                        } else {
                            echo " <font color=\"black\">(".round($num_chans)." channels)</font>";
                        }
                    } else {
                        echo " <font color=\"red\">Unknown</font>";
                    }
                } else {
                    echo "<font color=\"#cccccc\">Disabled</font>";
                }
                ?>
            </td>
        </TR>
    <?}?>
</TABLE>
<?/*end of shadow */?>
</div></div></div></div></div></td></tr></table>
<?
require "footer.php";
?>
