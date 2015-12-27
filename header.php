<?php

/* Find out what the base directory name is for two reasons:
 *  1. So we can include files
 *  2. So we can explain how to set up things that are missing
 */
$current_directory = dirname(__FILE__);
if (isset($override_directory)) {
    $current_directory = $override_directory;
}
/* What page we are currently on - this is used to highlight the menu
 * system as well as to not cache certain pages like the graphs
 */
$self = $_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
 * custom functions - for more information, read the comments in the
 * functions.php file - most functions are in their own file in the
 * functions subdirectory
 */
require "/".$current_directory."/functions/functions.php";

/* Find out what user the web server is running as - this looks like you
 could also get it from $HTTP_ENV_VARS[APACHE_RUN_USER] but I'm a little
 concerned about the fact it mentions apache - i.e. won't work with
 another server type - this'll have to do for the moment
 */
$whoami = exec('whoami');

/* Get the required Cookies */
if (isset($_COOKIE['language'])) {
    $language = $_COOKIE['language'];
}
if (isset($_COOKIE['user'])) {
    $user = $_COOKIE['user'];
}
if (isset($_COOKIE['level'])) {
    $level = $_COOKIE['level'];
}

/* Make sure we have the environment set up correctly - if not give the
 * user some information about how to remedy the situation - these
 * functions are mainly for the installer
 */
check_for_gd_library();
check_for_upload_settings($current_directory);
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
include "/".$current_directory."/admin/db_config.php";

/* We have to do this early because if it's missing we're going to need */
/* to recreate the information from the files inside the database.      */
/*======================================================================
 web_config
 ======================================================================*/
if (!mysql_is_table($db_host, $db_user, $db_pass, "SineDialer", "web_config")) {

    $sql = "
        CREATE TABLE `web_config` (
        `url` varchar(250) default NULL,
        `LANG` varchar(250) default NULL,
        `language` varchar(250) default NULL,
        `colour` varchar(250) default NULL,
        `title` varchar(250) default NULL,
        `logo` varchar(250) default NULL,
        `contact_text` text,
        `sox` varchar(250) default NULL,
        `userid` varchar(250) default NULL,
        `licence` varchar(250) default NULL,
        `cdr_host` varchar(250) default NULL,
        `cdr_user` varchar(250) default NULL,
        `cdr_pass` varchar(250) default NULL,
        `cdr_db` varchar(250) default NULL,
        `cdr_table` varchar(250) default NULL,
        `menu_home` varchar(250) default NULL,
        `menu_campaigns` varchar(250) default NULL,
        `menu_numbers` varchar(250) default NULL,
        `menu_dnc` varchar(250) default NULL,
        `menu_messages` varchar(250) default NULL,
        `menu_schedules` varchar(250) default NULL,
        `menu_customers` varchar(250) default NULL,
        `menu_queues` varchar(250) default NULL,
        `menu_servers` varchar(250) default NULL,
        `menu_surveys` varchar(250) default NULL,
        `menu_cdr` varchar(250) default NULL,
        `menu_trunks` varchar(250) default NULL,
        `menu_admin` varchar(250) default NULL,
        `menu_logout` varchar(250) default NULL,
        `date_colour` varchar(250) default NULL,
        `main_page_text` text,
        `main_page_username` varchar(250) default NULL,
        `main_page_password` varchar(250) default NULL,
        `main_page_login` varchar(250) default NULL,
        `currency_symbol` varchar(250) default NULL,
        `per_minute` varchar(250) default NULL,
        `use_billing` varchar(250) default NULL,
        `front_page_billing` varchar(250) default NULL,
        `spare1` varchar(250) default NULL,
        `spare2` varchar(250) default NULL,
        `spare3` varchar(250) default NULL,
        `spare4` varchar(250) default NULL,
        `spare5` varchar(250) default NULL,
        `st_mysql_host` varchar(250) default NULL,
        `st_mysql_user` varchar(250) default NULL,
        `st_mysql_pass` varchar(250) default NULL,
        `add_campaign` varchar(250) default NULL,
        `view_campaign` varchar(250) default NULL,
        `per_page` varchar(250) default NULL,
        `numbers_view` varchar(250) default NULL,
        `numbers_system` varchar(250) default NULL,
        `numbers_generate` varchar(250) default NULL,
        `numbers_warning` varchar(250) default NULL,
        `numbers_manual` varchar(250) default NULL,
        `numbers_upload` varchar(250) default NULL,
        `numbers_export` varchar(250) default NULL,
        `numbers_search` varchar(250) default NULL,
        `numbers_title` varchar(250) default NULL,
        `billing_text` varchar(250) default NULL,
        `cdr_text` varchar(250) default NULL,
        `use_generate` varchar(250) default NULL,
        `dnc_numbers_title` varchar(250) default NULL,
        `dnc_view` varchar(250) default NULL,
        `dnc_search` varchar(250) default NULL,
        `dnc_upload` varchar(250) default NULL,
        `dnc_add` varchar(250) default NULL,
        `per_lead` varchar(250) default NULL,
        `smtp_host` varchar(250) default NULL,
        `smtp_user` varchar(250) default NULL,
        `smtp_pass` varchar(250) default NULL,
        `smtp_from` varchar(250) default NULL,
        `use_separate_dnc` varchar(250) default NULL,
        `allow_numbers_manual` varchar(250) default NULL
                                   
        )		  ";
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
    $config_files['en'] = "/stweb.conf";
    $config_files['it'] = "/stweb_it.conf";
    $config_files['es'] = "/stweb_es.conf";
    $config_files['cn'] = "/stweb_cn.conf";
    $sql_defaults['en'] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual,menu_surveys,menu_cdr,numbers_warning) VALUES
        ('default', 'en', 'English', '#ffffff', 'The SmoothTorque Enterprise Predictive Dialing Platform', 'images/00_logo.jpg', 'For further information please email sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJWQIWU', 'localhost', 'admin', 'adminpass', 'phoneDB', 'cdr', 'Home', 'Campaigns', 'Numbers', 'DNC Numbers', 'Messages', 'Schedules', 'Customers', 'Queues', 'Servers', 'Trunks', 'Admin', 'Logout', '#3333FF', 'To get started, go into your list of campaigns by clicking on the Campaigns tab at the top of this page.', 'Username', 'Password', 'Login', '$', 'Per Minute', 'NO', 'NO', 'Spare 1 (unused)', 'Spare 2 (unused)', 'Spare 3 (unused)', 'Spare 4 (unused)', 'Spare 5 (unused)', 'localhost', 'root', '', 'Add Campaign', 'View Campaigns', '200', 'View phone numbers', 'Use System Lists', 'Generate numbers automatically', 'Add number(s) manually', 'Upload numbers from a text file', 'Export Phone Numbers', 'Search for a phone number', 'Number List Management', 'Billing Logs', 'Call Details', 'YES', 'Do Not Call List', 'View existing DNC numbers', 'Search DNC numbers', 'Upload DNC numbers from a text file', 'Add DNC number(s) manually', 'Price Per Lead', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'NO', 'Surveys','CDR','Number Exhaustion Warning Emails')";
    $sql_defaults['it'] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual,menu_surveys,menu_cdr,numbers_warning) VALUES
        ('default', 'it', 'Italiano', '#ffffff', 'The SmoothTorque Enterprise Predictive Dialing Platform', 'images/00_logo.jpg', 'For further information please email sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJWQIWU', 'localhost', 'root', '', 'phoneDB', 'cdr', 'Home', 'Campagne', 'Numeri', 'DNC Numeri', 'Messaggi', 'Orari', 'Clienti', 'Code', 'Servers', 'Linee telefoniche', 'Amministrazione', 'Logout', '#9999FF', 'Per iniziare, vai nel tuo elenco di campagne facendo clic sulla scheda Campagne nella parte superiore di questa pagina.', 'Nome utente', 'Password', 'Accesso', '�', 'Al minuto', 'NO', null, 'Spare 1 (inutilizzati)', 'Spare 2 (inutilizzati)', 'Spare 3 (inutilizzati)', 'Spare 4 (inutilizzati)', 'Spare 5 (inutilizzati)', 'localhost', 'root', '', 'Aggiungi campagna', 'Visualizza campagne', '200', 'Visualizza i numeri di telefono', 'Utilizzare System elenchi', 'Generare automaticamente il numero', 'Aggiungere manualmente i numeri', 'Carica numeri da un file di testo', 'Esporta i numeri di telefono', 'Ricerca di un numero di telefono', 'Numero della lista di gestione', 'Log di fatturazione', 'Chiama Dettagli', 'YES', 'DNC Numeri', 'Vedere numeri esistenti DNC', 'Cerca DNC numeri', 'Carica DNC numeri da un file di testo', 'Aggiungi DNC numeri manualmente', 'Prezzo per portare', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'YES', 'Surveys','CDR','Number Exhaustion Warning Emails')";
    $sql_defaults['es'] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual,menu_surveys,menu_cdr,numbers_warning) VALUES
        ('default', 'es', 'Espa�ol', '#ffffff', 'The SmoothTorque Enterprise Predictive Dialing Platform', 'images/00_logo.jpg', 'For further information please email sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJWQIWU', 'localhost', 'root', '', 'phoneDB', 'cdr', 'P�gina principal', 'Campa�as', 'N�meros', 'DNC N�meros', 'Mensajes', 'Listas', 'Clientes', 'Colas', 'Servidores', 'L�neas telef�nicas', 'Administraci�n', 'Logout', '#9999FF', 'Para empezar, vaya en su lista de campa�as, haga clic en la pesta�a Campa�as en la parte superior de esta p�gina.', 'Nombre de usuario', 'Contrase�a', 'Inicio de sesi�n', '�', 'Por minuto', 'NO', null, 'Spare 1 (no utilizados)', 'Spare 2 (no utilizados)', 'Spare 3 (no utilizados)', 'Spare 4 (no utilizados)', 'Spare 5 (no utilizados)', 'localhost', 'root', '', 'A�adir Campa�a', 'Ver Campa�as', '200', 'Ver los n�meros de tel�fono', 'Utilice sistema de listas', 'Generar autom�ticamente los n�meros', 'A�adir manualmente los n�meros de', 'Cargar los n�meros de un archivo de texto', 'Exportaci�n n�meros de tel�fono', 'B�squeda de un n�mero de tel�fono', 'N�mero de la gerencia de la lista', 'Registros de facturaci�n', 'Detalles de las llamadas', 'YES', 'Lista de No Llamar', 'Ver los n�meros de DNC', 'Buscar n�meros DNC', 'Subir DNC n�meros de un archivo de texto', 'A�adir manualmente los n�meros de DNC', 'Precio por plomo', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'YES', 'Surveys','CDR','Number Exhaustion Warning Emails')";


    $sql_defaults['cn'] = "INSERT INTO web_config (url, LANG, language,colour,title,logo,contact_text,sox,userid,licence,cdr_host,cdr_user,cdr_pass,cdr_db,cdr_table,menu_home,menu_campaigns,menu_numbers,menu_dnc,menu_messages,menu_schedules,menu_customers,menu_queues,menu_servers,menu_trunks,menu_admin,menu_logout,date_colour,main_page_text,main_page_username,main_page_password,main_page_login,currency_symbol,per_minute,use_billing,front_page_billing,spare1,spare2,spare3,spare4,spare5,st_mysql_host,st_mysql_user,st_mysql_pass,add_campaign,view_campaign,per_page,numbers_view,numbers_system,numbers_generate,numbers_manual,numbers_upload,numbers_export,numbers_search,numbers_title,billing_text,cdr_text,use_generate,dnc_numbers_title,dnc_view,dnc_search,dnc_upload,dnc_add,per_lead,smtp_host,smtp_user,smtp_pass,smtp_from,use_separate_dnc,allow_numbers_manual,menu_surveys,menu_cdr,numbers_warning) VALUES
        ('default', 'cn', 'Chinese', '#ffffff', '预测拨号的SmoothTorque企业平台', 'images/00_logo.jpg', '如需进一步资料，请电邮sales@venturevoip.com', '/usr/bin/sox', 'VentureVoIP', 'DRFHUJFWQIWU', 'localhost', 'admin', 'adminpass', 'phoneDB', 'cdr', '首页', '运动', '电话号码', '电话号码', '声音文件', '附表', '客户', '队列', '服务器', '电话线', '设置', '注销', '#3333FF', '要开始，到您的广告系列列表去通过点击广告系列标签在本页面顶部', '用户名', '密码', '注册', '$', '每分钟', 'NO', 'NO', '备用1（未使用）', '备用2（未使用）', '备用3（未使用）', '备用4（未使用）', '备用5（未使用）', 'localhost', 'root', '', '新增广告系列', '查看广告系列', '200', '查看电话号码', '使用系统列表', '自动生成号码', '手动添加号码', '从一个文本文件上传号码', '出口电话号码', '搜寻电话号码', '编号列表管理', '计费日志', '通话详情', 'YES', '不通话清单', '查看现有的电话号码不', '搜索不呼叫号码', '上传不来电号码从文本文件', '不添加人工呼叫号码', '单价铅', 'localhost', '', '', 'matt@venturevoip.com', 'NO', 'NO', 'Surveys','CDR','Number Exhaustion Warning Emails')";


    foreach ($config_files as $current_language => $filename) {
        if (file_exists($filename) && filesize($filename) > 0) {
            $fp = fopen($filename, "r");
            $sql1 = "INSERT INTO web_config (url, LANG, ";
            $sql2 = ") VALUES ('default', '$current_language', ";
            while (!feof($fp)) {
                $line = trim(fgets($fp));
                if (trim($line) && substr($line, 0, 1) != $comment) {
                    $pieces = explode("=", $line);
                    $option = trim($pieces[0]);
                    $value = trim($pieces[1]);
                    $config_values_array[$current_language][$option] = $value;
                    if ($option != "TEXT") {
                        $sql1 .= strtolower($option).",";
                        $sql2 .= sanitize($value).",";
                    } else {
                        $sql1 .= "contact_text,";
                        $sql2 .= sanitize($value).",";
                    }
                }
            }
            fclose($fp);
            unset($option);
            unset($value);
            unset($fp);
            unset($line);
            $sql = substr($sql1, 0, strlen($sql1) - 1).substr($sql2, 0, strlen($sql2) - 1).")";
            mysql_query($sql) or die(mysql_error());
        } else {
            echo "Creating default configs for $current_language<br />";
            mysql_query($sql_defaults[$current_language]) or die(mysql_error());
        }
    }
}
/* If we have no language set, let's use English - this is mainly because
 * header.php is also called from index.php where we couldn't possibly
 * know the language.
 */
if ((!(isset($_COOKIE['language']))) || $_COOKIE['language'] == "--") {
    $_COOKIE['language'] = "en";
}
/* Same goes for the server name */
if (!isset($_COOKIE['url']) || $_COOKIE['url'] == "--") {
    $_COOKIE['url'] = $_SERVER['SERVER_NAME'];
}

/* Set a variable so we don't need to keep reading the cookies */
$url = $_COOKIE['url'];

/* We now have a language and a server name */
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE['language'])." AND url = ".sanitize($url)) or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    /* No entry found for this url - use the default */
    $sql = "SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE['language'])." AND url = 'default'";
    $result_config = mysql_query($sql) or die("Unable to load config information from mysql: ".mysql_error());
}

if (mysql_num_rows($result_config) == 0) {
    echo "Even though we were sucessful reading the config, it has no values.  Please send an email to smoothtorque@venturevoip.com";
    exit(0);
}

/* Now that we have the config values, put them into the array */
while ($header_row = mysql_fetch_assoc($result_config)) {
    foreach ($header_row as $key => $value) {
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

if (!isset($config_values['MENU_SURVEY'])) {
    $config_values['MENU_SURVEY'] = "Surveys";
}

if (!isset($config_values['MENU_CDR'])) {
    $config_values['MENU_CDR'] = "CDR";
}

/* Check all connections are ok */
create_missing_tables($db_host, $db_user, $db_pass);

$sql = 'SELECT value FROM config WHERE parameter=\'use_new_pie\'';
$result = @mysql_query($sql, $link);
$use_new_pie = 0;
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $use_new_pie = mysql_result($result, 0, 'value');
    }
}
$sql = 'SELECT value FROM config WHERE parameter=\'SHOW_NUMBERS_LEFT\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['SHOW_NUMBERS_LEFT'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'brand\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['brand'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'DELETE_ALL\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['DELETE_ALL'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'LEAVE_PRESS1\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['LEAVE_PRESS1'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'EVERGREEN\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['EVERGREEN'] = mysql_result($result, 0, 'value');
} else {
    $config_values['EVERGREEN'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'CDR_USE_STATE\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['CDR_USE_STATE'] = mysql_result($result, 0, 'value');
} else {
    $config_values['CDR_USE_STATE'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'NUMBER_EXHAUSTION\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['NUMBER_EXHAUSTION'] = mysql_result($result, 0, 'value');
} else {
    $config_values['NUMBER_EXHAUSTION'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'DISABLE_RECYCLE_ALL\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['DISABLE_RECYCLE_ALL'] = mysql_result($result, 0, 'value');
} else {
    $config_values['DISABLE_RECYCLE_ALL'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'DISABLE_MESSAGE_UPLOAD\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['DISABLE_MESSAGE_UPLOAD'] = mysql_result($result, 0, 'value');
} else {
    $config_values['DISABLE_MESSAGE_UPLOAD'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'DISABLE_SURVEYS\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['DISABLE_SURVEYS'] = mysql_result($result, 0, 'value');
} else {
    $config_values['DISABLE_SURVEYS'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'USE_TIMEZONES\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['USE_TIMEZONES'] = mysql_result($result, 0, 'value');
} else {
    $config_values['USE_TIMEZONES'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'MENU_TIMEZONES\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['MENU_TIMEZONES'] = mysql_result($result, 0, 'value');
} else {
    $config_values['MENU_TIMEZONES'] = "Timezones";
}

$sql = 'SELECT value FROM config WHERE parameter=\'MENU_SURVEYS\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['MENU_SURVEYS'] = mysql_result($result, 0, 'value');
} else {
    $config_values['MENU_SURVEYS'] = "Surveys";
}


$sql = 'SELECT value FROM config WHERE parameter=\'sugar_user\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_USER'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_host\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_HOST'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_pass\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_PASS'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_db\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_DB'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'vm_0_5\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['vm_0_5'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'vm_6_10\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['vm_6_10'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'vm_11_20\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['vm_11_20'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'vm_21_30\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['vm_21_30'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'vm_31_60\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['vm_31_60'] = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'configurable_drive\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['configurable_drive'] = mysql_result($result, 0, 'value');
} else {
    $config_values['configurable_drive'] = "NO";
}

$sql = 'SELECT value FROM config WHERE parameter=\'configurable_target\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['configurable_target'] = mysql_result($result, 0, 'value');
} else {
    $config_values['configurable_target'] = "0";
}

$sql = 'SELECT value FROM config WHERE parameter=\'cdr_workaround\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['cdr_workaround'] = mysql_result($result, 0, 'value');
} else {
    $config_values['cdr_workaround'] = "0";
}

$sql = 'SELECT value FROM config WHERE parameter=\'test_number\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['test_number'] = mysql_result($result, 0, 'value');
} else {
    $config_values['test_number'] = "";
}

$sql = 'SELECT value FROM config WHERE parameter=\'disable_all_types\'';
$result = mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['disable_all_types'] = mysql_result($result, 0, 'value');
} else {
    $config_values['disable_all_types'] = "";
}


mysql_select_db("SineDialer", $link);

/* Check if the user is logged in */
$loggedin = TRUE;
$myPage = $_SERVER['PHP_SELF'];
//echo $myPage;
$url_split = explode("/", $myPage);
//echo sizeof($url_split);
$help = FALSE;
foreach ($url_split as $entry) {
    if ($entry == "help") {
        $help = TRUE;
    }
}
$myPage = "/".$url_split[sizeof($url_split) - 1];
if ($myPage != $_SERVER['PHP_SELF']) {
    // This website is being served from a subdirectory
    $http_dir_name = substr($_SERVER['PHP_SELF'], 0, strlen($_SERVER['PHP_SELF']) - strlen($myPage))."/";
    $self = $myPage;
} else {
    $http_dir_name = "/";
}
//print_pre($page);
//exit(0);
if (!isset($_COOKIE['loggedin']) || !($_COOKIE["loggedin"] == sha1("LoggedIn".$user))) {
    /* The user is not logged in */
    $loggedin = FALSE;
    if (!($myPage == "/index.php" || $myPage == "index.php" || $myPage == "login.php" || $myPage == "/login.php")) {
        /* Because header is included in login and the main page we don't
         want to redirect them constantly while they are trying to log
         in.  If they are not on these pages and they are not logged in
         they should be sent to the main page - but we remember via the
         redirect variable the page they were trying to get to. */
        ?>
        <META HTTP-EQUIV=REFRESH CONTENT="0; URL=<?= $http_dir_name ?>index.php?redirect=<? echo $myPage; ?>"><?
        exit(0);
    } else {
        $loggedin = FALSE;
    }
} else {
    //echo $myPage;exit(0);
    if (!$help && $myPage == "/index.php") {
        /* If they are already logged in, but are viewing the index.php page then we  */
        /* need to redirect them to the first page - i.e. main.php depending on their */
        /* interface type.                                                            */
        /* Try to load the password information from the database for that user */
        $sql = 'SELECT interface_type FROM customer WHERE username=\''.$user.'\'';
        $result = mysql_query($sql, $link);
        if (mysql_num_rows($result) > 0) {
            $interface_type = mysql_result($result, 0, 'interface_type');
        } else {
            $interface_type = "default";
        }
        if ($interface_type == "broadcast") {
            /* Redirect to the broadcast interface */
            $destination = "".$http_dir_name."/main.php";
        } else if (0 && $interface_type == "cc") {
            /* Redirect to the call centre interface */
            $destination = "".$http_dir_name."modules/cc/main.php";
        } else {
            /* Redirect to the default interface */
            $destination = $http_dir_name."main.php";
        }
        ?>
        <META HTTP-EQUIV=REFRESH CONTENT="0; URL=<?= $destination ?>"><?
        exit(0);
    }
}


if ($loggedin) {
    /* Set all the cookies again to extend login time - they're not inactive */
    setcookie("loggedin", sha1("LoggedIn".$user), time() + 60000, "/");
    setcookie("user", $user, time() + 60000, "/");
    setcookie("level", $level, time() + 60000, "/");
    setcookie("language", $language, time() + 60000, "/");
    setcookie("url", $_COOKIE['url'], time() + 60000, "/");

    /* Get the menu structure based on the config values, the current
     page, and the security level of the person viewing the page */
    if ($config_values['disable_all_types'] == "YES") {
        $menu = get_bootstrap_menu($config_values, $self, $level, 1);
    } else {
        $menu = get_menu_html($config_values, $self, $level, 1);
    }

}

/* Start printing out the HTML page */
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <? /*<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">*/ ?>
    <TITLE><? echo stripslashes(stripslashes($config_values['TITLE'])); ?></TITLE>
    <style>
        @media (max-width: 978px) {
            .jumbotron {
                padding: 40px;
            }
        }

        /*noinspection CssUnusedSymbol*/
        .table tbody > tr > td.vert-align {
            vertical-align: middle;
        }
    </style>
    <?
    /* If we are on one of the realtime graph pages we don't want it to be cached */
    if ($self == "/test.php" || $self == "/report.php" || $self == "/servers.php" || $self == "/mysql_stats.php" || $self == "/config.php") { ?>
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <META HTTP-EQUIV="Expires" CONTENT="-1">
        <script type="text/javascript" src="js/range.js"></script>
        <script type="text/javascript" src="js/timer.js"></script>

        <script type="text/javascript" src="js/slider.js"></script>
    <? } ?>
    <script type="text/javascript" src="<?= $http_dir_name ?>tabber.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- Latest compiled and minified JavaScript -->
    <script type="text/javascript" src="js/jquery.js"></script>

    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="js/jquery.flot.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/validator.js"></script>
    <?
    if ($loggedin) {
        $result_if = mysql_query("SELECT interface_type FROM customer where username = '$_COOKIE[user]'");
        if (!$result_if) { ?>
            <META HTTP-EQUIV=REFRESH CONTENT="0; URL=logout.php"><?
            exit(0);
        }
        if (mysql_num_rows($result_if) > 0) {
            $interface_type = mysql_result($result_if, 0, 0);
        }
    }

    ?>
    <link rel="stylesheet" type="text/css" href="<?= $http_dir_name ?>css/style.css?version=3">
    <link rel="stylesheet" href="<?= $http_dir_name ?>example.css?version=3" TYPE="text/css" MEDIA="screen">
    <? /*<link rel="stylesheet" href="<?=$http_dir_name?>example-print.css" TYPE="text/css" MEDIA="print">*/ ?>
    <link rel="stylesheet" type="text/css" href="<?= $http_dir_name ?>css/default.css?version=3">
    <link rel="shortcut icon" href="<?= $http_dir_name ?>favicon.ico">
    <!-- Javascript includes -->
    <script type="text/javascript" src="<?= $http_dir_name ?>ajax/picker.js"></script>
    <script type="text/javascript" src="<?= $http_dir_name ?>header.js"></script>
</head>

<body bgcolor="<? echo $config_values['COLOUR']; ?>">
<?
if ($config_values['disable_all_types'] == "YES") {
    echo '<div class="container">';
} else {
    echo '<div class="container-fluid">';
}

if (isset($menu) && $loggedin == TRUE) {
    $sql = 'SELECT value FROM config WHERE parameter=\'logo_width\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $logo_width = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'logo_height\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $logo_height = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'use_names\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['use_names'] = mysql_result($result, 0, 'value');
    }


    ?>
    <center><img class="img-responsive" src="./<? echo $config_values['LOGO']; ?>"<?
        if ($logo_height > 0) {
            echo ' height="'.$logo_height.'"';
        }
        if ($logo_width > 0) {
            echo ' width="'.$logo_width.'"';
        }
        ?>></center>
    <?


    echo $menu;
    flush();
    unset($menu);

    if ($loggedin) {
        if (!($config_values['USE_BILLING'] == "YES")) {

        } else {
            /* Find out how much credit and what the credit limit is for this
             customer */
            $sql = "SELECT credit, creditlimit from billing where accountcode = 'stl-$_COOKIE[user]'";
            $result_credit = mysql_query($sql, $link);
            box_start();
            if (mysql_num_rows($result_credit) == 0) {
                /* They have no billing account - set to defaults */
                $credit = $config_values['CURRENCY_SYMBOL']." 0.00";
                $creditlimit = 0;
                $postpay = 0;
            } else {
                /* They have a billing account - set up the variables */
                $credit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result_credit, 0, 'credit'), 2);
                $creditlimit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result_credit, 0, 'creditlimit'), 2);
                $postpay = 1;
            }
            if ($postpay == 1) {
                echo 'Credit: '.$credit.' Credit Limit: $creditlimit <a href="'.$http_dir_name.'"viewcdr.php" class="btn btn-primary"><i class="glyphicon glyphicon-phone-alt"></i> '.$config_values['CDR_TEXT'].'</a> <a href="'.$http_dir_name.'billinglog_account.php" class="btn btn-primary"><i class="glyphicon glyphicon-list"></i> '.$config_values['BILLING_TEXT'].'</a></font><br /></center>';

            } else {
                echo 'Credit: '.$credit.' <a href="'.$http_dir_name.'viewcdr.php" class="btn btn-success"><i class="glyphicon glyphicon-phone-alt"></i> '.$config_values['CDR_TEXT'].'</a> <a href="'.$http_dir_name.'billinglog_account.php" class="btn btn-success"><i class="glyphicon glyphicon-list"></i>  '.$config_values['BILLING_TEXT'].'</a></font><br /></center>';
            }
            unset($result_credit);
            unset($postpay);
            unset($credit);
            unset($creditlimit);
            box_end();
        }
    }

}
?>
</div>
<div class="container">

