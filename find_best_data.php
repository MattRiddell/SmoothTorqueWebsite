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

$records['14072674434'] = "Matt Riddell";
$records['14072674435'] = "John Smith";

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
    foreach ($sources as $source) {
        /*$response = get_data($source['url'], $number);
        $number_arr = array();
        $number_arr[0] = $number;
        $number_arr[1] = $number;*/
        $new_url = $source['url'].$number;
        echo "Loading: $new_url\n";
        $response = file_get_contents($new_url);
        //$response = get_data($source['url'], $number_arr);
        $exploded = explode($source['delim'],$response);
        print_r($exploded);
        $full_name = $exploded[$source['first_name_field']]." ".$exploded[$source['last_name_field']];
        $full_name_reverse = $exploded[$source['last_name_field']]." ".$exploded[$source['first_name_field']];
        $full_name_init = $exploded[$source['last_name_field']]." ".substr($exploded[$source['first_name_field']],0,1);
        $full_name_init_reverse = substr($exploded[$source['first_name_field']],0,1)." ".$exploded[$source['last_name_field']];
        similar_text($full_name, $name, $percentage_match_1);
        similar_text($full_name_reverse, $name, $percentage_match_2);
        similar_text($full_name_init, $name, $percentage_match_3);
        similar_text($full_name_init_reverse, $name, $percentage_match_4);
        echo $source['name'].": ".$full_name." Match 1: $name ".$percentage_match_1."\n";
        echo $source['name'].": ".$full_name_reverse." Match 2: $name ".$percentage_match_2."\n";
        echo $source['name'].": ".$full_name_init." Match 3: $name ".$percentage_match_3."\n";
        echo $source['name'].": ".$full_name_init_reverse." Match 4: $name ".$percentage_match_4."\n";
    }
}

?>