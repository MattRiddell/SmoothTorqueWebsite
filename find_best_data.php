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
$sources[0]['first_name_field'] = 3;
$sources[0]['last_name_field'] = 4;

function get_data($url) {
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $numbers);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    
    return = curl_exec( $ch );
}


foreach ($records as $number=>$name) {
    foreach ($sources as $source) {
        $response[$source['name']] = get_data($source['url']);
        $exploded = explode($source['delim'],$response);
        $full_name = $exploded[$sources['first_name_field']]." ".$exploded[$sources['last_name_field']];
        $full_name_reverse = $exploded[$sources['last_name_field']]." ".$exploded[$sources['first_name_field']];
        $full_name_init = $exploded[$sources['last_name_field']]." ".substr($exploded[$sources['first_name_field']],0,1);
        $full_name_init_reverse = substr($exploded[$sources['first_name_field']],0,1)." ".$exploded[$sources['last_name_field']];
        similar_text($full_name, $name, $percentage_match_1);
        similar_text($full_name_reverse, $name, $percentage_match_2);
        similar_text($full_name_init, $name, $percentage_match_3);
        similar_text($full_name_init_reverse, $name, $percentage_match_4);
        echo $source['name'].": Match 1: ".$percentage_match_1."\n";
        echo $source['name'].": Match 2: ".$percentage_match_2."\n";
        echo $source['name'].": Match 3: ".$percentage_match_3."\n";
        echo $source['name'].": Match 4: ".$percentage_match_4."\n";
    }
}

?>