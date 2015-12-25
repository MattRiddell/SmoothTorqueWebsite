<?
/**
 * Created by PhpStorm User: mattriddell Date: 23/12/15 Time: 18:49
 */

require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE['user'].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_GET['save_new'])) {
    //print_pre($_POST);
    $_POST['groupid'] = $campaigngroupid;
    $_POST['maxagents'] = 100;
    $_POST['context'] = 1;
    $_POST['did'] = $_POST['clid'];
    $sql1 = "INSERT INTO campaign (";
    $sql2 = ") VALUES (";
    foreach ($_POST as $key=>$value) {
        $sql1.=sanitize($key,FALSE).",";
        $sql2.=sanitize($value,TRUE).",";
    }
    $sql = substr($sql1,0,strlen($sql1)-1).substr($sql2,0,strlen($sql2)-1).")";
    mysql_query($sql) or die ("There was an error with the query $sql - ".mysql_error());
    ?>
    <div class="jumbotron">
    <h3>Saved Your Campaign</h3>
    <p>Please wait while we return you to the campaign list</p>
    </div>
    <META HTTP-EQUIV=REFRESH CONTENT="3; URL=new_campaign.php?view=1">
        <?

    require "footer.php";
    exit(0);
}
if (isset($_GET['save_edit'])) {
    $_POST['did'] = $_POST['clid'];
    $sql1 = "UPDATE campaign SET ";
    $id = $_POST['id'];
    unset($_POST['id']);
    foreach ($_POST as $key=>$value) {
        $sql1.=sanitize($key,FALSE)."=";
        $sql1.=sanitize($value,TRUE).",";
    }
    $sql = substr($sql1,0,strlen($sql1)-1)." WHERE id = ".sanitize($id);
    mysql_query($sql) or die ("There was an error with the query $sql - ".mysql_error());
    ?>
    <div class="jumbotron">
    <h3>Saved Your Campaign</h3>
    <p>Please wait while we return you to the campaign list</p>
    </div>
    <META HTTP-EQUIV=REFRESH CONTENT="3; URL=new_campaign.php?view=1">
        <?

    require "footer.php";
    exit(0);
}
if (isset($_GET['edit']) || isset($_GET['add'])) {
    if ($_COOKIE['level'] == sha1("level100")) {
        $sql = 'SELECT * FROM campaignmessage where filename like "x-%"';
    } else {
        $sql = 'SELECT * FROM campaignmessage WHERE filename like "x-%" and customer_id='.$campaigngroupid;
    }
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        // No rows
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            $messages[] = $row;
        }
    }
    if (isset($_GET['edit'])) {
        // Editing an existing entry
        $result = mysql_query("SELECT * FROM campaign WHERE id = ".sanitize($_GET['edit'])) or die(mysql_error());
        if (mysql_num_rows($result) == 0) {
            // No rows
        } else {
            $record = mysql_fetch_assoc($result);
        }
        ?>
        <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit Existing campaign</h3>
        </div>
        <form action="new_campaign.php?save_edit=1" method="post" data-toggle="validator" role="form">
        <input type="hidden" name="id" value="<?=$record['id']?>">
        <?
    } else {
        // Adding a new entry
        ?>
        <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Add a new campaign</h3>
        </div>
        <form action="new_campaign.php?save_new=1" method="post" data-toggle="validator" role="form">
        <?
        $record = array();
    }
    ?>
    <div class="panel-body" style="text-align: left">
        <div class="form-group">
            <label for="name">Campaign Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Please enter a short name for your campaign" value="<?=$record['name']?>" data-error="You need to give your campaign a name" required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label for="description">Campaign Description</label>
            <input type="text" class="form-control" id="description" name="description" placeholder="Please enter a description of your campaign"  value="<?=$record['description']?>" data-error="You need to give your campaign a short description" required>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Message To Play</label>
            <select name="messageid" class="form-control">
                <?
                foreach ($messages as $message) {
                    echo '<option value="'.$message['id'].'" ';
                    if ($message['id']==$record['messageid']) {
                        echo " selected ";
                    }
                    echo '>'.$message['name'].'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="clid">Caller ID Number</label>
            <input type="number" class="form-control" id="clid" name="clid" placeholder="I.E. 14075551234"  value="<?=$record['clid']?>" required data-error="You need a caller ID number with only the 0-9 characters (no letters or puctuation)" >
            <div class="help-block with-errors"></div>
        </div>
        <input type="submit" class="btn btn-primary" value="Save Campaign">

    </div>
    </div>
    </form>
    <?
} else if (isset($_GET['view'])) {

    if ($_COOKIE['level'] == sha1("level100")) {
        $sql = "SELECT * FROM campaign";
    } else {
        $sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE['user'].'\'';
        $result=mysql_query($sql, $link) or die (mysql_error());;
        $campaigngroupid=mysql_result($result,0,'campaigngroupid');
        $sql = "SELECT * FROM campaign WHERE groupid =".sanitize($campaigngroupid);
    }
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        // No rows
    } else {
        echo '<div class="table-responsive"><table class="table table-striped">';
        echo '<thead><tr>';
        echo '<th>Campaign Name</th><th colspan="2">Numbers</th><th>Start/Stop</th><th>Delete Campaign</th>';
        echo '</tr></thead><tbody>';
        while ($row = mysql_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td><a href="new_campaign.php?edit='.$row['id'].'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;'.ucwords($row['name']).'</td>';
            $sql = "SELECT count(*) from number where status = 'new' AND campaignid = ".sanitize($row['id']);
            if (isset($_GET['debug'])) {
                echo $sql;
            }
            $res = mysql_query($sql);
            $count = mysql_result($res,0,0);
            $res = mysql_query("SELECT count(*) from number where campaignid = ".sanitize($row['id']));
            $total = mysql_result($res,0,0);
            echo '<td>'.number_format($count)."/".number_format($total)."</td>";
            ?>
            <td>
                <a href="manage_numbers.php?add=<?=$row['id']?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Numbers</a>&nbsp;
                <a href="manage_numbers.php?view=<?=$row['id']?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"></i>&nbsp;View Numbers</a>&nbsp;
                <a href="recycle_new.php?id=<?=$row['id']?>" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-repeat"></i>&nbsp;Recycle Numbers</a>&nbsp;
                <a href="recycle.php?id=<?=$row['id']?>&type=deleteall" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;Delete Numbers</a>&nbsp;
            </td>

            <td>
                <a href="api.php?start=<?=$row['id']?>" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-play"></i>&nbsp;Start</a>
                <a href="api.php?stop=<?=$row['id']?>" class="btn btn-warning  btn-sm disabled"><i class="glyphicon glyphicon-stop"></i>&nbsp;Stop</a>
            </td>
            <td>
                <a href="new_campaign.php?delete=<?=$row['id']?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;Delete Campaign</a>&nbsp;
            </td>
            <?
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
}
//echo '</div>';
require "footer.php";
exit(0);