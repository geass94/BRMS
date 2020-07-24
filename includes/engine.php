<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }
if(isset($_POST['login']) && !empty($_POST['login'])){

if($_POST['login'] == 'provider'){
	
}
else{
	$username = cin($_POST['username']);
	$password = $_POST['password'];
	$secret = cin($_POST['secret']);
	$ret = login($username,$password,$secret);
	if($ret == true){
		//header("Location:?mod=tables");
		echo "login_success";
	}else{
		echo "login_error";
	}
	exit;
}

}

if(isset($_POST['form_type']) && $_POST['form_type'] == 'company_register'){
	global $MySQL, $Security;
	$username = cin($_POST['username']);
	$email = cin($_POST['email']);
	$options = ['cost' => 12];
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
	$photo = $_FILES['avatar']['name'];
	$activation_code = createcode();
	$ip = $_SERVER['REMOTE_ADDR'];
	$secret = $_POST['secret'];
	$plan = $_POST['plan'];
	$role = $_POST['role'];
	$MySQL->query("INSERT INTO users (username,email,password,approved,activation_code,reg_ip,reg_date,secret,role,plan) VALUES ('$username','$email','$password',0,'$activation_code','$ip',now(),'$secret','$role','$plan') ");
	$id = $MySQL->insertId();
	mkdir("data/users/".$id, 0700);
	if (!empty($photo)) {
		$extension = substr(strrchr($photo, '.'), 1);
		$extension = strtolower($extension);
		$name2 = md5(date("Ymdhis"));
		$new_image = $name2 . "1." . $extension;
		$destination="data/users/".$id."/".$new_image;
		$action = copy($_FILES['avatar']['tmp_name'], $destination);
		$MySQL->query("UPDATE users SET avatar='$new_image' WHERE id = $id");
	}else{
		$MySQL->query("UPDATE users SET avatar='default_avatar.png' WHERE id = $id");
	}
	header("Location: /admin.php?mod=companies&cat=edit&id=".$id);
	exit;
}

if(isset($_POST['form_type']) && $_POST['form_type'] == 'user_register'){
	global $MySQL, $Security;
	$username = cin($_POST['username']);
	$email = cin($_POST['email']);
	$options = ['cost' => 12];
	$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
	$photo = $_FILES['avatar']['name'];
	$activation_code = createcode();
	$ip = $_SERVER['REMOTE_ADDR'];
	$secret = cin($_POST['secret']);
	$role = $_POST['role'];
	$MySQL->query("INSERT INTO users (username,email,password,approved,activation_code,reg_ip,reg_date,secret,role) VALUES ('$username','$email','$password',0,'$activation_code','$ip',now(),'$secret','$role') ");
	$id = $MySQL->insertId();
	mkdir("data/users/".$id, 0700);
	if (!empty($photo)) {
		$extension = substr(strrchr($photo, '.'), 1);
		$extension = strtolower($extension);
		$name2 = md5(date("Ymdhis"));
		$new_image = $name2 . "1." . $extension;
		$destination="data/users/".$id."/".$new_image;
		$action = copy($_FILES['avatar']['tmp_name'], $destination);
		$MySQL->query("UPDATE users SET avatar='$new_image' WHERE id = $id");
	}else{
		$MySQL->query("UPDATE users SET avatar='default_avatar.png' WHERE id = $id");
	}
	header("Location: /admin.php?mod=users&cat=edit&id=".$id);
	exit;
}

if(isset($_POST['form_type']) && $_POST['form_type'] == 'customer_activation'){
	global $MySQL;
	$userid = cin(intval($_POST['user_id']));
	$code = cin(intval($_POST['activation_code']));
	$query = $MySQL->query("UPDATE users SET approved = 1, activation_date = now() WHERE id = $userid AND activation_code = '$code'");
	header("Location:?mod=login");
	exit;
}

if(isset($_GET['logout'])){
	$ret = logout();
	if($ret == "success")
		header("Location:?mod=startpage");
	exit;
}
/* THIS PAGE LOADS ORDERS MODAL BOX DATA */
include_once("orders.php");
/* THIS PAGE LOADS ORDERS MODAL BOX DATA */
if(isset($_POST['enter_reserved_code']) && $_POST['enter_reserved_code'] == date("Ymd")){
	$id = intval($_POST['table_id']);
	$session_id = cin($_POST['session_id']);
	$code = intval($_POST['reserver_code']);
	$query = $MySQL->query("SELECT reserved_time FROM tables WHERE id = $id AND (isnull(session_id) OR session_id = '$session_id') AND reserver_code = $code");
	if(mysqli_num_rows($query) == 1){
		$update = $MySQL->query("UPDATE tables SET state = 1, reserved_time = '', reserver_code = '' WHERE id = $id AND (isnull(session_id) OR session_id = '$session_id')");
			if($update === true)
				echo "success";
			else
				echo "failure";

			create_session($id,date('YmdHis'));
	}
	else{
		$MySQL->query("UPDATE tables SET state = 2, session_id = '' WHERE id = $id AND (isnull(session_id) OR session_id = '$session_id')");
		echo "failure";
	}
	exit;
}

if(isset($_POST['add_order'])){
	$table_id = cin(intval($_POST['add_order']));
	$session_id = cin($_POST['session_id']);;
	$mid = cin(intval($_POST['menu_item_id']));
	$qty = cin(intval($_POST['quantity']));
	$time = date("Y-m-d h:i:s");
	$desc = cin($_POST['desc']);
	$table = get_table_details($table_id);
	$servant_id = $table['servant_id'];
	$insert = $MySQL->query("INSERT INTO orders (menu_item_id,quantity,order_time,table_id,session_id,servant_id,description) VALUES ($mid,$qty,'$time',$table_id,'$session_id',$user_id,'$desc')");
	$id = $MySQL->insertId();
	$menu = get_menu_item($mid);
?>
	<tr id="order-<?=$id?>">
		<td><?php echo $menu['name'];?></td>
		<td><?php echo $menu['sell_price'];?></td>
		<td><?php echo $qty;?></td>
		<td><?php echo $qty*$menu['sell_price'];?></td>
		<td><button module="order" onclick="delete_item('order','<?=$id?>')" id="delete-<?=$id?>" class="btn btn-danger btn-sm"><?=$lang['delete']?></button></td>
	</tr>
<?php
	exit;
}

/* DELETE ACTION START */

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_order"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM orders WHERE id = $id");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_waiters"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM users WHERE id = $id AND secret = '$secret'");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_users"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM users WHERE id = $id");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_warehouse"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM warehouse WHERE wid = $id");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_menus"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM menus WHERE id = $id");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_table"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM tables WHERE id = $id AND state = 0");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

if(isset($_POST['delete_action']) && $_POST['delete_action'] == "delete_distributors"){
	$id = cin(intval($_POST['id']));
	$delete = $MySQL->query("DELETE FROM distributors WHERE id = $id");
	if($delete === true)
		echo "deleted";
	else
		echo "failure";
	exit;
}

/* DELETE ACTION END */

if(isset($_POST['calculate_orders'])){
	$table_id = cin(intval($_POST['calculate_orders']));
	$session_id = cin($_POST['session_id']);;
	$discount = cin(floatval($_POST['discount']));
	$payment_method = cin($_POST['payment_method']);;
	$cash = cin(floatval($_POST['cash']));
	$card = cin(floatval($_POST['card']));
	if(isset($_POST['spec_guest']))
		$spec_guest = 1;
	else
		$spec_guest = 0;
	$update = $MySQL->query("UPDATE tables SET payment_method = '$payment_method', cash = $cash, card = $card, discount = $discount, special_guest = $spec_guest WHERE id = $table_id");
	if($update === true){
	$table = get_table_details($table_id);
	$discount = $table["discount"];
	$payment_method = $table["payment_method"];
	$payment_method = get_payment_method($payment_method,$payment_method)["text"];
	if($spec_guest == 1)
		$total_price = get_total_price($table_id,$session_id)["total_cost_price"];
	else
		$total_price = get_total_price($table_id,$session_id)["total_sell_price"];
	$sum = $total_price - ( $total_price * ( $discount / 100 ) );
?>
	<div class="panel panel-default">
		<div class="panel-body">
			<p><?php echo $lang['payment_method'].": ".$payment_method; ?></p>
			<p><?php echo $lang['total_price'].": ".$total_price." ".$lang['currency']; ?></p>
			<p><?php echo $lang['discount'].": ".$discount."%"; ?></p>
			<p><?php echo $lang['amount_to_pay'].": ".$sum." ".$lang['currency']; ?></p>
		</div>
		<div class="panel-footer"><a target="_blank" href="/?mod=print&amp;table_id=<?=$table_id?>&amp;session_id=<?=$session_id?>"><button onclick="window.location.reload()" class="btn btn-info btn-sm"><?=$lang['print_reciept']?></button></a></div>
	</div>
<?php
	}else{
		echo "count_error";
	}
	exit;
}

if(isset($_POST['see_product'])){
	$id = cin(intval($_POST['see_product']));
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?=$lang['refill_or_add_stock']?></h4>
</div>
	<div class="modal-body">
		<div class="row">
			<!--START OF TOP BLOCK-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<form id="refill-form" method="POST" class="form-inline" action="" enctype="multipart/form-data">
					<div class="form-group order-inputs">
						<div style="overflow-x:initial" class="table-responsive">
							<table class="table table-bordered">
								<thead><th><?=$lang['name']?></th><th><?=$lang['quantity']?></th><th><?=$lang['dimension']?></th><th><?=$lang['total_price']?></th><th><?=$lang['unit_price']?></th></thead>
								<tbody>
									<tr>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" id="name" name="name" placeholder="<?=$lang['product_name']?>" class="form-control">
												<input type="hidden" id="item-id" name="item_id" class="hidden">
												<input type="hidden" id="item-wid" name="item_wid" class="hidden">
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="number" min="0" id="quantity" name="quantity" placeholder="<?=$lang['quantity']?>" class="form-control">
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<select class="form-control" name="dimension">
													<option id="na" value="-"><?=$lang['choose_dimension']?></option>
													<option id="unit" value="<?=$lang['unit']?>"><?=$lang['unit']?></option>
													<option id="kg" value="<?=$lang['kg']?>"><?=$lang['kilogram']?></option>
													<option id="lt" value="<?=$lang['litre']?>"><?=$lang['litre']?></option>
												</select>
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="number" min="0" id="total-price" name="total_price" placeholder="<?=$lang['total_price']?>" class="form-control wh-inputs">
												<span class="input-group-addon"><?=$lang['currency']?></span>
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="number" id="unit-price" readonly="readonly" name="unit_price" placeholder="<?=$lang['unit_price']?>" class="form-control wh-inputs">
												<span class="input-group-addon"><?=$lang['currency']?></span>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>		
					</div>
					<div class="form-group order-inputs">
						<div style="overflow-x:initial" class="table-responsive">
							<table class="table table-bordered">
								<thead><th><?=$lang['distributor']?></th><th>ID</th><th><?=$lang['waybill_s']?> №</th><th><?=$lang['refill_date']?></th></thead>
								<tbody>
									<tr>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" id="dist-name" name="dist_name" placeholder="<?=$lang['distributor']?>" class="form-control">
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="number" readonly="readonly" id="dist-id" class="form-control wh-inputs" name="dist_id">
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" id="waybill" name="waybill" placeholder="<?=$lang['waybill_s']?> №" class="form-control">
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" name="refill_date" readonly="readonly" value="<?=date('Y-m-d')?>" class="form-control">
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</form>
				<div id="response-data" class="form-group order-inputs">
					<!-- CALCULATED ORDERS APEND HERE -->
				</div>
			</div>
			<!--END OF TOP BLOCK-->
			<!-- START OF LEFT BLOCK -->
			<div id="stats-response" class="col-md-6">
				<!-- blaa -->
			</div>
			<!-- END OF LEFT BLOCK -->
			<!--START OF RIGHT BLOCK-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<!-- blaa -->
			</div>
			<!--END OF RIGHT BLOCK-->
		</div>
	</div>
<div class="modal-footer">
	<button class="btn btn-info cancel"><?=$lang['cancel']?></button>
	<button class="btn btn-success refill" data-dismiss="modal"><?=$lang['refill']?></button>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?=$lang['close']?></button>
</div>

<?php
include_once(TPL_DIR."js/warehouse.js.php");
	exit;
}

if(isset($_POST['load_whitem_stats'])){
	$id = cin(intval($_POST['load_whitem_stats']));
	$wid = cin(intval($_POST['wid']));
	$query = $MySQL->query("SELECT * FROM warehouse WHERE wid = $wid");
	while ($row = mysqli_fetch_array($query)) {
		echo "<p>ID: ".$row['id'].", Name: ".$row['name'].", Unit price: ".$row['unit_price'].", Refilled: ".$row['refill_date'].".</p>";
	}
	exit;
}

if( isset($_POST['refill_date']) && ( isset($_POST['name']) && !empty($_POST['name']) ) ){
	$id = cin(intval($_POST['item_id']));
	$wid = cin(intval($_POST['item_wid']));
	$name = cin($_POST['name']);
	$qty = cin(floatval($_POST['quantity']));
	$dimen = cin($_POST['dimension']);
	$tp = cin(floatval($_POST['total_price']));
	$up = cin(floatval($_POST['unit_price']));
	$dist_id = cin(intval($_POST['dist_id']));
	$waybill = cin($_POST['waybill']);
	$refill_date = date("Y-m-d");
	if(!empty($wid) && !empty($id)){
		$insert = $MySQL->query("INSERT INTO warehouse (wid,name,quantity,dimension,unit_price,refill_date,distributor_id,waybill) VALUES ($wid,'$name',$qty,'$dimen',$up,'$refill_date',$dist_id,'$waybill')");
		$update = true;
	}else{
		$insert = $MySQL->query("INSERT INTO warehouse (name,quantity,dimension,unit_price,refill_date,distributor_id,waybill,date) VALUES ('$name',$qty,'$dimen',$up,'$refill_date',$dist_id,'$waybill',now())");
		$wid = $MySQL->insertId();
		$update = $MySQL->query("UPDATE warehouse SET wid = $wid WHERE id = $wid");
	}
	if($insert === true && $update === true)
		echo "success";
	else
		echo "failure";
	$module = "warehouse";
	$array = array(
				"id"=>$id,
				"wid"=>$wid,
				"name"=>$name,
				"quantity"=>$qty,
				"dimension"=>$dimen,
				"total_price"=>$tp,
				"unit_price"=>$up,
				"dist_id"=>$dist_id,
				"waybill"=>$waybill,
				"refill_date"=>$refill_date
			);
	history($module,$array,date('Y-m-d'));
	exit;
}

if(isset($_POST['edit_menu'])){
	$id = cin(intval($_POST['edit_menu']));
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?=$lang['menu_edit']?></h4>
</div>
	<div class="modal-body">
		<div class="row">
			<!--START OF TOP BLOCK-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<form id="edit-form" method="POST" class="form-inline" action="" enctype="multipart/form-data">
					<div class="form-group order-inputs">
						<div style="overflow-x:initial" class="table-responsive">
							<table class="table table-bordered">
								<thead><th><?=$lang['name']?></th><th><?=$lang['sell_price']?></th><th><?=$lang['selfcost']?></th><th><?=$lang['cook_time']?></th></thead>
								<tbody>
									<tr>
										<td>
											<input type="text" name="name" id="name" placeholder="<?=$lang['name']?>" class="form-control">
											<input type="hidden" name="save_menu" value="<?=date('Ymd')?>" class="hidden">
											<input type="hidden" name="item_id" id="item-id" class="hidden">
										</td>
										<td>
											<div class="input-group">
												<input name="sell_price" id="sell-price" placeholder="<?=$lang['sell_price']?>" class="form-control">
												<div class="input-group-addon"><?=$lang['currency']?></div>
											</div>
										</td>
										<td>
											<div class="input-group">
												<input type="text" name="cost_price" id="cost-price" placeholder="<?=$lang['selfcost']?>" class="form-control">
												<div class="input-group-addon"><?=$lang['currency']?></div>
											</div>
										</td>
										<td>
											<div class="input-group">
												<input type="text" name="average_time" id="average-time" class="form-control">
												<div class="input-group-addon"><?=$lang['minute']?></div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>		
					</div>
				</form>
				<div id="response-data" class="form-group order-inputs">
					<!-- CALCULATED ORDERS APEND HERE -->
				</div>
			</div>
			<!--END OF TOP BLOCK-->
			<!-- START OF LEFT BLOCK -->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<h5><?=$lang['set_ingredients']?></h5>
				<form id="ingredients-form-0" class="hidden" action="" method="post">
					<div class="form-group">
						<input type="text" id="wh-item-name" name="wh_item_name" class="form-control" placeholder="<?=$lang['name']?>">
						<input type="hidden" class="hidden" id="wh-item-id" name="wh_item_id">
					</div>
					<div class="form-group">
						<input type="number" min="0" name="wh_item_qty" class="form-control" placeholder="<?=$lang['quantity']?>">
					</div>
					<div class="form-group">
						<select class="form-control" name="wh_item_dimen">
							<option id="na" value="-"><?=$lang['choose_dimension']?></option>
							<option id="unit" value="<?=$lang['unit']?>"><?=$lang['unit']?></option>
							<option id="kg" value="<?=$lang['kg']?>"><?=$lang['kilogram']?></option>
							<option id="lt" value="<?=$lang['litre']?>"><?=$lang['litre']?></option>
						</select>
					</div>
					<div class="form-group">
						<span module="ingredients" class="btn btn-primary btn-sm save-button"><?=$lang['add_ingredient']?></span>
					</div>
				</form>
			</div>
			<!-- END OF LEFT BLOCK -->
			<!--START OF RIGHT BLOCK-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<h5><?=$lang['ingredients']?></h5>
				<div id="ingredients"><!-- blaa --></div>
			</div>
			<!--END OF RIGHT BLOCK-->
		</div>
	</div>
<div class="modal-footer">
	<button type="button" class="btn btn-info cancel"><?=$lang['cancel']?></button>
	<button type="button" class="btn btn-success edit-menu" data-dismiss="modal"><?=$lang['save']?></button>
	<button type="button" class="btn btn-default" data-dismiss="modal"><?=$lang['close']?></button>
</div>
<?php
include_once(TPL_DIR."js/menus.js.php");
	exit;
}

if(isset($_POST['load_ingredients'])){
	echo get_ingredients(intval($_POST['id']),$_POST['load_ingredients']);
	exit;
}

if(isset($_POST['close_session']) && $_POST['close_session'] == date("Ymd")){
$table_id = intval($_POST['tid']);
$session_id = $_POST['sid'];
$o_query = $MySQL->query("SELECT * FROM orders WHERE table_id = $table_id AND session_id = '$session_id'");
$array = array();
$module = "orders";
while ($o_row = mysqli_fetch_array($o_query)) {
$menu = get_menu_item($o_row['menu_item_id']);
$array = array(
			"action"=>"add",
			"id"=>$o_row['id'],
			"table_id"=>$table_id,
			"session_id"=>$session_id,
			"menu_item_id"=>$o_row['menu_item_id'],
			"menu_item"=>$menu["name"],
			"sell_price"=>$menu["sell_price"],
			"quantity"=>$o_row['quantity'],
			"servant_id"=>$o_row['servant_id'],
			"time"=>$o_row['order_time']
		);
history($module,$array,date('Y-m-d'));
}
$table = get_table_details($table_id);
if($table['merge'] != $table_id){
	$aditional_condition = " AND merge = '".$table['merge']."'";
}else{
	$aditional_condition = "AND id = $table_id ";
}
$query = $MySQL->query("UPDATE tables SET session_id = '', default_guest_count = 0, reserver_code = '', reserved_time = '', state = 0, discount = '', special_guest = '', payment_method = 'choose', cash = 0, card = 0, servant_id = '', merge = `tables`.id,visible=1 WHERE session_id = '$session_id' $aditional_condition");
if($query)
	echo "success";
else
	echo $aditional_condition;
exit;
}

if(isset($_POST['reset_filter'])){
	$uri = $_POST['reset_filter'];
	$_SESSION['from'] = "";
	$_SESSION['to'] = "";
	$_SESSION['display_count'] = 25;
	unset($_SESSION['from']);
	unset($_SESSION['to']);
	unset($_SESSION['display_count']);
	echo "success";
	exit;
}

/*********************/
/* SAVE ACTION START */
/*********************/

if(isset($_POST['save_table'])){
	$id = cin(intval($_POST['save_table']));
	$guests = cin(intval($_POST['default_guest_count']));
	$table_no = cin(intval($_POST['table_no']));
	if( (isset($_POST['reserver_code']) && isset($_POST['reserved_time'])) && ($_POST['reserver_code'] > 0 && $_POST['reserved_time'] > date("Y-m-d h:i:s")) ){
		$reserver_code = cin($_POST['reserver_code']);
		$reserved_time = str_replace("/","-",$_POST['reserved_time']);
		$state = 2;
	}else{
		$reserver_code = '';
		$reserved_time = '';
		$state = 0;
	}
	$check = $MySQL->query("SELECT id FROM tables WHERE table_no = $table_no");
	if(mysqli_num_rows($check) >= 1){
		$update = $MySQL->query("UPDATE tables SET table_no = $table_no, merge = $id, reserver_code = '$reserver_code', reserved_time = '$reserved_time', default_guest_count = $guests,state = $state,visible=1 WHERE id = $id");
		echo "duplication_error";
	}
	else{
		$insert = $MySQL->query("INSERT INTO tables (table_no,reserver_code,reserved_time,default_guest_count,state,visible) VALUES ($table_no,'$reserver_code','$reserved_time',$guests,$state,1)");
		$nid = $MySQL->insertId();
		$MySQL->query("UPDATE tables SET merge = $nid WHERE id = $nid");
		if($insert === true)
			echo "success";
		else
			echo "failure";
	}
	exit;
}

if(isset($_POST['save_menu']) && $_POST['save_menu'] == date('Ymd')){
	$id = cin(intval($_POST['item_id']));
	$name = cin($_POST['name']);
	$sell_price = cin(str_replace(",",".",$_POST['sell_price']));
	$cost_price = cin(str_replace(",",".",$_POST['cost_price']));
	$average_time = cin($_POST['average_time']);
	if(empty($id) && !empty($name)){
		$query = $MySQL->query("INSERT INTO menus (name,sell_price,cost_price,average_time) VALUES ('$name','$sell_price','$cost_price','$average_time')");
	}else{
		$query = $MySQL->query("UPDATE menus SET name = '$name', sell_price = '$sell_price', cost_price = '$cost_price', average_time = '$average_time' WHERE id = '$id'");
	}
	if($query === true)
		echo "success";
	else
		echo "failure";
	exit;
}

if(isset($_POST['save_distributors'])){
	if(empty(intval($_POST['save_distributors'])) || intval($_POST['save_distributors']) == 0){
		$name = cin($_POST['name']);
		$phone = cin($_POST['phone']);
		$waybill = cin($_POST['waybill']);
		$insert = $MySQL->query("INSERT INTO distributors (name,phone,waybill) VALUES ('$name','$phone','$waybill')");
	}
	else{
		$id = intval($_POST['save_distributors']);
		$name = cin($_POST['name']);
		$phone = cin($_POST['phone']);
		$waybill = cin($_POST['waybill']);
		$insert = $MySQL->query("UPDATE distributors SET name = '$name', phone = '$phone', waybill = '$waybill' WHERE id = $id");
	}
	if($insert === true)
		echo "success";
	else
		echo "failure";
exit;
}

if(isset($_POST['save_waiter']) && $_POST['save_waiter'] == date("Ymd")){
	$id = intval($_POST['id']);
	$username = cin($_POST['username']);
	$firstname = cin($_POST['firstname']);
	$lastname = cin($_POST['lastname']);
	$role = cin($_POST['role']);
	if(!empty($id) || $id > 0){
		if(!empty($_POST['password'])){
			$options = ['cost' => 12];
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
			$MySQL->query("UPDATE users SET password = '$password' WHERE id = $id AND secret = '$secret'");
		}
		$update = $MySQL->query("UPDATE users SET username='$username',firstname='$firstname',lastname='$lastname',role='$role' WHERE id = $id AND secret = '$secret'");
		if($update === true)
			echo "duplication_error";
		else
			echo "failure";		
	}else{
		$options = ['cost' => 12];
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
		$insert = $MySQL->query("INSERT INTO users (username,firstname,lastname,password,secret,approved,reg_date,role) VALUES ('$username','$firstname','$lastname','$password','$secret',1,now(),'$role')");
		if($insert === true)
			echo "success";
		else
			echo "failure";
		
	}
	exit;
}
/*******************/
/* SAVE ACTION END */
/*******************/

/******************************/
/* TABLES MERGE & SPLIT START */
/******************************/
if(isset($_POST['merge_tables'])){

/*******************************************/
/* აქ გადმოცემულ მაგიდებს ვათავსებ ერთ დიდ მასივში */
/*******************************************/
if(is_array($_POST['table_id'])){
	$array_of_tables = $_POST['table_id'];
}
else{
	$array_of_tables[] = $_POST['table_id'];
}
/*******************************************/
/* აქ გადმოცემულ მაგიდებს ვათავსებ ერთ დიდ მასივში */
/*******************************************/

$new_array_of_tables = array(); // ახალი მასივი რომელშიც თანმიმდევრობით დალაგდება მაგიდები, ჯერ თავისუფლები მერე დაკავებულები

/*************************************************************************************/
/* აქ მასივის თავში ვალაგებ მაგიდებს რომლებიც თავისუფალია და ბოლოში ვალაგებ იმათ რომელბიც დაკავებულია */
/*************************************************************************************/
foreach($array_of_tables as $value){
	$qq = $MySQL->query("SELECT id,session_id FROM tables WHERE id = $value");
	$rr = mysqli_fetch_array($qq);
	if(empty($rr['session_id']))
		array_unshift($new_array_of_tables, $rr['id']);
	else
		array_push($new_array_of_tables, $rr['id']);
}
/*************************************************************************************/
/* აქ მასივის თავში ვალაგებ მაგიდებს რომლებიც თავისუფალია და ბოლოში ვალაგებ იმათ რომელბიც დაკავებულია */
/*************************************************************************************/

$to_merge = false;
$to_split = false;
$array_of_merging_tables = array();

foreach ($new_array_of_tables as $value) {
	$query = $MySQL->query("SELECT id, state, merge, visible,session_id FROM tables WHERE id = $value");
	$row = mysqli_fetch_array($query);

	if($row['state'] != 1 && $row['state'] != 2 && empty($row['session_id'])){
		if($row['merge'] == $value){
			array_push($array_of_merging_tables, $row['id']);
			$to_merge = true;
		}

		if($row['merge'] != $value){
			$array_of_spliting_tables = unserialize($row['merge']);
			$to_split = true;
		}

		if($to_merge == true){
			$id_to_merge_with = min($array_of_merging_tables);
			$serialized_array = serialize($array_of_merging_tables);
			foreach($array_of_merging_tables as $val1){
				$MySQL->query("UPDATE tables SET visible=0,merge='$serialized_array',state=3 WHERE id = $val1");
			}
			$merge = $MySQL->query("UPDATE tables SET visible=1,merge='$serialized_array',state=3 WHERE id = $id_to_merge_with");
		}
		if($to_split == true){
			foreach ($array_of_spliting_tables as $val2) {
				$split = reset_table($val2);
			}
		}
	}
	if($row['state'] != 2 && $row['state'] != 3 && !empty($row['session_id'])){ // here we get state 1 or 0
		if($row['merge'] == $value){
			$to_merge = true;
			array_push($array_of_merging_tables, $row['id']);
		}
		if($row['merge'] != $value){
			$array_of_spliting_tables = unserialize($row['merge']);
			$to_split = true;
		}
		if($to_merge == true){
			$serialized_array = serialize($array_of_merging_tables);
			foreach ($array_of_merging_tables as $val1) {
				$qr = $MySQL->query("SELECT id,state FROM tables WHERE id = $val1");
				$rw = mysqli_fetch_array($qr);
				if($rw['state'] == 1){
					$state = 1;
					$visible = 1;
					$id_to_merge_with = $val1;
				}
				$MySQL->query("UPDATE tables SET state=3,visible=0,merge='$serialized_array' WHERE id = $val1");
			}
			$merge = $MySQL->query("UPDATE tables SET state=$state,visible=$visible,merge='$serialized_array' WHERE id = $id_to_merge_with");
		}

		if($to_split == true){
			foreach ($array_of_spliting_tables as $val2) {
				$qr = $MySQL->query("SELECT id,state FROM tables WHERE id = $val2");
				$rw = mysqli_fetch_array($qr);
				if($rw['state'] == 1){
					$MySQL->query("UPDATE tables SET visible=1,state=1,merge=$val2 WHERE id = $val2");
				}else{
					$split = reset_table($val2);
				}
				
			}
		}
	}
}

if($merge === true || $split === true)
	echo "success";
else
	echo "failure";
exit;
}
/****************************/
/* TABLES MERGE & SPLIT END */
/****************************/


if(isset($_POST['generate_api'])){
	$api_key = md5(md5($_POST['generate_api']).md5($_POST['secret']));
	echo $api_key;
	exit;
}

if(isset($_POST['save_changes']) && $_POST['save_changes'] == 'save_company'){
	$id = intval($_POST['id']);
	$username = cin($_POST['username']);
	$firstname = cin($_POST['firstname']);
	$lastname = cin($_POST['lastname']);
	$email = cin($_POST['email']);
	$secret = cin($_POST['secret']);
	if(!empty($_POST['password'])){
		$options = ['cost' => 12];
		$pass = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
		$password = " password = '".$pass."' ";
	}
	else{
		$password = " password = password ";
	}
	$plan = $_POST['plan'];
	$role = $_POST['role'];
	$api_key = $_POST['api_key'];
	$update = $MySQL->query("UPDATE users SET username = '$username', firstname = '$firstname', lastname = '$lastname', secret = '$secret', email = '$email', $password, plan = '$plan', role = '$role', api_key = '$api_key' WHERE id = $id ");
	if($update)
		header("Location: /admin.php?mod=companies&cat=all");
	else
		header("Location: /admin.php?mod=companies&cat=edit&id=".$id);
	exit;
}


if(isset($_POST['save_changes']) && $_POST['save_changes'] == 'save_user'){
	$id = intval($_POST['id']);
	$username = cin($_POST['username']);
	$firstname = cin($_POST['firstname']);
	$lastname = cin($_POST['lastname']);
	$email = cin($_POST['email']);
	$secret = cin($_POST['secret']);
	if(!empty($_POST['password'])){
		$options = ['cost' => 12];
		$pass = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
		$password = " password = '".$pass."'";
	}
	else{
		$password = " password = password ";
	}
	$update = $MySQL->query("UPDATE users SET username = '$username', firstname = '$firstname', lastname = '$lastname', secret = '$secret', $password, email = '$email' WHERE id = $id ");
	if($update)
		header("Location: /admin.php?mod=users&cat=all");
	else
		header("Location: /admin.php?mod=users&cat=edit&id=".$id);
	exit;
}

if(isset($_POST['add_to_gallery'])){
	$id = $user_id;
	$photo = $_FILES['avatar']['name'];
	$title = cin($_POST['title']);
	$caption = cin($_POST['caption']);
	if (!empty($photo)) {
		$extension = substr(strrchr($photo, '.'), 1);
		$extension = strtolower($extension);
		$name2 = md5(date("Ymdhis"));
		$new_image = $name2 . "1." . $extension;
		$destination="data/users/".$id."/".$new_image;
		$action = copy($_FILES['avatar']['tmp_name'], $destination);
		$insert = $MySQL->query("INSERT INTO gallery (company_id,photo,title,caption,date) VALUES ($id,'$new_image','$title','$caption',now())");
	}else{
		$insert = false;
	}
	if($insert)
		header("Location: /admin.php?mod=media&cat=all");
	else
		header("Location: /admin.php?mod=media&cat=add_new");
	exit;
}


?>