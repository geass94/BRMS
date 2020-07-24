<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }

//-- MySQL konfiguracia
define("MYSQL_HOST", "127.0.0.1"); // Hosti (localhost)
define("MYSQL_USER", "root"); // Saxeli
define("MYSQL_PASS", "root1234"); // Paroli
define("MYSQL_DATABASE", "brms"); // Monacemta baza

//-- Saitis sxva da sxva konfiguracia
define("SITE", "http://localhost"); // Saitis misamarti
define("SSITE", "http://localhost"); // SSL Saitis misamarti

//-- Direqtoriebis misamartebi
define("TPL_DIR", "templates/");
define("MOD_DIR", "modules/");
define("CLS_DIR", "class/");
define("INC_DIR", "includes/");
define("LANG_DIR", "languages/");
define("ADMIN_DIR", "admin/");
define("ADMMOD_DIR", "admin/modules/");
define("DEFAULT_MOD","modules/startpage/startpage.php");
define("MAIN_DIR", "E:\xampp\htdocs");
?>
