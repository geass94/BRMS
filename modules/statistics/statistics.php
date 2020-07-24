<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php

$stats = $_GET['cat'];

if($stats == 'orders')
    include_once("orders.php");
elseif($stats == 'warehouse')
	include_once("warehouse.php");
else
    echo "error";