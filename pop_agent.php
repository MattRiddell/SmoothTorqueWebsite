<?
/*
 *  pop_agent.php
 *  SmoothTorque Website
 *
 *  Created by Matt Riddell on 5/03/10.
 *  Copyright 2010 VentureVoIP. All rights reserved.
 *
 */

require "header.php";
if (isset($_POST['reason'])||(isset($_GET['type']) || $_GET['type'] == "in")) {
    if (isset($_GET['type'])) {
        // Logged In
        $result = mysql_query("INSERT INTO agents (agent, type) VALUES ($_GET[agent], 'in')");
    } else {
        // Logged Out
        $reason = sanitize($_POST['reason']);
        $agent = sanitize($_POST['agent']);
        $result = mysql_query("INSERT INTO agents (agent, type, reason) VALUES ($agent, 'out', $reason')");
    }
    ?>
    <script language="javascript">
    window.close();
    </script>
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
require "footer.php";
?>
