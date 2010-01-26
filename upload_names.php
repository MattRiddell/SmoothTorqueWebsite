<?
require "header.php";
require "header_numbers.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (!isset($_POST[campaignid])){
    echo '<br /><br /><br /><br />';
	box_start(360);
	?><center><br /><br />Please select a campaign to add numbers to<br /><br />
	<FORM ACTION="upload_names.php" METHOD="POST">
		<table class="tborderx2xx" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
		<TD>
			<SELECT NAME="campaignid">
			<?
			if ($_COOKIE[level] == sha1("level100")) {
				echo "<OPTION VALUE=\"-1\">Shared List</OPTION>";
			}
			$level=$_COOKIE[level];
			if ($level==sha1("level100")) {
				$sql = 'SELECT id,name FROM campaign order by name';
			} else {
				$sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid.' order by name';
			}
			$result=mysql_query($sql, $link) or die (mysql_error());;
			while ($row = mysql_fetch_assoc($result)) {
				echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
			}
			?>
			</SELECT>
	
		</TD>
		</TR><TR>
		<TD COLSPAN=2 ALIGN="CENTER"><br />
		<INPUT TYPE="SUBMIT" VALUE="Select Campaign">
		</TD>
		</TR></table>
		</FORM>
	<?
	box_end();
} else {
 	if ($_POST[campaignid] == -1 && !isset($_POST[name])) {
		/*
		Create a campaign
		Create a new negative campaign id
		Use this campaign id for the generation
		*/
		?>
		<br /><br /><br /><br />
		<center>
		<table background="images/sdbox.png" width="300" height="200" class="dragme22">
			<TR>
				<td>
				</td>
				<td width="260">
					Create Administrator Number List<br />
					<br />
					<FORM ACTION="upload_names.php" METHOD="POST">
					<table class="tborderxsxx" align="center" border="0" cellpadding="0" cellspacing="2">
						<TR>
							<TD CLASS="thead">Name</TD>
							<TD>
								<INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
								<INPUT TYPE="TEXT" NAME="name" VALUE="" size="20">
							</TD>
						</TR>
						<TR>
							<TD CLASS="thead">Description</TD>
							<TD>
								<INPUT TYPE="TEXT" NAME="description" VALUE="" size="20">
							</TD>
						</TR>
						<TR>
							<TD COLSPAN=2 ALIGN="RIGHT">
								<br />
								<INPUT TYPE="SUBMIT" VALUE="Create Campaign">
							</TD>
						</TR>
					</TABLE>
				</TD>
				<td></td>
			</TR>
			</table>
		</FORM>
		<?
	} else {
		if (isset($_POST[name])){
			// echo $_POST[name];
			$sql = "INSERT INTO campaign (name, description, groupid, messageid, messageid2, messageid3)  VALUES ('$_POST[name]', '$_POST[description]', -1, 0,0,0)";
			 $result=mysql_query($sql, $link) or die (mysql_error());;
			require "upload_helper.php";
			$sid = md5(uniqid(rand()));
			
			 ?>
					<br /><br /><br /><br />
			<?
			box_start(360);
			?>
			<div id="matt2">
			<b>Upload Names and Numbers</b><br /><br />
			Please select a text file with one number per line that you would
			like to upload the numbers from and then click Upload.<br /><br />
			</div>
			<form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?echo normal_target('receive_names.php');?>" method="post">
			<center><table><tr><td>
			<div id="matt">
			<select name="order">
				<option value="names_first">Names in first column</option>
				<option value="numbers_first">Numbers in first column</option>
			</select><br />
			<br />
			<input type="file" name="file_1" />
			<input type="hidden" value="-<?echo mysql_insert_id();?>" name="id">
			</form> <br /><br />
			<input type="button" onclick="beginUpload('<?php echo $sid ?>');" value="Upload">
			</div>
			</td></tr>
			<tr><td colspan = 2 width=250>
			<div id="progressbox" style="display: none">
			Please wait while your list is uploaded.<br /><br /><br /><div class="progresscontainer"><div class="progressbar" id="progress"></div></div>
			</div>
	
			</td></tr>
			</table></center>	
			<?box_end();?>
			<br />
			 <?
		 } else {
			require "upload_helper.php";
			$sid = md5(uniqid(rand()));
			?>
			
					<br /><br /><br /><br />
			<?
			box_start(360);
			?>
			<div id="matt2">
			<b>Upload Names and Numbers</b><br /><br />
			Please select a text file with one number per line that you would
			like to upload the numbers from and then click Upload.<br /><br />
			</div>
			<form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?echo normal_target('receive_names.php');?>" method="post">
			<center><table><tr><td>
			<div id="matt">
			<select name="order">
				<option value="names_first">Names in first column</option>
				<option value="numbers_first">Numbers in first column</option>
			</select><br />
			<br />
			<input type="file" name="file_1" />
			<input type="hidden" value="<?echo $_POST[campaignid];?>" name="id">
			</form> <br /><br />
			<input type="button" onclick="beginUpload('<?php echo $sid ?>');" value="Upload">
			</div>
			</td></tr>
			<tr><td colspan = 2 width=250>
			<div id="progressbox" style="display: none">
			Please wait while your list is uploaded.<br /><br /><br /><div class="progresscontainer"><div class="progressbar" id="progress"></div></div>
			</div>	
			</td></tr>
			</table></center>
			<?box_end();?>
			<br />
			<?
		}
	}
}
?>
