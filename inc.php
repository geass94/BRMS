<?php
$time = microtime(); $time = explode(' ', $time); $time = $time[1] + $time[0]; $start = $time;
ob_start();
session_start();
$_SESSION['start'] = true;
define("PROTECT", true);
date_default_timezone_set('Asia/Muscat');

$using_ie6 = (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.') !== FALSE);
if($using_ie6) {echo "Please Update Your Browser <br />Use Google Chrome For best View";exit;}

require("config.inc.php");
require_once (CLS_DIR."main.class.php");
// mtavari klasi, saidanac xdeba sxva klasebis chatvirtva failshi
$mainClass = new mainClass();
$mainClass->loadClass("MySQL");
$mainClass->loadClass("Security");
##$mainClass->loadClass("Mail");
$Security = new Security();
$MySQL = new MySQL();
$MySQL->connect();
$MySQL->query("SET CHARACTER SET utf8");
$MySQL->query("SET NAMES 'utf8'");
$ip = $_SERVER['REMOTE_ADDR'];

require_once(LANG_DIR."english/eng.php");

require_once (INC_DIR."functions.inc.php");

require_once (INC_DIR."/functions/login.php");

require_once (INC_DIR."pre.inc.php");

require_once (INC_DIR."engine.php");

require_once(INC_DIR."autocomplete.php");

require_once(INC_DIR."api/api.php");

require_once(INC_DIR."export.php");

?>