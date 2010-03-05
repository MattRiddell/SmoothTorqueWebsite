<?
/*
 *  pop_agent.php
 *  SmoothTorque Website
 *
 *  Created by Matt Riddell on 5/03/10.
 *  Copyright 2010 VentureVoIP. All rights reserved.
 *
 */

/* Find out what the base directory name is for two reasons:
 *  1. So we can include files
 *  2. So we can explain how to set up things that are missing
 */
$current_directory = dirname(__FILE__);
if (isset($override_directory)) {
	$current_directory = $override_directory;
}
/* What page we are currently on - this is used to highlight the menu
 * system as well as to not cache certain pages like the graphs
 */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
 * custom functions - for more information, read the comments in the
 * functions.php file - most functions are in their own file in the
 * functions subdirectory
 */
require "/".$current_directory."/functions/functions.php";
/* Load in the database connection values and chose the database name */
include "/".$current_directory."/admin/db_config.php";


if (isset($_POST['reason'])||(isset($_GET['type']) && $_GET['type'] == "in")) {
    if (isset($_GET['type'])) {
        // Logged In
        $result = mysql_query("INSERT INTO agents (agent, type) VALUES ($_GET[agent], 'in')");
    } else {
        // Logged Out
        $reason = sanitize($_POST['reason']);
        $agent = sanitize($_POST['agent']);
        $result = mysql_query("INSERT INTO agents (agent, type, reason) VALUES ($agent, 'out', $reason)");
    }
    ?>
    <html>
    <body onload="alert(hello);">
    </body>
    </html>
    <?
    
} else {
    // Just been popped
?>
<form action="pop_agent.php" method="post">
    <input type="hidden" name="agent" value="<?=$_GET['agent']?>">
    <input type="hidden" name="type" value="<?=$_GET['type']?>">
    Reason For Signing Out: <input type="text" name="reason">
    <input type="submit" value="Sign Out">
    </form>
<?
}
?>
