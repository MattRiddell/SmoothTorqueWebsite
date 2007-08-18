<?


switch($_REQUEST['id']) {

case 'myRequest':

echo "showDiv||";

# enter your MySQL sever, username, and password

$link = mysql_connect('localhost', 'root', '');
	

# if link dies echo error

if (!$link) {

die('Sorry could not connect: ' . mysql_error());

}

# else connected
	
echo 'Yah! connected successfully<br />';

# close connection

mysql_close($link);
	
break;

}
