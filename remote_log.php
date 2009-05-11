#!/usr/bin/php
<?php
/* This script allows you to upload statistical information
 * on the running of SmoothTorque campaigns.  It does not
 * include any information on the numbers or results of campaigns
 * but simply provides information on the input and output
 * responses of the SmoothTorque engine.
 *
 * This is primarily useful for situations where strange things
 * happen during a campaign - i.e. everyone finishes dinner at
 * extactly the same time.  In this situation the data can be
 * uploaded to VentureVoIP so that extra tests can be made and
 * better responses tailored.
 *
 * While real campaigns are occasionally the source of testing, most
 * strange situations are hard to recreate accurately in the test
 * environment.  This means that making SmoothTorque respond accurately
 * to given inputs can be hard to write for.  If we can see the exact
 * scenario then we can replay it in our development engine to tailor
 * responses.
 */

require "admin/db_config.php";
mysql_select_db("SineDialer", $link);

// Server to connect to
$ftp_server = "data.venturevoip.com";
// Username and password to login with
$ftp_user_name = "Anonymous";
$ftp_user_pass = "SmoothTorque Engine Logging";
// Directory in which to place files
$directory = "public";

exec("/bin/hostname",$hostnames);
$hostname = $hostnames[0];

// Get the ifconfig information to create unique filename
exec("/sbin/ifconfig",$result);

// Hash the results
$hashed_result = sha1(implode($result));

// Filename to give the uploaded file
$destination_file = $hashed_result."-".Date("d-m-y_H-i-s");

// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// check connection
if ((!$conn_id) || (!$login_result)) {
        echo "FTP connection has failed!\n";
        echo "Attempted to connect to $ftp_server for user $ftp_user_name\n";
        exit;
    } else {
        echo "Connected to $ftp_server, for user $ftp_user_name\n";
    }


// Write to temporary file
$tempHandle = tmpfile();

$last_campaignid = "";
$result = mysql_query("SELECT sleeps.campaignid as campaignid, sleeps.idx as idx, sleeps.value as sleepvalue, profracs.value as profracvalue, rates.value as ratesvalue FROM sleeps, profracs,rates WHERE sleeps.campaignid=profracs.campaignid and sleeps.idx = profracs.idx AND sleeps.campaignid=rates.campaignid and sleeps.idx = rates.idx order by sleeps.campaignid, sleeps.idx desc");
while ($row = mysql_fetch_assoc($result)) {
    //print_r($row);
    if ($row[campaignid] != $last_campaignid) {
        $last_campaignid = $row[campaignid];
        fwrite($tempHandle, "\n=== CAMPAIGN ID: $row[campaignid] ON HostName: $hostname ====================\n");
        $result2 = mysql_query("SELECT * FROM campaign_stats WHERE campaignid = $row[campaignid]");
        while ($row_stat = mysql_fetch_assoc($result2)) {
            foreach($row_stat as $key=>$value) {
                fwrite($tempHandle, $key.":".$value."\n");
            }
        }
        fwrite($tempHandle, "\n#===========================================================\n");
        fwrite($tempHandle, "# Next section is index, sleep ms, profrac, rate\n");
        fwrite($tempHandle, "#===========================================================\n\n");
    }
    fwrite($tempHandle, $row[idx].", ".$row[sleepvalue].", ".$row[profracvalue].", ".$row[ratesvalue]."\n");
}


rewind($tempHandle);

// upload the file
if (!ftp_chdir($conn_id, $directory)) {
    echo "Couldn't change directory\n";
} else {
    echo "Changed directory\n";
}
$upload = ftp_fput($conn_id, $destination_file, $tempHandle, FTP_BINARY);

// check upload status
if (!$upload) {
        echo "FTP upload has failed!\n";
    } else {
        echo "Uploaded $destination_file\n";
    }

fclose($tempHandle);
// close the FTP stream
ftp_close($conn_id);
?>
