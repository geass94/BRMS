<?php
if (!defined("PROTECT")) {
    exit("<h3 style=\"color:#FF0000;\">STOP!</h3>");
}
// DEFAULT_MOD aris modoli, romelic mtavar gverdze gamochndeba shesvlisas.
// defaultad aris news modi, anu siaxleebis modi.
//
$right_tables = "ON"; // Default aris ON

//-- Modulis gamotanis funqcia
function getMod() {
    global $MySQL;
    global $Security;
    global $right_tables;
    global $mGET;
   # global $id;
   # global $user;
    if (isset($mGET)) {
        // Shemocmeba
        if ($Security->checkParam("mGET", $mGET) === true) {
            if (file_exists(ADMMOD_DIR . $mGET . "/" . $mGET . ".php")) {
                require_once (ADMMOD_DIR . $mGET . "/" . $mGET . ".php");
            } else {
                require_once (ADMMOD_DIR . "notmod.php"); // tu shemocmeba arascoria
            }
        } else {
            require_once (ADMMOD_DIR . "notmod.php"); // tu moduli (faili) ar arsebobs
        }
    }
     else {
		unset($mGET);
    }
} // funqciis dasasruli
?>