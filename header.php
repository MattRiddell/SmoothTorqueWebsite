<?php

/* Find out what the base directory name is for two reasons:
 *  1. So we can include files
 *  2. So we can explain how to set up things that are missing
 */
$current_directory = dirname(__FILE__);

/* What page we are currently on - this is used to highlight the menu
 * system as well as to not cache certain pages like the graphs
 */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
 * custom functions - for more information, read the comments in the
 * functions.php file - most functions are in their own file in the
 * functions subdirectory
 */
require "/".$current_directory."/functions/functions.php";

/* Find out what user the webserver is running as - this looks like you
   could also get it from $HTTP_ENV_VARS[APACHE_RUN_USER] but I'm a little
   concerned about the fact it mentions apache - i.e. won't work with
   another server type - this'll have to do for the moment
*/
$whoami = exec('whoami');

/* Get the required Cookies */
$language=$_COOKIE[language];
$user=$_COOKIE[user];
$level=$_COOKIE[level];

/* Make sure we have the environment set up correctly - if not give the
 * user some information about how to remedy the situation - these
 * functions are mainly for the installer
 */
check_for_gd_library();
check_for_upload_settings();
check_for_upload_directory($whoami);

/* This was temporarily used to check for the running of the backend.
 * Not currently used because of permission problems, but may be
 * resurrected in the future
 */
/*$cmd = "ps aux |grep `cat /SmoothTorque/exampled.lock`";*/

/* See if we can find out the version of the SmoothTorque backend that is
 * currently installed - we use this so we can inform about updates etc
 */
$version = get_backend_version();

/* Load in the database connection values and chose the database name */
include "admin/db_config.php";

/* We have to do this early because if it's missing we're going to need */
/* to recreate the information from the files inside the database.      */
/*======================================================================
                            web_config
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","web_config")){

  $sql = "CREATE TABLE web_config (
    url VARCHAR(250), LANG VARCHAR(250), language VARCHAR(250), colour VARCHAR(250),
    title VARCHAR(250), logo VARCHAR(250), contact_text TEXT,
    sox VARCHAR(250), userid VARCHAR(250), licence VARCHAR(250),
    cdr_host VARCHAR(250), cdr_user VARCHAR(250),
    cdr_pass VARCHAR(250), cdr_db VARCHAR(250),
    cdr_table VARCHAR(250), menu_home VARCHAR(250),
    menu_campaigns VARCHAR(250), menu_numbers VARCHAR(250),
    menu_dnc VARCHAR(250), menu_messages VARCHAR(250),
    menu_schedules VARCHAR(250), menu_customers VARCHAR(250),
    menu_queues VARCHAR(250), menu_servers VARCHAR(250),
    menu_trunks VARCHAR(250), menu_admin VARCHAR(250),
    menu_logout VARCHAR(250), date_colour VARCHAR(250),
    main_page_text TEXT, main_page_username VARCHAR(250),
    main_page_password VARCHAR(250), main_page_login VARCHAR(250),
    currency_symbol VARCHAR(250), per_minute VARCHAR(250),
    use_billing VARCHAR(250), front_page_billing VARCHAR(250),
    spare1 VARCHAR(250), spare2 VARCHAR(250), spare3 VARCHAR(250),
    spare4 VARCHAR(250), spare5 VARCHAR(250), st_mysql_host VARCHAR(250),
    st_mysql_user VARCHAR(250), st_mysql_pass VARCHAR(250),
    add_campaign VARCHAR(250), view_campaign VARCHAR(250),
    per_page VARCHAR(250), numbers_view VARCHAR(250),
    numbers_system VARCHAR(250), numbers_generate VARCHAR(250),
    numbers_manual VARCHAR(250), numbers_upload VARCHAR(250),
    numbers_export VARCHAR(250), numbers_search VARCHAR(250),
    numbers_title VARCHAR(250), billing_text VARCHAR(250),
    cdr_text VARCHAR(250), use_generate VARCHAR(250),
    dnc_numbers_title VARCHAR(250), dnc_view VARCHAR(250),
    dnc_search VARCHAR(250), dnc_upload VARCHAR(250),
    dnc_add VARCHAR(250), per_lead VARCHAR(250), smtp_host VARCHAR(250),
    smtp_user VARCHAR(250), smtp_pass VARCHAR(250),
    smtp_from VARCHAR(250), use_separate_dnc VARCHAR(250),
    allow_numbers_manual VARCHAR(250)
  )";
  mysql_query($sql, $link) or die (mysql_error());
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created web_config Table')";
  mysql_query($sql, $link);

  /*
   * Check if the files are present.
   * If not just create the info in the database.
   * If so, read the info out of the database.
   * Once done with this loop read the info out of the db and into
   * $config_values based on the language cookie and the url
   */
  $config_files[en] = "/stweb.conf";
  $config_files[it] = "/stweb_it.conf";
  $config_files[es] = "/stweb_es.conf";
  $sql_defaults[en] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual) VALUES
                                     ('default', 'en', 'English', '#323232', 'The SmoothTorque Enterprise Predictive Dialing Platform', '/images/logo2.png', 'For further information please email sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJWQIWU', 'localhost', 'matt', 'starsock', 'phoneDB', 'cdr', 'Home', 'Campaigns', 'Numbers', 'DNC Numbers', 'Messages', 'Schedules', 'Customers', 'Queues', 'Servers', 'Trunks', 'Admin', 'Logout', '#3333FF', 'To get started, go into your list of campaigns by clicking on the Campaigns tab at the top of this page.', 'Username', 'Password', 'Login', '$', 'Per Minute', 'YES', 'NO', 'Spare 1 (unused)', 'Spare 2 (unused)', 'Spare 3 (unused)', 'Spare 4 (unused)', 'Spare 5 (unused)', 'localhost', 'root', '', 'Add Campaign', 'View Campaigns', '200', 'View phone numbers', 'Use System Lists', 'Generate numbers automatically', 'Add number(s) manually', 'Upload numbers from a text file', 'Export Phone Numbers', 'Search for a phone number', 'Number List Management', 'Billing Logs', 'Call Details', 'YES', 'Do Not Call List', 'View existing DNC numbers', 'Search DNC numbers', 'Upload DNC numbers from a text file', 'Add DNC number(s) manually', 'Price Per Lead', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'NO')";
  $sql_defaults[it] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual) VALUES
                                     ('default', 'it', 'Italiano', '#000000', 'The SmoothTorque Enterprise Predictive Dialing Platform', '/images/logo2.png', 'For further information please email sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJWQIWU', 'localhost', 'root', '', 'phoneDB', 'cdr', 'Home', 'Campagne', 'Numeri', 'DNC Numeri', 'Messaggi', 'Orari', 'Clienti', 'Code', 'Servers', 'Linee telefoniche', 'Amministrazione', 'Logout', '#9999FF', 'Per iniziare, vai nel tuo elenco di campagne facendo clic sulla scheda Campagne nella parte superiore di questa pagina.', 'Nome utente', 'Password', 'Accesso', '€', 'Al minuto', 'YES', null, 'Spare 1 (inutilizzati)', 'Spare 2 (inutilizzati)', 'Spare 3 (inutilizzati)', 'Spare 4 (inutilizzati)', 'Spare 5 (inutilizzati)', 'localhost', 'root', '', 'Aggiungi campagna', 'Visualizza campagne', '200', 'Visualizza i numeri di telefono', 'Utilizzare System elenchi', 'Generare automaticamente il numero', 'Aggiungere manualmente i numeri', 'Carica numeri da un file di testo', 'Esporta i numeri di telefono', 'Ricerca di un numero di telefono', 'Numero della lista di gestione', 'Log di fatturazione', 'Chiama Dettagli', 'YES', 'DNC Numeri', 'Vedere numeri esistenti DNC', 'Cerca DNC numeri', 'Carica DNC numeri da un file di testo', 'Aggiungi DNC numeri manualmente', 'Prezzo per portare', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'YES')";
  $sql_defaults[es] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual) VALUES
                                     ('default', 'es', 'Español', '#000000', 'The SmoothTorque Enterprise Predictive Dialing Platform', '/images/logo2.png', 'For further information please email sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJWQIWU', 'localhost', 'root', '', 'phoneDB', 'cdr', 'Página principal', 'Campañas', 'Números', 'DNC Números', 'Mensajes', 'Listas', 'Clientes', 'Colas', 'Servidores', 'Líneas telefónicas', 'Administración', 'Logout', '#9999FF', 'Para empezar, vaya en su lista de campañas, haga clic en la pestaña Campañas en la parte superior de esta página.', 'Nombre de usuario', 'Contraseña', 'Inicio de sesión', '€', 'Por minuto', 'YES', null, 'Spare 1 (no utilizados)', 'Spare 2 (no utilizados)', 'Spare 3 (no utilizados)', 'Spare 4 (no utilizados)', 'Spare 5 (no utilizados)', 'localhost', 'root', '', 'Añadir Campaña', 'Ver Campañas', '200', 'Ver los números de teléfono', 'Utilice sistema de listas', 'Generar automáticamente los números', 'Añadir manualmente los números de', 'Cargar los números de un archivo de texto', 'Exportación números de teléfono', 'Búsqueda de un número de teléfono', 'Número de la gerencia de la lista', 'Registros de facturación', 'Detalles de las llamadas', 'YES', 'Lista de No Llamar', 'Ver los números de DNC', 'Buscar números DNC', 'Subir DNC números de un archivo de texto', 'Añadir manualmente los números de DNC', 'Precio por plomo', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'YES')";
  foreach ($config_files as $current_language=>$filename) {
      if (file_exists($filename)) {
          $fp = fopen($filename, "r");
          $sql1 = "INSERT INTO web_config (url, LANG, ";
          $sql2 = ") VALUES ('default', '$current_language', ";
          while (!feof($fp)) {
              $line = trim(fgets($fp));
              if (trim($line) && substr($line,0,1)!=$comment) {
                  $pieces = explode("=", $line);
                  $option = trim($pieces[0]);
                  $value = trim($pieces[1]);
                  $config_values_array[$current_language][$option] = $value;
                  if ($option != "TEXT") {
                      $sql1 .=strtolower($option).",";
                      $sql2 .=sanitize($value).",";
                  } else {
                      $sql1 .="contact_text,";
                      $sql2 .=sanitize($value).",";
                  }
              }
          }
          fclose($fp);
          unset($option);
          unset($value);
          unset($fp);
          unset($line);
          $sql = substr($sql1,0,strlen($sql1)-1).substr($sql2,0,strlen($sql2)-1).")";
          mysql_query($sql) or die(mysql_error());
      } else {
          mysql_query($sql_defaults[$current_language]) or die(mysql_error());
      }
  }
}
/* If we have no language set, let's use English - this is mainly because
 * header.php is also called from index.php where we couldn't possibly
 * know the language.
 */
if ((!(isset($_COOKIE[language])))||$_COOKIE[language] == "--") {
    $_COOKIE[language] = "en";
}
/* Same goes for the server name */
if ($_COOKIE[url] == "--") {
    $_COOKIE[url] = $_SERVER[SERVER_NAME];
}

/* Set a variable so we don't need to keep reading the cookies */
$url = $_COOKIE[url];

/* We now have a language and a server name */
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE[language])." AND url = ".sanitize($url)) or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    /* No entry found for this url - use the default */
    $sql = "SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE[language])." AND url = 'default'";
    $result_config = mysql_query($sql) or die("Unable to load config information from mysql: ".mysql_error());
}

if (mysql_num_rows($result_config) == 0) {
    echo "Even though we were sucessful reading the config, it has no values.  Please send an email to smoothtorque@venturevoip.com";
    exit(0);
}

/* Now that we have the config values, put them into the array */
while ($header_row = mysql_fetch_assoc($result_config) ) {
    foreach ($header_row as $key=>$value) {
        if ($key != "contact_text") {
            $config_values[strtoupper($key)] = $value;
        } else {
            $config_values["TEXT"] = $value;
        }
    }
}
unset($key);
unset($value);
unset($result_config);
unset($header_row);

mysql_select_db("SineDialer", $link);

/* Check if the user is logged in */
$loggedin=true;
if (!($_COOKIE["loggedin"]==sha1("LoggedIn".$user))){
    /* The user is not logged in */
    $loggedin=false;
    $myPage=$_SERVER[PHP_SELF];
    if (!($myPage=="/index.php"|$myPage=="/login.php")){
        /* Because header is included in login and the main page we don't
           want to redirect them constantly while they are trying to log
           in.  If they are not on these pages and they are not logged in
           they should be sent to the main page - but we remember via the
           redirect variable the page they were trying to get to. */
        ?><META HTTP-EQUIV=REFRESH CONTENT="0; URL=/index.php?redirect=<?echo $myPage;?>"><?
        exit(0);
    } else {
        $loggedin=false;
    }
}


if ($loggedin) {
    /* Set all the cookies again to extend login time - they're not inactive */
    setcookie("loggedin",sha1("LoggedIn".$user),time()+60000);
    setcookie("user",$user,time()+60000);
    setcookie("level",$level,time()+60000);
    setcookie("language",$language,time()+60000);
    setcookie("url",$_COOKIE[url],time()+60000);

    /* Get the menu structure based on the config values, the current
       page, and the security level of the person viewing the page */
    $menu = get_menu_html($config_values, $self, $level);
}

/* Start printing out the HTML page */
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<TITLE><?echo $config_values['TITLE'];?></TITLE>
<?
/* If we are on one of the realtime graph pages we don't want it to be cached */
if ($self == "/test.php" || $self == "/report.php") {?>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?}?>
<script type="text/javascript" src="/tabber.js"></script>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="stylesheet" href="/example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="/example-print.css" TYPE="text/css" MEDIA="print">
<link rel="stylesheet" type="text/css" href="/css/default.css">
<link rel="shortcut icon" href="/favicon.ico">
<!-- Javascript includes -->
<script type="text/javascript" src="/ajax/picker.js"></script>
<script type="text/javascript" src="/header.js"></script>
</head>

<body bgcolor="<?echo $config_values['COLOUR'];?>" >

<?if (isset($menu) && $loggedin == true){?>
<center><img src="<?echo $config_values['LOGO'];?>">
<?
echo $menu;
flush();
unset($menu);
}?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="tborder3">
<tr valign="TOP" >
<td bgcolor="#ffffff">
<?
if ($loggedin) {
    if (!($config_values['USE_BILLING'] == "YES")) {
        /* The billing system is not enabled so don't bother printing links
           related to credit etc */
        echo "<center>";
        echo "<font color=\"".$config_values['DATE_COLOUR']."\">";
        echo "<a href=\"/help/index.php\">";
        echo "<img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\">";
        echo "<b> Help</b>";
        echo "</a>";
        echo "&nbsp;".ucwords(strftime('%A %d %B %Y %H:%M:%S'));
        echo "</font>";
        echo "</center>";
        echo "<br />";
    } else {
        /* Find out how much credit and what the credit limit is for this
           customer */
        $sql = "SELECT credit, creditlimit from billing where accountcode = 'stl-$_COOKIE[user]'";
        $result_credit = mysql_query($sql,$link);
        if (mysql_num_rows($result_credit)==0){
            /* They have no billing account - set to defaults */
            $credit = $config_values['CURRENCY_SYMBOL']." 0.00";
            $creditlimit = 0;
            $postpay = 0;
        } else {
            /* They have a billing account - set up the variables */
            $credit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result_credit,0,'credit'),2);
            $creditlimit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result_credit,0,'creditlimit'),2);
            $postpay = 1;
        }
        if ($postpay == 1) {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))." Credit: $credit Credit Limit: $creditlimit <a href=\"/viewcdr.php\"><img width=\"16\" height=\"16\" src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img width=\"16\" height=\"16\" src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
        } else {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))." Credit: $credit <a href=\"/viewcdr.php\"><img width=\"16\" height=\"16\"  src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img width=\"16\" height=\"16\"  src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
        }
        unset($result_credit);
        unset($postpay);
        unset($credit);
        unset($creditlimit);
    }
}
?>
