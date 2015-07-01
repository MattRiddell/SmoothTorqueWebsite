<?
require "header.php";
//echo "<br />";
//echo "No customer selected";
$sql = "SELECT customer.* FROM customer order by customer.company";
$result = mysql_query($sql) or die(mysql_error());
box_start();
?>

                Please select a customer:<br/><br/>

                <form action="campaigns.php" METHOD="get">
                    <select  class="form-control"  name="campaigngroupid" class="form-control"><?
                        while ($row = mysql_fetch_assoc($result)) {
                            //echo $row[id]." - ".$row[customerid]."<br>";
                            ?>
                            <option value="<?echo $row[campaigngroupid]; ?>"><?echo $row[company]; ?></option>

                            <?
                            //echo '<A HREF="addfunds.php?id='.$row[id].'">'.$row[company].'</a>';
                        }
                        ?>        </select>
                    <br/><br/><input class="btn btn-primary" type="submit" value="Select Customer">

                </form>
<?
box_end();
?>