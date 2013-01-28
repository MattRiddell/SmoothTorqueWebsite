<?
$cnam_lookup = "http://x.x.x.x/api/query.php?mode=all&output=txt&dn=";
$userpass = "user:pass";
require "admin/db_config.php";
require "functions/sanitize.php";
mysql_select_db("ClusterControl");
while (1) {
    $result = mysql_query("select distinct survey_results.phonenumber from survey_results left join names on survey_results.phonenumber=names.phonenumber where survey_results.campaign_id = 64 and names.phonenumber is null");
    while ($row = mysql_fetch_assoc($result)) {
        $context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode($userpass))));
        $phonenumber = $row['phonenumber'];
        $url = $cnam_lookup.$phonenumber;
        $data = file_get_contents($url, false, $context);
        echo "XXXXXXXXXXX: ".$phonenumber." returns ".$data."\n";
        $skip = false;
        if (strlen(trim($data)) > 0 && $data != "WIRELESS CALLER" && $data != "U.S. CELLULAR") {
            if (substr($data,13,1) == " ") {
                $test_string = substr($data,14,1);
                if (preg_match('/[^A-Za-z]/',$test_string)) {
                    $test_string = substr($data,15,1);
                    if (preg_match('/[^A-Za-z]/',$test_string)) {
                        echo "CITY: ".$data."\n";
                        $skip = true;
                    } else {
                        echo substr($data,15,1) ." is not a letter\n";
                    }
                } else {
                    echo substr($data,14,1) ." is not a letter\n";
                }
            } else {
                echo substr($data,13,1)." is not space\n";
            }
        } else {
            $skip = true;
        }
        if ($skip) {
            $sql = "INSERT IGNORE INTO names (campaignid, phonenumber, name) VALUES ($campaign, '$phonenumber','XXX: ".$data."')";
            echo $sql."\n";
            mysql_query($sql);
        } else {
            $sql = "INSERT IGNORE INTO names (campaignid, phonenumber, name) VALUES ($campaign, '$phonenumber','".$data."')";
            echo $sql."\n";
            mysql_query($sql);
        }
        sleep(1);
    }
}
?>