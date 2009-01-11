<?
    require "header.php";
    //echo "<br />";
    //echo "No customer selected";
    $sql = "SELECT customer.* FROM customer order by customer.company";
    $result = mysql_query($sql) or die(mysql_error());
?>
    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">


        Please select a customer:<br /><br />
        <form action="campaigns.php" METHOD="get">
        <select name="campaigngroupid"><?
    while ($row = mysql_fetch_assoc($result)) {
        //echo $row[id]." - ".$row[customerid]."<br>";
        ?>
       <option value="<?echo $row[id];?>"><?echo $row[company];?></option>

        <?
        //echo '<A HREF="addfunds.php?id='.$row[id].'">'.$row[company].'</a>';
    }
    ?>        </select>
    <br /><br /><input type="submit" value="Select Customer">

        </form><br />
</td>
<td>
</td></tr>
</table>
</center>
