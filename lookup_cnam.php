<?
$cnam_lookup = "http://x.com/api/query.php?mode=all&output=txt&dn=";
$userpass = "user:pass";
$campaign = 64;
require "admin/db_config.php";
require "functions/sanitize.php";
mysql_select_db("SineDialer");
while (1) {
    $result = mysql_query("select distinct survey_results.phonenumber from survey_results where survey_results.campaign_id = $campaign and phonenumber in (select phonenumber from names) limit 100 order by RAND()");
    while ($row = mysql_fetch_assoc($result)) {
        $context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode($userpass))));
        $phonenumber = $row['phonenumber'];
        $url = $cnam_lookup.$phonenumber;
        $data = file_get_contents($url, false, $context);
        echo "XXXXXXXXXXX: ".$phonenumber." returns ".$data."\n";
        $skip = false;
        if (strlen(trim($data)) > 0 && $data != "WIRELESS CALLER" && $data != "U.S. CELLULAR") {
            if (substr($data,12,1) == " ") {
                $test_string = substr($data,13,1);
                if (preg_match('/[A-Za-z]/',$test_string)) {
                    $test_string = substr($data,14,1);
                    if (preg_match('/[A-Za-z]/',$test_string)) {
                        echo "CITY: ".$data."\n";
                        $skip = true;
                    } else {
                        echo substr($data,14,1) ." is not a letter\n";
                    }
                } else {
                    echo substr($data,13,1) ." is not a letter\n";
                }
            } else {
                echo substr($data,12,1)." is not space\n";
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