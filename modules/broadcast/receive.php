<?php
$override_directory = dirname(__FILE__)."/../../";
require_once($override_directory."read_settings.php");
require_once($override_directory."receive_helper.php");
require $override_directory."header.php";
ob_implicit_flush(FALSE);


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if(!empty($files)){
	foreach($files as $file) {
        $filename = trim($file[path]);
        $row = 0;
        $display2 = 0;
        $filetype = strtolower(substr($filename,strlen($filename)-3));
		if ($filetype == "csv") {
			$handle = fopen($filename, "r");
			$row = 0;
			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
				$col = 0;
				foreach ($data as $column) {
//					echo "X: $row Y: $col Value: $column<br />";
					$results[$row][$col] = $column;
					$col ++;
				}
				$row ++;
			}
			fclose($handle);
			$numrows = sizeof($results);
			$numcols = sizeof($results[0]);
//			print_pre($results);
			echo "Please select the column with the phone number in it by clicking on the header<br />";
			echo "<br />";
			?><div id="tableContainer" class="tableContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable"><?
			for ($row = 0; $row <= ($numrows<100?$numrows:100); $row++) {
				if ($row == 0) {
					echo '<thead class="fixedHeader"><tr>';
				} else {
					echo '<tr>';
				}
				for ($column = 0; $column <= ($numcols<5?$numcols:5); $column++) {
					$contents = trim($results[$row][$column]);
					if ($row == 0) {
						echo "<th>";
						if (strlen($contents) > 0) {
							$contents = substr($contents,0,10);
							echo "<a href=\"csv_import?filename=".$file['name']."&column=$column\"><b>Column $column:</b><br />".$contents."</a>";
						} else {
							echo "<a href=\"csv_import?filename=".$file['name']."&column=$column\"><b>Column $column</b><br />(No title)</a>";
						}
						echo "</th>";
					} else {
						echo "<td>".$contents."</td>";
					}
				}
				if ($row == 0) {
					?><th style="width:16px"></th></tr>
					</thead>
					<tbody class="scrollContent"><?
				} else {
					echo '</tr>';
				}	
			}
			echo '</tbody></table></div>';
		} else if ($filetype == "xls") {
			require_once 'Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');		
			/* if you want you can change 'iconv' to mb_convert_encoding:
			* $data->setUTFEncoder('mb');*/
			
			/***
			*  Some function for formatting output.
			* $data->setDefaultFormat('%.2f');
			* setDefaultFormat - set format for columns with unknown formatting
			*
			* $data->setColumnFormat(4, '%.3f');
			* setColumnFormat - set format for column (apply only to number fields)
			*
			**/
			$data->read($filename);

			error_reporting(E_ALL ^ E_NOTICE);
			$numrows = $data->sheets[0]['numRows'];
			$numcols = $data->sheets[0]['numCols'];
			
			//echo "<b>Filename: </b>".$file['name']."<br />";
			//echo "<br />";
			echo "Please select the column with the phone number in it by clicking on the header<br />";
			echo "<br />";
			//if ($data->sheets[0]['numCols'] < 10) {
			?><div id="tableContainer" class="tableContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable"><?
			for ($row = 1; $row <= ($numrows<100?$numrows:100); $row++) {
				if ($row == 1) {
					echo '<thead class="fixedHeader"><tr>';
				} else {
					echo '<tr>';
				}
				for ($column = 1; $column <= ($numcols<6?$numcols:6); $column++) {
					$contents = trim($data->sheets[0]['cells'][$row][$column]);
					if ($row == 1) {
						echo "<th>";
						if (strlen($contents) > 0) {
							$contents = substr($contents,0,10);
							echo "<a href=\"xls_import?filename=".$file['name']."&column=$column\"><b>Column $column:</b> ".$contents."</a>";
						} else {
							echo "<a href=\"xls_import?filename=".$file['name']."&column=$column\"><b>Column $column</b> (No title)</a>";
						}
						echo "</th>";
					} else {
						echo "<td>".$contents."</td>";
					}
				}
				if ($row == 1) {
					?></tr>
					</thead>
					<tbody class="scrollContent"><?
				} else {
					echo '</tr>';
				}	
			}
			echo '</tbody></table></div>';
		}
	}
}
require $override_directory."footer.php";
?>
