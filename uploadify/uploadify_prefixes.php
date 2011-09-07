<?
/*
 Uploadify v2.1.0
 Release Date: August 24, 2009
 
 Copyright (c) 2009 Ronnie Garcia, Travis Nickels
 
 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:
 
 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
 */
require "../header_timezones.php";
require "../admin/db_config.php";

if (!empty($_FILES)) {
    
    $filename = $_FILES['Filedata']['tmp_name'];
    $row = 0;
    $display2 = 0;
    $handle = fopen($filename, "r");
    echo "<br />Importing numbers, please wait<br /><br />";
    $campaignid = $_GET['campaignid'];
    $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type, random_sort) VALUES";
    $isfirst=true;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $data[0] = str_replace("(","",$data[0]);
        $data[0] = str_replace(")","",$data[0]);
        $data[0] = str_replace("-","",$data[0]);
        $data[0] = str_replace(" ","",$data[0]);
        $data[0] = str_replace("\r","",$data[0]);
        if ($isfirst) {
            $sql.="(".$campaignid.",'".$data[0]."','new',0, ROUND(RAND() * 999999999))";
            $isfirst=false;
        }
        $row++;
        $display++;
        $display2++;
        if ($display > 17347) { /* Just so the chances of doing nothing  */
            /* in the last write is low.  It doesn't */
            /* really matter but makes it cleaner */
            echo "".$row." numbers imported<br />\n";
            ob_flush();flush();
            mysql_query($sql, $link) or die (mysql_error());;
            $display = 0;
            $sql = "INSERT IGNORE INTO number (campaignid,phonenumber,status,type,random_sort)  VALUES";
            $sql.="(".$campaignid.",'".$data[0]."','new',0,ROUND(RAND() * 999999999))";
        } else {
            $sql.=",(".$campaignid.",'".$data[0]."','new',0, ROUND(RAND() * 999999999))";
        }
    }
    echo "[".$row." numbers inserted]<br />\n";
    ob_flush();flush();
    mysql_query($sql, $link) or die (mysql_error());;
    echo "<br />";
    echo "<br />";
    fclose($handle);
    echo "<b>A total of $row numbers were inserted into the database</b><br /><br /><br />";
    
}
?>
