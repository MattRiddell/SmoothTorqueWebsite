<?
$result = exec("/usr/bin/which svn");
print_r($result);
//echo $result;
//print_r(exec($result." info -r HEAD"));
print_r(exec($result." status --username web --password \"\" --no-auth-cache --show-updates --verbose .",$output));
print_r($output);
?>
