<?
session_start();

define('SESSION_ID',session_id());

$_LOG_STR = DATE("Y-m-d H:i:s")."|".SESSION_ID."|".$_SERVER['REMOTE_ADDR']."|";
$_TO_DAY = date("Y-m-d");//금일
$_YESTERDAY = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")));	//전일
$_LAST_MONTH = date("Y-m-d", mktime(0,0,0,date("m")-1,1,date("Y")));	//전월
$_LAST_MONTH_LAST_DAY = date("Y-m-d", mktime(0,0,0,date("m")-1,(int)date("t",mktime(0,0,0,date("m")-1,1,date("Y"))),date("Y")));	//전월말일
$_TO_MONTH = date("Y-m-d", mktime(0,0,0,date("m"),1,date("Y")));	//금월
$_LAST_WEEK = date("Y-m-d",strtotime("last Sunday", time()));	//전주
$_LAST_WEEK_LAST_DAY = date("Y-m-d",strtotime("last Saturday", time()));	//전주일요일
$_TO_WEEK = date("Y-m-d",strtotime("Sunday", time()));//금주
$_TO_WEEK_LAST_DAY = date("Y-m-d",strtotime("Saturday", time()));//금주일요일
//echo (int)date("t",mktime(0,0,0,2,1,2011)); //말일

define('SITENAME',"coin23");
define('WEB_TITLE',"가상화폐 국제거래소");
define('MAGICPASSWD',"547d6f9d4d15a22bae6c84cc3ca20dbe");
define('HOME_DIR',$_SERVER['DOCUMENT_ROOT']."/");
define('ADM_DIR',HOME_DIR."/Admin");
define('DATA_DIR',$_SERVER['DOCUMENT_ROOT']."/data/uploads");

define('HOME',"/");

define('SMTP_SERVER',"smart.whoismail.net");
define('SMTP_PORT',"587");
define('SMTP_USER',"master@kodaq.io");
define('SMTP_PASS',"kodaq2017!");
?>
