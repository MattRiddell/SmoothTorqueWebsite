<?
require "header.php";

if (isset($_POST[name])) {
    if (isset($_POST[enabled])) {
        $values = $_POST['enabled'];
        foreach ($values as $a) {
            $adminlists .= $a.",";
        }
        $adminlists = substr($adminlists, 0, strlen($adminlists) - 1);
    } else {
        $adminlist = '';
    }

    //exit(0);
    //$_POST = array_map(mysql_real_escape_string($_POST));

    $description = $_POST[description];
    $username = $_POST[username];
    require "functions/sanitize.php";
    $sql2 = 'SELECT campaigngroupid FROM customer WHERE username = '.sanitize($_COOKIE[user]);
    //echo $sql2;
    $result2 = mysql_query($sql2, $link) or die (mysql_error());;

    //$current_id = mysql_result($result2,0,0);
    $sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $current_campaigngroupid = mysql_result($result, 0, 'campaigngroupid');
    //echo "Comparing $_POST[campaigngroupid] with $current_campaigngroupid";
    if ($_POST[campaigngroupid] == $current_campaigngroupid) {
        setcookie("user", $username, time() + 60000, "/");
        $_COOKIE[user] = $username;
        //echo "Change cookie etc to $username";
    }
//	exit(0);

    //$password=sha1($_POST[password]);
    $address1 = $_POST[address1];
    $address2 = $_POST[address2];
    $city = $_POST[city];
    $country = $_POST[country];
    $phone = $_POST[phone];
    $fax = $_POST[fax];
    $email = $_POST[email];
    $website = $_POST[website];
    $security = $_POST[security];
    $company = $_POST[name];
    $trunkid = $_POST[trunkid];
    $zip = $_POST[zip];
    $state = $_POST[state];
    $maxcps = $_POST[maxcps];
    $maxcps = 100;
    $maxchans = $_POST[maxchans];
    $didlogin = $_POST[didlogin];
    $astqueuename = $_POST[astqueuename];
    $interface_type = $_POST[interface_type];
    $interface_type = "default";
    $sql = "update campaigngroup set name='$company',description='$description' where id=".$_POST[campaigngroupid];
//    echo $sql;
    $result = mysql_query($sql, $link) or die (mysql_error());;
    //  $insertedID = mysql_insert_id();

    $sql = "update customer set username='$username',address1='$address1',address2='$address2',
	city='$city',country='$country',phone='$phone',fax='$fax',email='$email',website='$website',
	security='$security',company='$company', trunkid='$trunkid', zip='$zip', didlogin='$didlogin', 
	state='$state' , maxcps=$maxcps, maxchans=$maxchans, adminlists='$adminlists', astqueuename='$astqueuename', 
	interface_type='$interface_type' WHERE id=".$_POST[id];

    //echo $sql;
    $result = mysql_query($sql, $link) or die (mysql_error());;

    $sql = "UPDATE billing set accountcode = 'stl-$username' WHERE customerid = ".$_POST['id'];
    $result = mysql_query($sql, $link) or die (mysql_error());;

    //exit(0);
    redirect("customers.php");
    exit;
}

require "header_customer.php";
if (!isset($_GET[id])) {
    ?>
    <META HTTP-EQUIV=REFRESH CONTENT="0; URL=customers.php">
    <?
    exit(0);
}
$sql = 'SELECT * FROM customer WHERE id='.$_GET[id];
$result = mysql_query($sql, $link) or die (mysql_error());;
while (mysql_num_rows($result) > 0 && $row = mysql_fetch_assoc($result)) {

    $sql2 = 'SELECT * FROM campaigngroup WHERE id='.$row[campaigngroupid];
    $result2 = mysql_query($sql2, $link) or die (mysql_error());;
    $row2 = mysql_fetch_assoc($result2);

    ?>

    <FORM ACTION="editcustomer.php" METHOD="POST" name="customer">
        <table class="table table-striped" align="center" border="0" cellpadding="0" cellspacing="2">
            <?
            ?>
            <TR>
                <TD CLASS="theadx">Customer Name</TD>
                <TD colspan=2>
                    <INPUT TYPE="HIDDEN" NAME="id" VALUE="<? echo $_GET[id]; ?>">
                    <INPUT TYPE="HIDDEN" NAME="campaigngroupid" VALUE="<? echo $row[campaigngroupid]; ?>">

                    <INPUT TYPE="TEXT" NAME="name" VALUE="<? echo $row[company]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Customer Details</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="description" VALUE="<? echo $row2[description]; ?>" size="60">
                </TD>
            </TR>
            <?/*
	<TR><TD CLASS="theadx">Maximum Calls Per Second</TD><TD colspan=2>
	<INPUT TYPE="TEXT" NAME="maxcps" VALUE="<?echo $row[maxcps];?>" size="60">
	</TD>
	</TR>
	*/ ?>
            <TR>
                <TD CLASS="theadx">Maximum Channels</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="maxchans" VALUE="<? echo $row[maxchans]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Username</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="username" VALUE="<? echo $row[username]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Address Line 1</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="address1" VALUE="<? echo $row[address1]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Address Line 2</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="address2" VALUE="<? echo $row[address2]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">City</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="city" VALUE="<? echo $row[city]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">State</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="state" VALUE="<? echo $row[state]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Zip</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="zip" VALUE="<? echo $row[zip]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Country</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="country" VALUE="<? echo $row[country]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Phone</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="phone" VALUE="<? echo $row[phone]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Fax</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="fax" VALUE="<? echo $row[fax]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Email</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="email" VALUE="<? echo $row[email]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Website</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="website" VALUE="<? echo $row[website]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">DID Login ID</TD>
                <TD colspan=2>
                    <INPUT TYPE="TEXT" NAME="didlogin" VALUE="<? echo $row[didlogin]; ?>" size="60">
                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Customer Type</TD>
                <TD colspan=2>
                    <SELECT class="form-control" NAME="security">
                        <OPTION VALUE="0" <? if ($row[security] == 0) {
                            echo "SELECTED";
                        } ?>>Normal Customer
                        </OPTION>
                        <OPTION VALUE="5" <? if ($row[security] == 5) {
                            echo "SELECTED";
                        } ?>>Agent
                        </OPTION>
                        <OPTION VALUE="10" <? if ($row[security] == 10) {
                            echo "SELECTED";
                        } ?>>Accounts Management
                        </OPTION>
                        <OPTION VALUE="100" <? if ($row[security] == 100) {
                            echo "SELECTED";
                        } ?>>Administrator
                        </OPTION>
                    </SELECT>
                </TD>
            </TR>
            <?/*
	<TR><TD CLASS="theadx">Interface Type</TD><TD>
	<SELECT NAME="interface_type">
	<OPTION VALUE="default" <?if ($row[interface_type]=='default'){echo "SELECTED";}?>>Default</OPTION>
	<OPTION VALUE="broadcast" <?if ($row[interface_type]=='broadcast'){echo "SELECTED";}?>>Message Broadcasting</OPTION>
	<OPTION VALUE="cc" <?if ($row[interface_type]=='cc'){echo "SELECTED";}?>>Predictive Dialing</OPTION>
	</SELECT>
	</TD>
	</TR>
	*/ ?>
            <TR>
                <TD CLASS="theadx">Queue Name</TD>
                <TD colspan=2>
                    <SELECT class="form-control" NAME="astqueuename">
                        <?
                        $resultss = mysql_query("SELECT name from queue_table", $link);
                        while ($rowx = mysql_fetch_assoc($resultss)) {
                            //    echo ."<BR>";
                            ?>
                            <OPTION VALUE="<? echo $rowx[name]; ?>" <? if ($row[astqueuename] == $rowx[name]) {
                                echo "SELECTED";
                            } ?>><? echo $rowx[name]; ?></OPTION>
                            <?
                        }
                        ?>
                    </SELECT>

                </TD>
            </TR>
            <TR>
                <TD CLASS="theadx">Trunk</TD>
                <TD colspan=2>
                    <SELECT class="form-control" NAME="trunkid">
                        <?
                        $resultss = mysql_query("SELECT name,id from trunk", $link);
                        ?>
                        <OPTION VALUE="-1" <? if ($row[trunkid] == -1) {
                            echo "SELECTED";
                        } ?>>Default
                        </OPTION>
                        <?
                        while ($rowx = mysql_fetch_assoc($resultss)) {
                            //    echo ."<BR>";
                            ?>
                            <OPTION VALUE="<? echo $rowx[id]; ?>" <? if ($row[trunkid] == $rowx[id]) {
                                echo "SELECTED";
                            } ?>><? echo $rowx[name]; ?></OPTION>
                            <?
                        }
                        ?>
                    </SELECT>

                </TD>
            </TR>
            <tr>
                <TD CLASS="theadx" colspan="3">Assigned Lead Lists</TD>
            </tr>
            <tr>
                <TD>
                    <?
                    $rows = explode(",", $row['adminlists']);
                    ?>
                    <select class="form-control" name="enabled[]" id="enabled" size="5" multiple style="width: 200px;">
                        <?
                        $resultss2 = mysql_query("SELECT distinct(campaignid) from number where campaignid<0", $link);
                        while ($rowx2 = mysql_fetch_assoc($resultss2)) {
                            $resultss3 = mysql_query("SELECT name from campaign where id=".(0 - $rowx2[campaignid]), $link);
                            $found = 0;
                            foreach ($rows as $a) {
                                if ($a == (0 - $rowx2[campaignid])) {
                                    echo "Found";
                                    $found = 1;
                                }
                            }
                            if ($found == 1) {
                                echo '<option value="'.(0 - $rowx2[campaignid]).'">'.mysql_result($resultss3, 0, 0).'</option>   ';
                            }


                        }
                        ?>
                    </select>
                </td>
                <TD>
                    <input type="button" name="Disable" value="&nbsp;&nbsp;&nbsp; Remove -&gt; " style="width: 100px;"
                           onClick="MoveOption(this.form.enabled, this.form.disabled)"><br>
                    <br>
                    <input type="button" name="Enable" value=" &lt;- Add &nbsp;&nbsp;&nbsp;" style="width: 100px;"
                           onClick="MoveOption(this.form.disabled, this.form.enabled)"><br>

                </td>
                <TD>
                    <select class="form-control" name="disabled[]" id="disabled" size="5" multiple style="width: 200px;">
                        <?
                        //$sqlx = "SELECT adminlists from customer WHERE customerid=".

                        //$rows = explode (",",select adminlists from customer
                        $resultss2 = mysql_query("SELECT distinct(campaignid) from number where campaignid<0", $link);
                        while ($rowx2 = mysql_fetch_assoc($resultss2)) {
                            $resultss3 = mysql_query("SELECT name from campaign where id=".(0 - $rowx2[campaignid]), $link);
                            $found = 0;
                            foreach ($rows as $a) {
                                if ($a == (0 - $rowx2[campaignid])) {
                                    //echo "Found";
                                    $found = 1;
                                }
                            }
                            if ($found == 0) {
                                echo '<option value="'.(0 - $rowx2[campaignid]).'">'.mysql_result($resultss3, 0, 0).'</option>   ';
                            }


                        }
                        ?>    </select>


                </td>
            </tr>
            </TR>
            <TR>
                <TD COLSPAN=3 ALIGN="RIGHT">
                    <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Save Customer" onclick="f_selectAll('enabled[]')">

                </TD>
            </TR>
            <?
            ?>

        </TABLE>
    </FORM>
    <?
}
require "footer.php";
?>
