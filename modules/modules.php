<?php
if (!defined("PROTECT")) {
    exit("<h3 style=\"color:#FF0000;\">STOP!</h3>");
}
$right_tables = "ON"; // Default aris ON

function getMod() {
    global $MySQL;
    global $Security;
    global $right_tables;
    global $mGET;
    global $lang;
    if(isset($_GET['cat'])) { $cGET = $_GET['cat']; } else { unset($cGET); } // category GET
    if (isset($mGET)) {
        // Shemocmeba
        if ($Security->checkParam("mGET", $mGET) === true) {
            if (file_exists(MOD_DIR . $mGET . "/" . $mGET . ".php")) {
                require_once (MOD_DIR . $mGET . "/" . $mGET . ".php");
            } else {
                require_once (MOD_DIR . "notmod.php"); // tu shemocmeba arascoria
            }
        } else {
            require_once (MOD_DIR . "notmod.php"); // tu moduli (faili) ar arsebobs
        }
    }
     else {
		unset($mGET);
    }
} // funqciis dasasruli
?>

