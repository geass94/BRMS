<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN","ROLE_BARMAN"))){ ?>
<?php if(isset($_GET['table_id']) && isset($_GET['session_id'])){ ?>
<div id="section-to-print" class="print-area">
<center><img src="<?=TPL_DIR?>images/print-logo.png"></center>
<?php
global $lang;
$table_id = intval($_GET['table_id']);
$session_id = cin($_GET['session_id']);
$query = $MySQL->query("SELECT * FROM orders WHERE table_id = $table_id AND session_id = '$session_id'");
while ($row = mysqli_fetch_array($query)) {
	$menu = get_menu_item($row['menu_item_id']);
	echo "<p>".$menu['name']." - ".$row['quantity']." ".$lang['unit']." (".$menu['sell_price']." ".$lang['currency'].")</p>";
}
$table = get_table_details($table_id);
$discount = $table["discount"];
$payment_method = $table["payment_method"];
$payment_method = get_payment_method($payment_method,$payment_method)["text"];
if($spec_guest == 1)
	$total_price = get_total_price($table_id,$session_id)["total_cost_price"];
else
	$total_price = get_total_price($table_id,$session_id)["total_sell_price"];
$sum = $total_price - ( $total_price * ( $discount / 100 ) );
	echo "<hr>";
	echo "<p>".$lang['payment_method'].": ".$payment_method."</p>"; 
	echo "<p>".$lang['amount_to_pay'].": ".$sum." ".$lang['currency']."</p>";
?>
</div>
<button class="btn btn-danger btn-sm" onclick="window.print();  close_session('<?=$table_id?>','<?=$session_id?>'); "><?=$lang['print_final']?></button>
<button class="btn btn-primary btn-sm" onclick="window.print();"><?=$lang['print_preview']?></button>
<?php } else{ echo $lang['error']; } ?>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>