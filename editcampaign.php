<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

/* Include the sanitize function */
require "functions/sanitize.php";

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[name])){
    /* Campaign edit is being saved */
    
	$id = sanitize($_POST['id']);
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $context = sanitize($_POST['context']);
    if ($context == 8) {
        $messageid = sanitize($_POST['faxid']);
    } else {
        $messageid = sanitize($_POST['messageid']);
    }
    $messageid2 = sanitize($_POST['messageid2']);
    $messageid3 = sanitize($_POST['messageid3']);
    if ($_POST['context'] == 9) {
        if (isset($_POST['sms_id']) && $_POST['sms_id'] != 0) {            
            $filename = str_replace(".","#~#",$_POST['sms_message'].".");
            $result_sms = mysql_query("UPDATE campaignmessage SET filename = '".$filename."' WHERE id = ".$_POST['sms_id']);
            $messageid3 = $_POST['sms_id'];
        } else {
            $filename = str_replace(".","#~#",$_POST['sms_message'].".");
            $result = mysql_query("INSERT INTO campaignmessage (filename, name) VALUES ('".$filename."', 'SMS')");
            $messageid3 = mysql_insert_id();
        }
    }
    $modein = $_POST['mode'];
    $maxagents = sanitize($_POST['agents']);
    if ($modein == "mode_queue"){
        $mode = 1;
        $astqueuename = sanitize($_POST['astqueuename']);
        $maxagents=0;
        
    } else {
        $mode = 0;
        $astqueuename = sanitize(NULL);
        
    }
    if (isset($_POST['drive_min'])) {
        $drive_min = sanitize($_POST['drive_min']);
        $drive_max = sanitize($_POST['drive_max']);
    } else {
        $drive_min = sanitize("43.0");
        $drive_max = sanitize("61.0");
    }
    $did = sanitize($_POST['did']);
    $clid = sanitize($_POST['clid']);
    $trclid = sanitize($_POST['trclid']);
    $evergreen = sanitize($_POST['evergreen']);
    $survey = sanitize($_POST['survey']);
    $sql = "UPDATE campaign SET name=$name, description=$description, messageid=$messageid, messageid2=$messageid2, messageid3=$messageid3,
            mode=$mode, astqueuename=$astqueuename, did=$did, maxagents=$maxagents, clid=$clid, trclid=$trclid, context=$context, evergreen=$evergreen, drive_min=$drive_min, survey=$survey, drive_max = $drive_max WHERE id=$id";
    if (isset($_GET['debug'])) {
        echo $sql;
        exit(0);
    }
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("campaigns.php");
    exit;
}

require "header.php";
require "header_campaign.php";
$id = $_GET['id'];
$sql = 'SELECT * FROM campaign WHERE id='.sanitize($id).' limit 1';
$result=mysql_query($sql,$link) or die(mysql_error());
$row = mysql_fetch_assoc($result);
$row = array_map(stripslashes,$row);
//$sms = str_replace("#~#",".",$row['message3']);
/* SMS Stuff */
$sql_sms = 'SELECT filename FROM campaignmessage WHERE id = '.$row['messageid3'];
$result_sms = mysql_query($sql_sms);
$sms = str_replace("#~#",".",mysql_result($result_sms,0,0));
$sms = substr($sms,0,strlen($sms)-1);

if ($_COOKIE['level'] == sha1("level100")) {
    $sql = 'SELECT * FROM campaignmessage where filename like "x-%"';
    $sql_fax = 'SELECT * FROM campaignmessage where filename like "fax-%"';
} else {
    $sql = 'SELECT * FROM campaignmessage WHERE filename like "x-%" and customer_id='.$campaigngroupid;
    $sql_fax = 'SELECT * FROM campaignmessage WHERE filename like "fax-%" and customer_id='.$campaigngroupid;
}
$result=mysql_query($sql,$link) or die (mysql_error());
$count=0;
while ($row2[$count] = mysql_fetch_assoc($result)) {
    $count++;
}

$result_fax=mysql_query($sql_fax,$link) or die (mysql_error());
$count_fax=0;
while ($row2_fax[$count_fax] = mysql_fetch_assoc($result_fax)) {
    $count_fax++;
}

$sql="SELECT * from queue_table";
$result=mysql_query($sql,$link) or die (mysql_error());
$count2=0;
while ($row_queue[$count2] = mysql_fetch_assoc($result)) {
    $count2++;
}
$sql="SELECT astqueuename from customer where campaigngroupid=$campaigngroupid";
$result=mysql_query($sql,$link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $row_queue[$count2][name] = mysql_result($result,0,0);
    $count2++;
}

$result_surveys=mysql_query("SELECT * FROM surveys",$link) or die (mysql_error());
$count_surveys=0;
if (mysql_num_rows($result_surveys) > 0) {
    while ($row_surveys[$count_surveys] = mysql_fetch_assoc($result_surveys)) {
        $count_surveys++;
    }
}

?>

<form action="editcampaign.php" method="POST" id="addcampaign">
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
        <?/* =================== Campaign Name Field ====================== */?>
        <tr title="A short name to give to the campaign">
            <td class="thead">
                Campaign Name
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=A short name you would like to give to the campaign - preferrably one word');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>
            <td>
                <input type="HIDDEN" name="id" value="<?echo $row['id'];?>">
                <input type="TEXT" name="name" value="<?echo $row[name];?>" size="60">
            </td>
        </tr>

        <?/* ================ Campaign Description Field ================== */?>
        <tr title="A short description of the campaign">
            <td class="thead">
                Campaign Description
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=A short description of the campaign in case you are not able to tell from the Campaign Name');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>
            <td>
                <input type="TEXT" name="description" value="<?echo $row['description'];?>" size="60">
            </td>
        </tr>

<?
    if ($config_values['EVERGREEN'] == "YES") {
        ?>
<!-- Evergreen -->

<TR title="Evergreen Mode"><TD CLASS="thead">Allow Campaigns To Run Indefinitely

<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you would like this campaign to continuously run the campaign if it has numbers');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="radio" NAME="evergreen" VALUE="1" <?if ($row['evergreen'] == 1) {echo "checked";}?>> Yes&nbsp;
<INPUT TYPE="radio" NAME="evergreen" VALUE="0" <?if ($row['evergreen'] == 0) {echo "checked";}?>> No<br />
</TD>
</TR>

<?
    }
if ($config_values['configurable_drive'] == 1) {
    ?>
    
    <TR title="Configurable Drive"><TD CLASS="thead">Configurable Drive
    
    <a href="#" onclick="displaySmallMessage('includes/help.php?section=Provide SmoothTorque with a different value for intensity. Lower values will make campaigns slower, higher values will cause more overs');return false"><img src="images/help.png" border="0"></a>
    </TD><TD>
    Minimum Drive (Default 43.0) <input type="text" name="drive_min" value="<?=$row['drive_min']?>"><br />
    Maximum Drive (Default 61.0) <input type="text" name="drive_max" value="<?=$row['drive_max']?>">
    
    
    </TD>
    </TR>
    <?
}
    ?>


        <?/* =================== Queue/DID Mode Field ===================== */?>
        <tr id="mode">
            <td class="thead" width=200>
                Mode
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=What type of campaign you would like to run. <br /><br />If you are connected to the machine doing the calling then chose Queue Mode.  If you would like to receive any connected calls at a particular phone number, chose DID Mode.  Normally you will use DID Mode unless you have been told to use Queue Mode.');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>

<td width=*>
<input type="radio" name="mode" <?if ($row['mode'] == "0") {echo "selected";}?> value="didmode" rel="didmode" id="mode_did" checked onclick="document.getElementById('queue_field').style.display = 'none';document.all['queue_field'].style.display = 'none';"/>
<label for="mode_did" title="Which number to receive the calls at">DID Mode</label>
<input type="radio" name="mode" value="mode_queue" <?if ($row['mode'] == "1") {echo "checked";}?> id="mode_queue" onclick="document.getElementById('queue_field').style.display = '';document.all['queue_field'].style.display = 'visible';"/>
<label for="mode_queue" title="Use this is the agents are connected to the machine doing the calling">Queue Mode</label>
</td>
</tr>

        <?/* =================== Campaign Type Field ====================== */?>
        <tr>
            <td class="thead">
                Type of Campaign
                <a href="#" onclick="displayLargeMessage('includes/campaign_types.php');return false">
                    <img src="images/help.png" border="0" title="Type Of Campaign">
                </a>
            </td>
            <td>
                <select name="context" id="context" onchange="whatPaySelected(this.value)">
                    <option value="-1">Please chose a type of campaign...</option>
                    <option value="0" <?echo $row[context]==0?"SELECTED":""?> title="No numbers are dialed">Load Simulation</option>
                    <option value="1" <?echo $row[context]==1?"SELECTED":""?> title="Only leave a message if an answer machine is detected, hangup otherwise">Answer Machine Only</option>
                    <option value="2" <?echo $row[context]==2?"SELECTED":""?> title="Connect a person directly to the call center, don't bother with answer machines">Immediate Live</option>
                    <option value="4" <?echo $row[context]==4?"SELECTED":""?> title="Play a message to a person, and if they press 1 transfer them to the call center, don't bother with answer machines">Press 1 Live Only</option>
                    <option value="5" <?echo $row[context]==5?"SELECTED":""?> title="Connect a person directly to the call center, and leave a message on the answer machine">Immediate Live and Answer Machine</option>
                    <option value="3" <?echo $row[context]==3?"SELECTED":""?> title="Play a message to a person, if they press 1 they go to the call center, leave a message on the answer machine">Press 1 Live and Answer Machine</option>
                    <option value="6" <?echo $row[context]==6?"SELECTED":""?> title="As soon as a number is connected, transfer it to a staff memeber"> Direct Transfer</option>
                    <option value="7" <?echo $row[context]==7?"SELECTED":""?> title="When a call is answered, play back the message and then hang up"> Immediate Message Playback</option>
                    <option value="8" <?echo $row[context]==8?"SELECTED":""?> title="Ring a number, when it answers start sending a fax" >Fax Broadcast</option>
                    <option value="9" <?echo $row[context]==9?"SELECTED":""?> title="Send SMS Messages">SMS Broadcast</option>
                    <option value="15" <?echo $row[context]==15?"SELECTED":""?> title="Automated Survey">Automated Survey</option>
                    <option value="10" <?echo $row[context]==10?"SELECTED":""?>><?echo $config_values['SPARE1'];?></option>
                    <option value="11" <?echo $row[context]==11?"SELECTED":""?>><?echo $config_values['SPARE2'];?></option>
                    <option value="12" <?echo $row[context]==12?"SELECTED":""?>><?echo $config_values['SPARE3'];?></option>
                    <option value="13" <?echo $row[context]==13?"SELECTED":""?>><?echo $config_values['SPARE4'];?></option>
                    <option value="14" <?echo $row[context]==14?"SELECTED":""?>><?echo $config_values['SPARE5'];?></option>
                </select>
            </td>
        </tr>
<?/*
   ===================================================================================================
   This is for the survey
   ===================================================================================================
   */?>


<TR id="survey" style="display:none" title="The survey you would like to run"><TD CLASS="thead">Survey
<a href="#" onclick="displaySmallMessage('includes/help.php?section=Select the survey you would like to run for this campaign.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<SELECT name="surveyid">
<?
for ($i=0;$i<$count_surveys;$i++){
    $selected="";
    if ($row['survey']==$row_surveys[$i]['id']){
        $selected=" SELECTED";
    }
    echo "<OPTION VALUE=\"".$row_surveys[$i]['id']."\"$selected>".$row_surveys[$i]['name']."</OPTION>";
}
?>
</SELECT>
</TD>
</TR>
        <?/* ==================== Fax Message Field ======================= */?>
        <tr id="fax" style="display:none" title="The fax you would like to send">
            <td class="thead">
                Fax Message
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which sends a fax to the user then this is the fax that will be used.');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>
            <td>
                <select name="faxid">
                    <?
                    for ($i=0; $i<$count_fax; $i++){
                        if ($row['messageid']==$row2_fax[$i]['id']){
                            $selected = " SELECTED";
                        } else {
                            $selected = "";
                        }
                        echo "<option value=\"".$row2_fax[$i]['id']."\"$selected>".$row2_fax[$i]['name']."</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>


<?/*
   ===================================================================================================
   This is for the SMS message
   ===================================================================================================
   */?>

<?
if ($row['context'] == 9) {
    echo '<input type="hidden" name="sms_id" value="'.$row['messageid3'].'">';
}
?>
<TR id="sms" style="display:none" title="The SMS you would like to send"><TD CLASS="thead">SMS Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which sends an SMS to the user then this is the SMS that will be sent.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<input type="text" name="sms_message" size="60" value="<?=$sms?>">
</TD>
</TR>


        <?/* ==================== Live Message Field ====================== */?>
        <tr id="live_message" style="display:none" title="The message to play to the person who answers the phone">
            <td class="thead">
                Live Message
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which plays a message to the user while waiting for them to press 1 then this is the message that will be used.');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>
            <td>
                <select name="messageid">
                    <?
                    for ($count2=0; $count2<$count; $count2++){
                        if ($row['messageid']==$row2[$count2]['id']){
                            $selected = " SELECTED";
                        } else {
                            $selected = "";
                        }
                        echo "<option value=\"".$row2[$count2]['id']."\"$selected>".$row2[$count2]['name']."</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>

        <?/* ==================== AMD Message Field ======================= */?>
        <tr id="answer_machine_message"  style="display:none" title="The message to leave to the answer machine">
            <td class="thead">
                Answer Machine Message
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are leaving automated messages on answer machines then you can set this to a particular message you would like to have played when an answer machine is detected.  Usage of this will depend on your settings in the Type of Campaign section.');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>
            <td>
                <select name="messageid2">
                    <?
                    for ($count2=0; $count2<$count; $count2++){
                        if ($row['messageid2']==$row2[$count2]['id']){
                            $selected = " SELECTED";
                        } else {
                            $selected = "";
                        }
                        echo "<option value=\"".$row2[$count2]['id']."\"$selected>".$row2[$count2]['name']."</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>

        <?/* ==================== DNC Message Field ======================= */?>
        <tr id="dnc_list_message" style="display:none" title="The message played to someone who wants to be put on the DNC list">
            <td class="thead">
                DNC Confirmation Message
                <a href="#" onclick="displaySmallMessage('includes/help.php?section=This message is played to a customer who presses 2 to be added to DNC.');return false">
                    <img src="images/help.png" border="0">
                </a>
            </td>
            <td>
                <select name="messageid3">
                    <?
                    for ($count2=0; $count2<$count; $count2++){
                        if ($row['messageid3']==$row2[$count2]['id']){
                            $selected = " SELECTED";
                        } else {
                            $selected = "";
                        }
                        echo "<option value=\"".$row2[$count2]['id']."\"$selected>".$row2[$count2]['name']."</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>

        <?/* ===================== Queue Name Field ======================= */?>
        <tr id = "queue_field" title="The name of the queue used for agents" style="display: <?=$row['mode']==1?"visible":"none"?>">

            <td class="thead" width=200>
                <label for="agents">
                    Queue Name
                    <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the name of a Queue on the telephone system of the provider of this system. Normally this will be assigned to you when you set up an account.');return false">
                        <img src="images/help.png" border="0">
                    </a>
                </label>
            </td>
            <td width=*>
                <select name="astqueuename">
                    <?
                    for ($count2=0; $count2<sizeof($row_queue); $count2++){
                        if ($row['astqueuename']==$row_queue[$count2]['name']){
                            $selected = " SELECTED";
                        } else {
                            $selected = "";
                        }
                        echo "<option value=\"".$row_queue[$count2]['name']."\"$selected>".$row_queue[$count2]['name']."</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>

        <?/* ===================== Max Calls Field ======================== */?>
        <tr rel="didmode" id="max_connected_calls" title="The max number of concurrent calls to be connected to the call center">
            <td class="thead" width="216px">
                <label for="agents">
                    Maximum Connected Calls:
                    <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the number of concurrent calls you would like to receive on the call center number specified.  <br /><br />Normally this will be the number of staff you have.');return false">
                        <img src="images/help.png" border="0">
                    </a>
                </label>
            </td>
            <td width=*>
                <input type="text" name="agents" id="agents" size="28" value="<?echo ($row['maxagents'])?>">
            </td>
        </tr>

        <?/* ===================== Caller ID Field ======================== */?>
        <tr id="outbound_callerid" title="The caller ID you'd like to use">
            <td class="thead">
                <label for="did">
                    Caller ID:
                    <a href="#" onclick="displaySmallMessage('includes/help.php?section=The CallerID you would like to send on calls to your customers');return false">
                        <img src="images/help.png" border="0">
                    </a>
                </label>
            </td>
            <td>
                <input type="text" name="clid" id="did" size=28 value="<?echo ($row['clid']);?>">
            </td>
        </tr>

        <?/* ============== Call Centre Phone Number Field ================ */?>
        <tr rel="didmode" id="cc_number" title="Call Centre Phone Number">
            <td class="thead">
                <label for="did">
                    Call Center Phone Number:
                    <a href="#" onclick="displaySmallMessage('includes/help.php?section=The phone number you would like to have connected calls sent to. Eg: (123) 555-1234. ');return false">
                        <img src="images/help.png" border="0">
                    </a>
                </label>
            </td>
            <td>
                <input type="text" name="did" id="did" size=28 value="<?echo ($row['did'])?>">
            </td>
        </tr>

        <?/* =================== Save Changes Button ====================== */?>
        <tr>
        </tr>
        <tr>
            <td colspan="2" align="RIGHT">
                <input type="SUBMIT" value="Save Campaign">
            </td>
        </tr>
    </table>
</form>

<script>
whatPaySelected(<?echo $row[context];?>);
</script>

<?
require "footer.php";
?>