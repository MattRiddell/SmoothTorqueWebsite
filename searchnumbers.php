<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid = mysql_result($result, 0, 'campaigngroupid');
require "header_numbers.php";

$_POST = array_map(mysql_real_escape_string, $_POST);
$_GET = array_map(mysql_real_escape_string, $_GET);

if (!isset($_POST[campaignid]) && !isset($_GET[campaignid])) {
    box_start();
    ?>

    <h2>Search Numbers</h2>
    <p>
    From here you can chose a campaign that you would like to search for the numbers in.</p>
    <FORM ACTION="searchnumbers.php" METHOD="POST" class="form-horizontal">
        <div class="form-group">
            <label for="campaignid" class="col-sm-2 control-label">Campaign:</label>
            <SELECT class="col-sm-8 input-small form-control" NAME="campaignid" id="campaignid" style="width:auto !important;">
                <?
                $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
                $result = mysql_query($sql, $link) or die (mysql_error());;
                //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
                while ($row = mysql_fetch_assoc($result)) {
                    echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name], 0, 22)."</OPTION>";
                }
                ?>
            </SELECT>
        </div>

        <div class="form-group">
            <label for="phonenumber" class="col-sm-2 control-label">Number to find:</label>

            <INPUT TYPE="TEXT" name="phonenumber"class="col-sm-8">
        </div>

        <div class="form-group">
        <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Find Number">
        </div>
    </FORM>







    <?
    box_end();
} else {
    if (isset($_GET[campaignid])) {
        $_POST[campaignid] = $_GET[campaignid];
    }
    ?>
    <br/>
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
        <TR>
            <TD CLASS="thead">
                Number
            </TD>
            <TD CLASS="thead">
                Last Updated
            </TD>
            <TD CLASS="thead">
                Status
            </TD>
            <TD CLASS="thead">

            </TD>
        </TR>

        <?
        $start = 0;
        if (isset($_POST[campaignid])) {
            $campaignid = $_POST[campaignid];
        } else {
            $campaignid = $_GET[campaignid];
        }
        if (isset($_POST[phonenumber])) {
            $phonenumber = $_POST[phonenumber];
        } else {
            $phonenumber = $_GET[phonenumber];
        }
        if ($_GET[start] > 0) {
            $start = $_GET[start];
        }
        $sql = 'SELECT count(*) FROM number WHERE campaignid='.$campaignid;
        $result = mysql_query($sql, $link) or die (mysql_error());;
        $max = mysql_result($result, 0, 'count(*)');

        $sql = 'SELECT *, UNIX_TIMESTAMP(datetime) as newdate FROM number WHERE campaignid='.$campaignid.' and phonenumber="'.$phonenumber.'"order by status asc';
        $result = mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');

        while ($row = mysql_fetch_assoc($result)) {
            if ($toggle) {
                $toggle = FALSE;
                $class = " class=\"tborder2\"";
            } else {
                $toggle = TRUE;
                $class = " class=\"tborderx\"";
            }
            ?>
            <TR <?echo $class; ?>>
                <TD>
                    <?echo "<b>".$row[phonenumber]."</b></td><td>";
                    $newdate = date(DATE_RFC822, $row["newdate"]);
                    echo " ".$newdate; ?>

                </TD>
                <TD>
                    <?echo $row[status]; ?>
                </TD>
                <TD>
                    <A TITLE="Delete this Number" HREF="deletenumber.php?campaignid=<?echo $_POST[campaignid]; ?>&number=<?echo $row[phonenumber]; ?>"><img src="images/delete.png" border="0" alt="Delete Number"></A>
                    <?
                    if ($row[status] != "new") {
                        ?>
                        <A TITLE="Reset the status of this Number" HREF="resetnumber.php?campaignid=<?echo $_POST[campaignid]; ?>&number=<?echo $row[phonenumber]; ?>"><img src="images/control_repeat_blue.png" border="0" alt="Reset Number"></A>
                    <?
                    }
                    ?>
                </TD>
            </TR>

        <?
        }

        ?>

    </TABLE>
<?
}
require "footer.php";
?>
