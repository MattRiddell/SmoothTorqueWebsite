<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])) {
    $_POST = array_map(mysql_real_escape_string, $_POST);
    $description = ($_POST[description]);
    $username = ($_POST[username]);
    $password = sha1($_POST[password]);
    $address1 = ($_POST[address1]);
    $address2 = ($_POST[address2]);
    $city = ($_POST[city]);
    $country = ($_POST[country]);
    $phone = ($_POST[phone]);
    $fax = ($_POST[fax]);
    $email = ($_POST[email]);
    $website = ($_POST[website]);
    $security = ($_POST[security]);
    $company = ($_POST[name]);
    $trunkid = ($_POST[trunkid]);
    $zip = ($_POST[zip]);
    $state = ($_POST[state]);
    $maxcps = $_POST[maxcps];
    $maxcps = 100;
    $maxchans = $_POST[maxchans];
    $didlogin = $_POST[didlogin];
    $astqueuename = $_POST[astqueuename];
    $interface_type = $_POST[interface_type];
    $interface_type = "default";
    if ($maxchans + 1 == 1) {
        $maxchans = 1000;
    }
    $sql = "INSERT INTO campaigngroup (name,description) VALUES ('$company','$description')";
//    echo $sql;
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $insertedID = mysql_insert_id();

    if ($security == 10) { /* Accounts user - they don't need to make calls */
        $sql = "INSERT INTO customer (username,password,campaigngroupid,address1,address2,city,
        country,phone,fax,email,website,security,company,trunkid,zip,state,astqueuename, interface_type)
        VALUES ('$username','$password','$insertedID','$address1','$address2','$city',
        '$country','$phone','$fax','$email','$website','$security','$company','$trunkid','$zip','$state','$astqueuename', '$interface_type')";
    } else if ($security == 5) { /* Agent - they don't need to make calls */
        $sql = "INSERT INTO customer (username,password,campaigngroupid,address1,address2,city,
        country,phone,fax,email,website,security,company,trunkid,zip,state,astqueuename, interface_type)
        VALUES ('$username','$password','$insertedID','$address1','$address2','$city',
        '$country','$phone','$fax','$email','$website','$security','$company','$trunkid','$zip','$state','$astqueuename', '$interface_type')";
    } else {
        $sql = "INSERT INTO customer (username,password,campaigngroupid,address1,address2,city,
        country,phone,fax,email,website,security,company,trunkid,zip,state, maxchans, maxcps, didlogin, astqueuename, interface_type)
        VALUES ('$username','$password','$insertedID','$address1','$address2','$city',
        '$country','$phone','$fax','$email','$website','$security','$company','$trunkid','$zip','$state', $maxchans, $maxcps, '$didlogin','$astqueuename', '$interface_type')";
    }

    //    echo $sql;
    $result = mysql_query($sql, $link) or die (mysql_error());;

    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Added a customer')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/


    include("customers.php");
    exit;
}
require "header.php";
require "header_customer.php";
?>

<script type="text/javascript">
    <!--
    function valid_text_field(field, msg) {
        if (field.value.length == 0) {
            field.style.background = "Red";
            return msg + "\n";
        } else {
            field.style.background = "White";
        }
        return "";
    }

    function validate(form) {
        var result = "";
        result += valid_text_field(form.name, "You should supply a name for the customer");
        result += valid_text_field(form.maxcps, "You need to specify the maximum number of calls per second");
        i
        result += valid_text_field(form.maxchans, "You need to specify the maximum number of channels");
        result += valid_text_field(form.username, "You need to provide a username");
        if (result.length>0) {
            alert("Some fields need your attention: \n" + result);
            return false;
        }
        $password = valid_text_field(
            form.password, "You've provided a blank password, is this really what you want to do?"
        )
    )
    ;
    if ($password != "") {
        return confirm($password)
    }
    return true;
    }
    //-->

</script>

<FORM onsubmit="return validate(this)" ACTION="addcustomer.php" METHOD="POST">
    <table class="table table-striped" align="center" border="0" cellpadding="0" cellspacing="2">
        <?
        ?>
        <TR>
            <TD CLASS="theadx">Customer Name</TD>
            <TD>
                <INPUT TYPE="HIDDEN" NAME="id" VALUE="<? echo $_GET[id]; ?>">
                <INPUT TYPE="TEXT" NAME="name" VALUE="<? echo $row[name]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Customer Details</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="description" VALUE="<? echo $row[description]; ?>" size="60">
            </TD>
        </TR>
        <? /*<TR><TD CLASS="theadx">Maximum Calls Per Second</TD><TD>
<INPUT TYPE="TEXT" NAME="maxcps" VALUE="<?echo $row2[maxcps];?>" size="60">
</TD>
</TR>
*/ ?>
        <TR>
            <TD CLASS="theadx">Maximum Channels</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="maxchans" VALUE="<? echo $row2[maxchans]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Username</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="username" VALUE="<? echo $row[username]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Password</TD>
            <TD>
                <INPUT TYPE="PASSWORD" NAME="password" VALUE="<? echo $row[password]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Address Line 1</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="address1" VALUE="<? echo $row[address1]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Address Line 2</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="address2" VALUE="<? echo $row[address2]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">City</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="city" VALUE="<? echo $row[city]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">State</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="state" VALUE="<? echo $row[state]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Zip</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="zip" VALUE="<? echo $row[zip]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Country</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="country" VALUE="<? echo $row[country]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Phone</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="phone" VALUE="<? echo $row[phone]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Fax</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="fax" VALUE="<? echo $row[fax]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Email</TD>
            <TD>
                <INPUT TYPE="TEXT" NAME="email" VALUE="<? echo $row[email]; ?>" size="60">
            </TD>
        </TR>
        <TR>
            <TD CLASS="theadx">Website</TD>
            <TD>
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
            <TD>
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
        <? /*
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
            <TD>
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
            <TD>
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

        </TR>
        <TR>
            <TD COLSPAN=2 ALIGN="RIGHT">
                <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Add Customer">
            </TD>
        </TR>
        <?
        ?>

    </TABLE>
</FORM>
<?
require "footer.php";
?>
