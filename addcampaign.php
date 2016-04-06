<?
    
    require "header.php";
    require "header_campaign.php";
    $sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE['user'].'\'';
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $campaigngroupid=mysql_result($result,0,'campaigngroupid');
    if (isset($_POST['name'])){
        if ($_POST['context']!="-1"){
            $_POST = array_map(mysql_real_escape_string,$_POST);
            $id=($_POST['id']);
            $name=($_POST['name']);
            $description=($_POST['description']);
            $context=$_POST['context'];
            if ($context == 8) {
                $messageid=$_POST['faxid'];
            } else {
                $messageid=$_POST['messageid'];
            }
            $messageid2=($_POST['messageid2']);
            $messageid3=($_POST['messageid3']);

            if ($context == 9) {
                $filename = str_replace(".","#~#",$_POST['sms_message'].".");
                $result = mysql_query("INSERT INTO campaignmessage (filename, name) VALUES ('".sanitize($filename)."', 'SMS')");
                $messageid3 = mysql_insert_id();
            }


            $modein=($_POST['mode']);
            if (!isset($_POST['surveyid'])) {
                $survey=-1;
            } else {
                $survey=($_POST['surveyid']);
            }

            $maxagents=($_POST['agents']);
            if ($modein == "mode_queue"){
                $mode = 1;
                $astqueuename=($_POST['astqueuename']);
                $maxagents=0;
            } else {
                $mode = 0;
                $astqueuename=NULL;
            }
            $evergreen=($_POST['evergreen']);
            $did=($_POST['did']);
            $clid=($_POST['clid']);
            $trclid=($_POST['trclid']);
            if (isset($_POST['drive_min'])) {
                $drive_min = $_POST['drive_min'];
                $drive_max = $_POST['drive_max'];
            } else {
                $drive_min = "43.0";
                $drive_max = "61.0";
            }
            $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3,mode,astqueuename,did,maxagents,clid,trclid,context,evergreen,drive_min,drive_max,survey) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3','$mode','$astqueuename','$did','$maxagents','$clid','$trclid','$context','$evergreen','$drive_min','$drive_max','$survey')";
            //    echo $sql;
            $result=mysql_query($sql, $link) or die (mysql_error());;
            /*================= Log Access ======================================*/
            $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Added a campaign')";
            $result=mysql_query($sql, $link);
            /*================= Log Access ======================================*/
            redirect("campaigns.php","Saved",0);
            exit;
        } else {
            $error = "Please select a campaign";
        }
    }
    ?>

<FORM ACTION="addcampaign.php" METHOD="POST" id="addcampaign">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
$row['drive_min'] = "43.0";
$row['drive_max'] = "61.0";
    ?>

<!-- Campaign Name -->

<TR title="The name for the campaign"><TD CLASS="thead">Campaign Name
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short name you would like to give to the campaign - preferrably one word');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR>

<!-- Campaign Description -->

<TR title="A short description of the campaign"><TD CLASS="thead">Campaign Description
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short description of the campaign in case you are not able to tell from the Campaign Name');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>

<?
    if ($config_values['EVERGREEN'] == "YES") {
        ?>
<!-- Evergreen -->

<TR title="Evergreen Mode"><TD CLASS="thead">Allow Campaigns To Run Indefinitely

<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you would like this campaign to continuously redial all numbers whether answered or not');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="radio" NAME="evergreen" VALUE="1" onclick="displaySmallMessage('includes/help.php?section=Warning: this will redial all numbers whether they are answered or not');return true;"> Yes&nbsp;
<INPUT TYPE="radio" NAME="evergreen" VALUE="0" checked> No<br />
<?echo $row['evergreen'];?>
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

<!-- Campaign Mode -->

<tr id="mode" style="display:none">
<td class="thead" width=200>Mode
<a href="#" onclick="displaySmallMessage('includes/help.php?section=What type of campaign you would like to run. <br /><br />If you are connected to the machine doing the calling then chose Queue Mode.  If you would like to receive any connected calls at a particular phone number, chose DID Mode.  Normally you will use DID Mode unless you have been told to use Queue Mode.');return false"><img src="images/help.png" border="0"></a>
</td>
<td width=*>
<input type="radio" name="mode" value="didmode" rel="didmode" id="mode_did" checked onclick="document.getElementById('queue_field').style.display = 'none';document.all['queue_field'].style.display = 'none';"/>
<label for="mode_did" title="Which number to receive the calls at">DID Mode</label>
<input type="radio" name="mode" value="mode_queue" id="mode_queue" onclick="document.getElementById('queue_field').style.display = '';document.all['queue_field'].style.display = 'visible';"/>
<label for="mode_queue" title="Use this is the agents are connected to the machine doing the calling">Queue Mode</label>
</td>
</tr>
<TR><TD CLASS="thead">Type of Campaign
<a href="#" onclick="displayLargeMessage('includes/campaign_types.php');return false"><img src="images/help.png" border="0" title="Type Of Campaign"></a>
</TD><td>
        <select name="context" class="form-control" id="context" onchange="whatPaySelected(this.value)">
            <option value="-1">Please chose a type of campaign...</option>
            <?
            if ($config_values['disable_all_types'] != "YES") {
                ?>
                <option value="0" <? echo $row[context] == 0 ? "SELECTED" : "" ?> title="No numbers are dialed">Load Simulation</option>
                <option value="1" <? echo $row[context] == 1 ? "SELECTED" : "" ?> title="Only leave a message if an answer machine is detected, hangup otherwise">Answer Machine Only</option>
                <option value="2" <? echo $row[context] == 2 ? "SELECTED" : "" ?> title="Connect a person directly to the call center, don't bother with answer machines">Immediate Live</option>
                <option value="4" <? echo $row[context] == 4 ? "SELECTED" : "" ?> title="Play a message to a person, and if they press 1 transfer them to the call center, don't bother with answer machines">Press 1 Live Only</option>
                <option value="5" <? echo $row[context] == 5 ? "SELECTED" : "" ?> title="Connect a person directly to the call center, and leave a message on the answer machine">Immediate Live and Answer Machine</option>
                <option value="3" <? echo $row[context] == 3 ? "SELECTED" : "" ?> title="Play a message to a person, if they press 1 they go to the call center, leave a message on the answer machine">Press 1 Live and Answer Machine</option>
                <option value="6" <? echo $row[context] == 6 ? "SELECTED" : "" ?> title="As soon as a number is connected, transfer it to a staff memeber"> Direct Transfer</option>
                <option value="7" <? echo $row[context] == 7 ? "SELECTED" : "" ?> title="When a call is answered, play back the message and then hang up"> Immediate Message Playback</option>
                <option value="8" <? echo $row[context] == 8 ? "SELECTED" : "" ?> title="Ring a number, when it answers start sending a fax">Fax Broadcast</option>
                <option value="9" <? echo $row[context] == 9 ? "SELECTED" : "" ?> title="Send SMS Messages">SMS Broadcast</option>
                <option value="15" <? echo $row[context] == 15 ? "SELECTED" : "" ?> title="Automated Survey">Automated Survey</option>
                <?
            }
            ?>
            <option value="10" <? echo $row[context] == 10 ? "SELECTED" : "" ?>><? echo $config_values['SPARE1']; ?></option>
            <?
            if ($config_values['disable_all_types'] != "YES") {
                ?>
                <option value="11" <? echo $row[context] == 11 ? "SELECTED" : "" ?>><? echo $config_values['SPARE2']; ?></option>
                <option value="12" <? echo $row[context] == 12 ? "SELECTED" : "" ?>><? echo $config_values['SPARE3']; ?></option>
                <option value="13" <? echo $row[context] == 13 ? "SELECTED" : "" ?>><? echo $config_values['SPARE4']; ?></option>
                <option value="14" <? echo $row[context] == 14 ? "SELECTED" : "" ?>><? echo $config_values['SPARE5']; ?></option>
                <?
            }
            ?>
        </select>
    </td>
</TR>
<tr rel="didmode" id="max_connected_calls" style="display:none" >
<td class="thead" width=200><label for="agents">Maximum Connected Calls:
<a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the number of concurrent calls you would like to receive on the call center number specified.  <br /><br />Normally this will be the number of staff you have.');return false" title="The number of concurrent calls to be put through to the call center"><img src="images/help.png" border="0"></a>
</label></td>
<td width=*><input type="text" name="agents" id="agents" size="60" value="0"></td>
</tr>
<?
    if ($_COOKIE[level] == sha1("level100")) {
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

    $result_surveys=mysql_query("SELECT * FROM surveys",$link) or die (mysql_error());
    $count_surveys=0;
    if (mysql_num_rows($result_surveys) > 0) {
        while ($row_surveys[$count_surveys] = mysql_fetch_assoc($result_surveys)) {
            $count_surveys++;
        }
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


    ?>
<?/*
   ===================================================================================================
   This is for the survey
   ===================================================================================================
   */?>


<TR id="survey" style="display:none" title="The survey you would like to run"><TD CLASS="thead">Survey
<a href="#" onclick="displaySmallMessage('includes/help.php?section=Select the survey you would like to run for this campaign.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<SELECT  class="form-control" name="surveyid">
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


<?/*
   ===================================================================================================
   This is for the fax message
   ===================================================================================================
   */?>


<TR id="fax" style="display:none" title="The fax you would like to send"><TD CLASS="thead">Fax Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which sends a fax to the user then this is the fax that will be used.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<SELECT  class="form-control" name="faxid">
<?
    for ($i=0;$i<$count_fax;$i++){
        $selected="";
        if ($row[messageid]==$row2_fax[$i][id]){
            $selected=" SELECTED";
        }
        echo "<OPTION VALUE=\"".$row2_fax[$i][id]."\"$selected>".$row2_fax[$i][name]."</OPTION>";
    }
    ?>
</SELECT>
</TD>
</TR>


<?/*
   ===================================================================================================
   This is for the SMS message
   ===================================================================================================
   */?>


<TR id="sms" style="display:none" title="The SMS you would like to send"><TD CLASS="thead">SMS Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which sends an SMS to the user then this is the SMS that will be sent.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<input type="text" name="sms_message" size="60">
</TD>
</TR>


<?/*
   ===================================================================================================
   This is for the live message
   ===================================================================================================
   */?>

<TR id="live_message" style="display:none" title="The message to play to the person who answers the phone"><TD CLASS="thead">Live Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which plays a message to the user while waiting for them to press 1 then this is the message that will be used.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<SELECT  class="form-control" name="messageid">
<?
    for ($count2=0;$count2<$count;$count2++){
        $selected="";
        if ($row[messageid]==$row2[$count2][id]){
            $selected=" SELECTED";
        }
        echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][name]."</OPTION>";
    }
    ?>
</SELECT>
</TD>
</TR>

<?/*
   ===================================================================================================
   This is for the answer machine message
   ===================================================================================================
   */?>


<TR id="answer_machine_message"  style="display:none" title="The message to leave to the answer machine"><TD CLASS="thead">Answer Machine Message<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are leaving automated messages on answer machines then you can set this to a particular message you would like to have played when an answer machine is detected.  Usage of this will depend on your settings in the Type of Campaign section.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<SELECT  class="form-control" name="messageid2">
<?
    for ($count2=0;$count2<$count;$count2++){
        $selected="";
        if ($row[messageid2]==$row2[$count2][id]){
            $selected=" SELECTED";
        }
        echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][name]."</OPTION>";
    }
    ?>
</SELECT>
</TD>
</TR>

<?/*
   ===================================================================================================
   This is for the DNC List Message
   ===================================================================================================
   */?>


<TR  id="dnc_list_message" style="display:none" title="The message played to someone who wants to be put on the DNC list"><TD CLASS="thead">DNC Confirmation Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=This message is played to a customer who presses 2 to be added to DNC.');return false"><img src="images/help.png" border="0"></a>
</TD><TD>
<SELECT  class="form-control" name="messageid3">
<?
    for ($count2=0;$count2<$count;$count2++){
        $selected="";
        if ($row[messageid3]==$row2[$count2][id]){
            $selected=" SELECTED";
        }
        echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][name]."</OPTION>";
    }
    ?>
</SELECT>
</TD>
</TR>




<tr id = "queue_field" title="The name of the queue used for agents" style="display:none">
<td class="thead" width=200><label for="agents">Queue Name
<a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the name of a Queue on the telephone system of the provider of this system. Normally this will be assigned to you when you set up an account.');return false"><img src="images/help.png" border="0"></a>
</label></td>
<td width=*>

<SELECT  class="form-control" name="astqueuename">
<?
    for ($count2=0;$count2<sizeof($row_queue);$count2++){
        $selected="";
        if ($row[astqueuename]==$row_queue[$count2][name]){
            $selected=" SELECTED";
        }
        echo "<OPTION VALUE=\"".$row_queue[$count2][name]."\"$selected>".$row_queue[$count2][name]."</OPTION>";
    }
    ?>
</SELECT>

</td>
</tr>
<tr id="outbound_callerid" style="display:none" title="The caller id you would like to send out">
<td class="thead"><label for="did">Caller ID:
<a href="#" onclick="displaySmallMessage('includes/help.php?section=The CallerID you would like to send on calls to your customers');return false"><img src="images/help.png" border="0"></a>
</label></td>
<td><input type="text" name="clid" id="did" size=60 value="ls3"></td>
</tr>
<tr rel="didmode" id="cc_number" style="display:none" title="The number for the call center">
<td class="thead"><label for="did">Call Center Phone Number:
<a href="#" onclick="displaySmallMessage('includes/help.php?section=The phone number you would like to have connected calls sent to. Eg: (123) 555-1234. ');return false"><img src="images/help.png" border="0" id="x"  ></a>
</label></td>
<td><input type="text" name="did" id="did" size=60 value="ls3"></td>
</tr>
<?/*        <tr class=tborder2>
   <td colspan="2">
   <b>Load Simulation</b><br />
   Simple test campaign.  Does not actually make any phone calls<br />
   <b>Answer Machine Only</b><br />
   Human: Hang Up. Answer Machine: Leave Message<br />
   <b>Immediate Live Only</b><br />
   Human: Connect immediately to the call center. Answer Machine: hang up.<br />
   <b>Press 1 Live Only</b><br />
   Human: Play the person message and then if they press
   1, transfer to the call center.  Answer Machine: Hang Up.<br />
   <b>Immediate Live and Answer Machine</b><br />
   Human: Connect immediately to the call center. Answer Machine: Leave the answer machine message.<br />
   <b>Press 1 Live and Answer Machine</b><br />
   Human: Play the person message and then if they press
   1, transfer to the call center.  Answer Machine: Leave the answer machine message.<br />
   <b>Direct Transfer</b><br />
   Transfer the call to the queue or did regardless of answer machine or human.<br />
   </td></tr>*/?>
<tr>




</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" class="btn btn-primary"  VALUE="<?echo $config_values['ADD_CAMPAIGN'];?>">
</TD>
</TR>
<?
    ?>

</TABLE>
</FORM>
<?
    require "footer.php";
    ?>
