<?
require "admin/db_config.php";
require "functions/sanitize.php";
mysql_select_db("SineDialer");

/* 1. Lookup data from multiple sources
 * 2. Compare the data
 * 3. Rank the best file
 *
 
 Start by getting CNAM.
 If CNAM for this number does not exist use primary data source.
 If CNAM exists, compare to three other data sources for match.
 If we get a match use that data source.
 If we don't get a match combine data to create new record.
 
 Create rank for record authenticity
 
 */
$result = mysql_query("SELECT * FROM names limit 100");
while ($row = mysql_fetch_assoc($result)) {
    $records[$row['phonenumber']] = $row['name'];
}
//$records['14072674434'] = "Matt Riddell";
//$records['14072674435'] = "John Smith";

$sources[0]['name'] = "neel";
$sources[0]['url'] = "http://x.x.x.x/optinlookup/default.aspx";
$sources[0]['delim'] = ",";
$sources[0]['first_name_field'] = 2;
$sources[0]['last_name_field'] = 3;

function get_data($url, $number) {
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $number);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    
    return curl_exec( $ch );
}


foreach ($records as $number=>$name) {
    $name = strtoupper($name);
    foreach ($sources as $source) {
        /*$response = get_data($source['url'], $number);
        $number_arr = array();
        $number_arr[0] = $number;
        $number_arr[1] = $number;*/
        $new_url = $source['url'].$number;
        //echo "Loading: $new_url\n";
        $response = file_get_contents($new_url);
        //$response = get_data($source['url'], $number_arr);
        $exploded = explode($source['delim'],$response);
        for ($i = 0;$i<count($exploded);$i++) {
            $exploded[$i] = strtoupper(str_replace("\"","",$exploded[$i]));
        }
        //print_r($exploded);
        $full_name = $exploded[$source['first_name_field']]." ".$exploded[$source['last_name_field']];
        $full_name_reverse = $exploded[$source['last_name_field']]." ".$exploded[$source['first_name_field']];
        $full_name_init = $exploded[$source['last_name_field']]." ".substr($exploded[$source['first_name_field']],0,1);
        $full_name_init_reverse = substr($exploded[$source['first_name_field']],0,1)." ".$exploded[$source['last_name_field']];
        similar_text($full_name, $name, $percentage_match[0]);
        similar_text($full_name_reverse, $name, $percentage_match[1]);
        similar_text($full_name_init, $name, $percentage_match[2]);
        similar_text($full_name_init_reverse, $name, $percentage_match[3]);
        
        $highest = -1;
        $highest_percentage = -1;
        for ($i = 0;$i<4;$i++) {
            if ($percentage_match[$i] > $highest_percentage) {
                $highest = $i;
                $highest_percentage = $percentage_match[$i];
            }
        }
        
        /*echo $source['name'].": ".$full_name." Match 1: $name ".$percentage_match_1."\n";
        echo $source['name'].": ".$full_name_reverse." Match 2: $name ".$percentage_match_2."\n";
        echo $source['name'].": ".$full_name_init." Match 3: $name ".$percentage_match_3."\n";
        echo $source['name'].": ".$full_name_init_reverse." Match 4: $name ".$percentage_match_4."\n";*/
        switch ($highest) {
            case 0:
                $text = $full_name;
                break;
            case 1:
                $text = $full_name_reverse;
                break;
            case 2:
                $text = $full_name_init;
                break;
            case 3:
                $text = $full_name_init_reverse;
                break;
            default:
                break;
        }
        if ($highest > -1) {
            if ($highest_percentage < 50) {
                echo "No Match $highest_percentage $name with ".$text;
            } else if ($highest_percentage < 75) {
                echo "Spouse Match $highest_percentage $name with ".$text;
            } else if ($highest_percentage < 100) {
                echo "Almost exact Match $highest_percentage $name with ".$text;
            } else {
                echo "EXACT MATCH $name with ".$text;
            }            
        } else {
            echo "No match";
        }
        echo "\n";
    }
}

?>