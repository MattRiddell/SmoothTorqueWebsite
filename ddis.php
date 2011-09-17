<?
/* DDI Management
 * ==============
 * 
 * Works in conjunction with the SineDialer.extensions table to provide 
 * realtime Asterisk extensions for setting up DDI numbers.
 *
 * A DDI contains three components:
 * 1. A DDI Number
 * 2. A Message to play when it is answered
 * 3. A Campaign it is associated with
 * 
 * Rather than create a separate table for DDI info and extensions we will
 * just parse the extensions_table for the info we're looking for.
 */

require "header.php";
require "header_surveys.php";
$result = mysql_query("SELECT * FROM extensions_table");
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        print_pre($row);
    }
}
require "footer.php";
?>