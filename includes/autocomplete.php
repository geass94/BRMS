<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $MySQL,$Security;
if(isset($_POST['menu_search'])){
	$q = $Security->filter_text($_POST['menu_search']);
	$query = $MySQL->query("SELECT * FROM menus WHERE name LIKE '%$q%'");
	$return = [];
	while ($row = mysqli_fetch_assoc($query)) {
		if (isset($row)) $return[] = $Security->return_autocomplete($row);
	}
	echo json_encode($return);
	exit;
}
if(isset($_POST['warehouse_search'])){
	$q = $Security->filter_text($_POST['warehouse_search']);
	$query = $MySQL->query("SELECT DISTINCT id,wid,name,SUM(`quantity`) `QTY`,dimension,AVG(`unit_price`) `AVG`,refill_date,date,edit_date,distributor_id,waybill FROM warehouse WHERE name LIKE '%$q%' GROUP BY wid");
	$return = [];
	while ($row = mysqli_fetch_assoc($query)) {
		if (isset($row)) $return[] = $Security->return_autocomplete($row);
	}
	echo json_encode($return);
	exit;
}
if(isset($_POST['distributor_search'])){
	$q = $Security->filter_text($_POST['distributor_search']);
	$query = $MySQL->query("SELECT * FROM distributors WHERE name LIKE '%$q%'");
	$return = [];
	while ($row = mysqli_fetch_assoc($query)) {
		if (isset($row)) $return[] = $Security->return_autocomplete($row);
	}
	echo json_encode($return);
	exit;
}
?>