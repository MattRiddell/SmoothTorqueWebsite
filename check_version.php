<?
$result = exec("/usr/bin/which svn");
print_r($result);
//echo $result;
//print_r(exec($result." info -r HEAD"));
print_r(exec($result." status --username web --password \"\" --show-updates --verbose . |grep *",$output));
print_r($output);
?>
