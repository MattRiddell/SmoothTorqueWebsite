<?
$result = exec("/usr/bin/which svn");
//print_r($result);
//echo $result;
//print_r(exec($result." info -r HEAD"));
exec($result." status --username web --password \"\" --no-auth-cache --show-updates --verbose .|grep \"*\"",$output);
echo "<pre>";
print_r($output);
echo "</pre>";
?>
