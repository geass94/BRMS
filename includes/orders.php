<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
/* THIS PAGE IS INCLUDED AT engine.php */
/* THIS PAGE LOADS ORDERS MODAL BOX DATA */
if(isset($_POST['open_order'])){
	$table_id = cin(intval($_POST['open_order']));
	$session_id = create_session($table_id,date('YmdHis'));
	$table = get_table_details($table_id);
	$user = user($table['servant_id']);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $lang['table']." №: ".$table["table_no"]." | ".$lang['session'].": ".$table["session_id"]." | ".$lang['waiter'].": ".$user['firstname']." ".$user['lastname'];?></h4>
</div>
	<div class="modal-body">
	<?php if($table["status"] == 2 && !(date('Y-m-d H:i:s', strtotime('+2 hour')) <= $table["reserved_time"])){ ?>
		<div class="row">
			<div class="col-md-12">
				<form action="" id="reserved-table" method="POST" class="form-inline">
					<div id="reservation-code" class="input-group input-group-sm">
						<span class="input-group-addon"><?=$table["reserved_time"];?></span>
						<input class="form-control input-sm" type="number" min="0" name="reserver_code" placeholder="<?=$lang['reservation_code']?>">
						<input type="hidden" name="table_id" value="<?=$table_id?>">
						<input type="hidden" name="session_id" value="<?=$table['session_id']?>">
						<input type="hidden" name="enter_reserved_code" value="<?=date('Ymd')?>">
						
					</div>
					<span id="rsv-<?=$table_id?>" class="btn btn-default btn-sm enter-code"><?=$lang['input']?></span>
				</form>
			</div>
		</div>
	<?php }else{ ?>
		<!--HERE STARTS CHECK-->
	<?php
	if($table['servant_id'] == $user_id || $role != "ROLE_BARMAN"){
	?>
		<div class="row">
			<!--START OF LEFT BLOCK-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<form id="orders-form" method="POST" action="" enctype="multipart/form-data">
					<div class="form-group order-inputs">
					<label><?=$lang['product_name']?></label>
						<input type="text" id="item-name" name="item_name" class="form-control input-sm" placeholder="<?=$lang['product_name']?>">
						<input type="hidden" id="menu-item-id" name="menu_item_id">
					</div>
					<div class="form-group order-inputs">
						<div class="table-responsive">
							<table class="table table-bordered">
							<thead><th><?=$lang['quantity']?></th><th><?=$lang['unit_price']?></th><th><?=$lang['total_price']?></th></thead>
								<tbody>
									<tr>
										<td>
											<!--<input class="hidden" type="hidden" id="item-qty" name="item_qty" value="1" class="form-control input-sm" placeholder="რაოდენობა">-->
											<input name="quantity" min="1" value="1" id="quantity" class="form-control" type="number">
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" disabled id="sell-price" value="" name="sell_price"><span class="input-group-addon"><?=$lang['currency']?></span>
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" id="price-summary" disabled name="price_summary" value="" class="form-control input-sm" placeholder="<?=$lang['total_price']?>"><span class="input-group-addon"><?=$lang['currency']?></span>
												<input type="hidden" name="add_order" value="<?=$table_id?>">
												<input type="hidden" name="session_id" value="<?=$table['session_id']?>">
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>		
					</div>
					<div class="form-group">
					<label><?=$lang['comment']?></label>
						<textarea class="form-control" name="desc"></textarea>
					</div>
					<div class="form-group order-inputs">
						<span class="btn btn-primary btn-sm add"><?=$lang['add']?></span>  <span class="btn btn-info btn-sm cancel"><?=$lang['cancel']?></span>
					</div>
				</form>
				<form method="POST" action="" id="finish-order" enctype="multipart/form-data">
					<div class="form-group order-inputs">
						<label><?=$lang['payment_method']?></label>
						<select name="payment_method" id="payment-method" class="form-control">
							<option <?=get_payment_method($table["payment_method"],"choose")["state"];?> value="choose"><?=$lang['choose_payment_method']?></option>
							<option <?=get_payment_method($table["payment_method"],"cash")["state"];?> value="cash"><?=$lang['using_cash']?></option>
							<option <?=get_payment_method($table["payment_method"],"card")["state"];?> value="card"><?=$lang['using_card']?></option>
							<option <?=get_payment_method($table["payment_method"],"both")["state"];?> value="both"><?=$lang['using_both']?></option>
							<option <?=get_payment_method($table["payment_method"],"debt")["state"];?> value="debt"><?=$lang['using_debit']?></option>
						</select>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead><th><?=$lang['via_cash']?></th><th><?=$lang['via_card']?></th></thead>
								<?php $total_price = get_total_price($table_id,$table["session_id"])["total_sell_price"]; $total_price = $total_price - ($total_price*$table["discount"]/100); ?>
								<tbody>
									<tr>
										<td>
											<div class="input-group input-group-sm">
											<?php if($table["payment_method"] == "cash"){ 
												$value = "value='".$total_price."'"; 
												$disabled = "";
												}elseif($table["payment_method"] == "both"){
													$value = "value='".($total_price/2)."'";
													$disabled = "";
												}else{
													$value = "value='0'"; 
													$disabled = "disabled"; 
												} ?>
												<input name="cash" <?=$value?> <?=$disabled?> id="cash" type="text" class="form-control input-sm"><span class="input-group-addon"><?=$lang['currency']?></span>
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
											<?php if($table["payment_method"] == "card"){ 
												$value = "value='".$total_price."'"; 
												$disabled = "";
												}elseif($table["payment_method"] == "both"){
													$value = "value='".($total_price/2)."'";
													$disabled = "";
												}else{
													$value = "value='0'"; 
													$disabled = "disabled"; 
												} ?>
												<input name="card" <?=$value?> <?=$disabled?> id="card" type="text" class="form-control input-sm"><span class="input-group-addon"><?=$lang['currency']?></span>
												<input id="sum-price" type="hidden" value="<?=$total_price?>">
											<div class="input-group input-group-sm">
										</td>		
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-group order-inputs">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="spec_guest" <?php if($table["spec_guest"] == 1){ echo "checked";}?> >
								<?=$lang['calculate_via_selfcost']?>
							</label>
						</div>
					</div>
					<div class="form-group order-inputs">
						<div class="input-group input-group-sm">
							<input type="number" min="0" class="form-control input-sm" name="discount" placeholder="<?=$lang['discount']?>" value="<?=$table['discount']?>"><span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group order-inputs">
						<input type="hidden" name="calculate_orders" value="<?=$table_id?>">
						<input type="hidden" name="session_id" value="<?=$table['session_id']?>">
						<div class="btn btn-info btn-sm calculate-orders"><?=$lang['calculate']?></div>
					</div>
				</form>
				<div id="response-data" class="form-group order-inputs">
					<!-- CALCULATED ORDERS APEND HERE -->
				</div>
			</div>
			<!--END OF LEFT BLOCK-->
			<!--START OF RIGHT BLOCK-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead><th><?=$lang['name']?></th><th><?=$lang['price']?></th><th><?=$lang['qty']?>.</th><th><?=$lang['total']?></th><th><?=$lang['action']?></th></thead>
						<tbody id="ordered-items">						
							<?php
							$query = $MySQL->query("SELECT * FROM orders WHERE table_id = $table_id AND session_id = '".$table['session_id']."'");
							while($row = mysqli_fetch_array($query)){
								$menu = get_menu_item($row['menu_item_id']);
							?>
							<tr id="order-<?=$row['id']?>">
								<td><?php echo $menu['name']; if(!empty($row['description'])){ echo " (".$row['description'].")"; }?></td>
								<td><?php echo $menu['sell_price'];?></td>
								<td><?php echo $row['quantity'];?></td>
								<td><?php echo $row['quantity']*$menu['sell_price'];?></td>
								<td><button module="order" onclick="delete_item('order','<?=$row['id']?>')" id="delete-<?=$row['id']?>" class="btn btn-danger btn-sm"><?=$lang['delete']?></button></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!--END OF RIGHT BLOCK-->
		</div>
		<?php } } ?>
		<!--HERE ENDS CHECK-->
	</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?=$lang['close']?></button>
</div>
<?php
include_once(TPL_DIR."js/orders.js.php");
	exit;
}

if(isset($_POST['see_order'])){
	$table_id = cin(intval($_POST['see_order']));
	$table = get_table_details($table_id);
	$user = user($table['servant_id']);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel"><?php echo $lang['table']." №: ".$table["table_no"]." | ".$lang['session'].": ".$table["session_id"]." | ".$lang['waiter'].": ".$user['firstname']." ".$user['lastname'];?></h4>
</div>
	<div class="modal-body">
	<!--HERE STARTS CHECK-->
	<?php
	if($table['servant_id'] == $user_id || $role != "ROLE_BARMAN"){
	?>
		<div class="row">
			<!--START OF LEFT BLOCK-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<form id="orders-form" method="POST" action="" enctype="multipart/form-data">
					<div class="form-group order-inputs">
					<label><?=$lang['product_name']?></label>
						<input type="text" id="item-name" name="item_name" class="form-control input-sm" placeholder="<?=$lang['product_name']?>">
						<input type="hidden" id="menu-item-id" name="menu_item_id">
					</div>
					<div class="form-group order-inputs">
						<div class="table-responsive">
							<table class="table table-bordered">
							<thead><th><?=$lang['quantity']?></th><th><?=$lang['unit_price']?></th><th><?=$lang['total_price']?></th></thead>
								<tbody>
									<tr>
										<td>
											<!--<input class="hidden" type="hidden" id="item-qty" name="item_qty" value="1" class="form-control input-sm" placeholder="რაოდენობა">-->
											<input name="quantity" min="1" value="1" id="quantity" class="form-control" type="number">
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" class="form-control input-sm" disabled id="sell-price" value="" name="sell_price"><span class="input-group-addon"><?=$lang['currency']?></span>
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
												<input type="text" id="price-summary" disabled name="price_summary" value="" class="form-control input-sm" placeholder="<?=$lang['total_price']?>"><span class="input-group-addon"><?=$lang['currency']?></span>
												<input type="hidden" name="add_order" value="<?=$table_id?>">
												<input type="hidden" name="session_id" value="<?=$table['session_id']?>">
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>		
					</div>
					<div class="form-group">
					<label><?=$lang['comment']?></label>
						<textarea class="form-control" name="desc"></textarea>
					</div>
					<div class="form-group order-inputs">
						<span class="btn btn-primary btn-sm add"><?=$lang['add']?></span>  <span class="btn btn-info btn-sm cancel"><?=$lang['cancel']?></span>
					</div>
				</form>
				<form method="POST" action="" id="finish-order" enctype="multipart/form-data">
					<div class="form-group order-inputs">
						<label><?=$lang['payment_method']?></label>
						<select name="payment_method" id="payment-method" class="form-control">
							<option <?=get_payment_method($table["payment_method"],"choose")["state"];?> value="choose"><?=$lang['choose_payment_method']?></option>
							<option <?=get_payment_method($table["payment_method"],"cash")["state"];?> value="cash"><?=$lang['using_cash']?></option>
							<option <?=get_payment_method($table["payment_method"],"card")["state"];?> value="card"><?=$lang['using_card']?></option>
							<option <?=get_payment_method($table["payment_method"],"both")["state"];?> value="both"><?=$lang['using_both']?></option>
							<option <?=get_payment_method($table["payment_method"],"debt")["state"];?> value="debt"><?=$lang['using_debit']?></option>
						</select>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead><th><?=$lang['via_cash']?></th><th><?=$lang['via_card']?></th></thead>
								<?php $total_price = get_total_price($table_id,$table["session_id"])["total_sell_price"]; $total_price = $total_price - ($total_price*$table["discount"]/100); ?>
								<tbody>
									<tr>
										<td>
											<div class="input-group input-group-sm">
											<?php if($table["payment_method"] == "cash"){ 
												$value = "value='".$total_price."'"; 
												$disabled = "";
												}elseif($table["payment_method"] == "both"){
													$value = "value='".($total_price/2)."'";
													$disabled = "";
												}else{
													$value = "value='0'"; 
													$disabled = "disabled"; 
												} ?>
												<input name="cash" <?=$value?> <?=$disabled?> id="cash" type="number" min="0" class="form-control input-sm"><span class="input-group-addon"><?=$lang['currency']?></span>
											</div>
										</td>
										<td>
											<div class="input-group input-group-sm">
											<?php if($table["payment_method"] == "card"){ 
												$value = "value='".$total_price."'"; 
												$disabled = "";
												}elseif($table["payment_method"] == "both"){
													$value = "value='".($total_price/2)."'";
													$disabled = "";
												}else{
													$value = "value='0'"; 
													$disabled = "disabled"; 
												} ?>
												<input name="card" <?=$value?> <?=$disabled?> id="card" type="number" min="0" class="form-control input-sm"><span class="input-group-addon"><?=$lang['currency']?></span>
												<input id="sum-price" type="hidden" value="<?=$total_price?>">
											<div class="input-group input-group-sm">
										</td>		
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="form-group order-inputs">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="spec_guest" <?php if($table["spec_guest"] == 1){ echo "checked";}else{ }?> >
								<?=$lang['calculate_via_selfcost']?>
							</label>
						</div>
					</div>
					<div class="form-group order-inputs">
						<div class="input-group input-group-sm">
							<input type="number" min="0" class="form-control input-sm" name="discount" placeholder="<?=$lang['discount']?>" value="<?=$table['discount']?>"><span class="input-group-addon">%</span>
						</div>
					</div>
					<div class="form-group order-inputs">
						<input type="hidden" name="calculate_orders" value="<?=$table_id?>">
						<input type="hidden" name="session_id" value="<?=$table['session_id']?>">
						<div class="btn btn-info btn-sm calculate-orders"><?=$lang['calculate']?></div>
					</div>
				</form>
				<div id="response-data" class="form-group order-inputs">
					<!-- CALCULATED ORDERS APEND HERE -->
				</div>
			</div>
			<!--END OF LEFT BLOCK-->
			<!--START OF RIGHT BLOCK-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead><th><?=$lang['name']?></th><th><?=$lang['price']?></th><th><?=$lang['qty']?>.</th><th><?=$lang['total']?></th><th><?=$lang['action']?></th></thead>
						<tbody id="ordered-items">						
							<?php
							$query = $MySQL->query("SELECT * FROM orders WHERE table_id = $table_id AND session_id = '".$table['session_id']."'");
							while($row = mysqli_fetch_array($query)){
								$menu = get_menu_item($row['menu_item_id']);
							?>
							<tr id="order-<?=$row['id']?>">
								<td><?php echo $menu['name']; if(!empty($row['description'])){ echo " (".$row['description'].")"; }?></td>
								<td><?php echo $menu['sell_price'];?></td>
								<td><?php echo $row['quantity'];?></td>
								<td><?php echo $row['quantity']*$menu['sell_price'];?></td>
								<td><button module="order" onclick="delete_item('order','<?=$row['id']?>')" id="delete-<?=$row['id']?>" class="btn btn-danger btn-sm"><?=$lang['delete']?></button></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!--END OF RIGHT BLOCK-->
		</div>
		<?php } ?>
		<!--HERE ENDS CHECK-->
	</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal"><?=$lang['close']?></button>
</div>
<?php
include_once(TPL_DIR."js/orders.js.php");
	exit;
}

?>