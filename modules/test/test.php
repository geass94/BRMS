<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container main-container">
<?php
global $MySQL;
$text = "[ხახვი,1,ცალი]|[კვერცხი,2,ცალი]|[კარაქი,0.05,კგ]|[მარილი,,კგ]|[,,]";
$text = explode("|", $text);
$size = count($text);
$ret = "";
for($i = 0; $i < $size; $i++){
$text[$i] = str_replace("[", "", $text[$i]);
$text[$i] = str_replace("]", "", $text[$i]);
$ings = explode(",", $text[$i]);
$ret .= "<p>".$ings[0]." ".$ings[1]." ".$ings[2]."</p>";
}
//echo $ret;
$query = $MySQL->query("SELECT * FROM warehouse");
$row = mysqli_fetch_assoc($query);
$rows = mysqli_num_rows($query);
$array = array();
foreach ($row as $key => $value) {
	$array[$key] = $value;
}
for($i = 0; $i < $rows; $i++){
	//echo $i."<br>";
}
/*
$menu = get_menu_item($row['menu_item_id']);
$module = "orders";
$array = array(
			"action"=>"add",
			"id"=>cin(cout($row['id'])),
			"table_id"=>cin(cout($row['table_id'])),
			"session_id"=>cin(cout($row['session_id'])),
			"menu_item_id"=>cin(cout($row['menu_item_id'])),
			"menu_item"=>cin(cout($menu["name"])),
			"sell_price"=>cin(cout($menu["sell_price"])),
			"quantity"=>cin(cout($row['quantity'])),
			"time"=>$row['order_time']
		);
history($module,$array,$row['order_time']);


	$array = array(
				"id"=>cin(cout($row['id'])),
				"wid"=>cin(cout($row['wid'])),
				"name"=>cin(cout($row['name'])),
				"quantity"=>cin(cout($row['quantity'])),
				"dimension"=>cin(cout($row['dimension'])),
				"total_price"=>($row['quantity']*$row['unit_price']),
				"unit_price"=>cin(cout($row['unit_price'])),
				"dist_id"=>cin(cout($row['distributor_id'])),
				"waybill"=>cin(cout($row['waybill'])),
				"refill_date"=>$row['refill_date']
			);
	history('warehouse',$array,$row['refill_date']);

*/
	$role = array("ROLE_ADMIN");
	foreach($role as $key=>$value){
		echo $key;
	}

$cookie_name = "user";
$cookie_value = "John Doeson";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

if(!isset($_COOKIE[$cookie_name])) {
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$cookie_name];
}
print_r(unserialize('a:2:{i:0;s:1:"2";i:1;s:1:"5";}'));
?>
</div>