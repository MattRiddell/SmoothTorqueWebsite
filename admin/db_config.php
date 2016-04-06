<?
if (!isset($config_values) || $config_values['ST_MYSQL_HOST'] == "") {
    $db_host="localhost";
    $db_user="root";
    $db_pass="";
    $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
} else {
    $db_host=$config_values['ST_MYSQL_HOST'];
    $db_user=$config_values['ST_MYSQL_USER'];
    $db_pass=$config_values['ST_MYSQL_PASS'];
    $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
}
if (!mysql_select_db("SineDialer")) {
	$result = mysql_query("CREATE DATABASE SineDialer");
	mysql_select_db("SineDialer");
}
?>
<?
if (!function_exists('sanitize') ) {
    function sanitize($var, $quotes = true) {
        if (is_array($var)) {   //run each array item through this function (by reference)
            foreach ($var as &$val) {
                $val = $this->sanitize($val);
            }
        }
        else if (is_string($var)) { //clean strings
            $var = mysql_real_escape_string($var);
            if ($quotes) {
                $var = "'". $var ."'";
            }
        }
        else if (is_null($var)) {   //convert null variables to SQL NULL
            $var = "NULL";
        }
        else if (is_bool($var)) {   //convert boolean variables to binary boolean
            $var = ($var) ? 1 : 0;
        }
        return $var;
    }
}
?>