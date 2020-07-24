<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN","ROLE_BARMAN"))){ ?>
<form action="" method="POST" enctype="multipart/form-data" id="table-merge-form" class="form-inline">
<input type="hidden" class="hidden" name="merge_tables" value="<?=date('Ymd')?>">
<label><?=$lang['merge_selected_tables']?>
    <span class="btn btn-default btn-sm merge" id="merge"><?=$lang['merge']?></span>
</label>
<br><br>
	<div class="row grid">
		<?php $tables_query = $MySQL->query("SELECT id, table_no, session_id,merge,state FROM tables WHERE visible = 1 GROUP BY merge ORDER BY table_no");
			while($tables_row = mysqli_fetch_array($tables_query)){
				$status = get_table_status($tables_row[0]);
		?>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 grid-item">
				<div class="<?=$status['panel_class']?>">
					<div class="panel-heading">
					<div class="checkbox">
						<label>
						<?php if($tables_row['state'] != 3 && $tables_row['state'] != 2 ){ ?>
							<input name="table_id[]" class="chck-box" value="<?=$tables_row['id']?>" type="checkbox">
							<?php } ?>
							<strong><?=$lang['table']?> №: <?=$tables_row[1]?></strong>
						</label> 
						<span class="hidden"><?=$lang['session']?>: <?=$tables_row[2]?></span>
					</div>
						
					</div>
					<div class="panel-body">
						<p><?=$lang['order']?>:</p>
						<div class="orders-list">
						<?php 
							$query = $MySQL->query("SELECT * FROM orders WHERE table_id = $tables_row[0] AND session_id = '$tables_row[2]'");
							while($row = mysqli_fetch_array($query)){
						?>
							<div class="ordered-item"><p><?php echo "* ".get_menu_item($row['menu_item_id'])["name"]." - ". get_menu_item($row['menu_item_id'])["sell_price"]." ".$lang['currency']." (".$lang['qty'].".: ".cout(intval($row['quantity'])).")"; ?></p></div>
						<?php } ?>
						</div>
					</div>
					<div class="panel-footer">
						<button data-toggle="modal" data-target=".modal-box" id="<?=$tables_row[0]?>" class="<?=$status['button_class']?>"> <?=$status['text']?> </button>
						<?php if($tables_row['state'] == 3 || $tables_row['merge'] != $tables_row['id']){ ?>
							<span onclick="split_tables(<?=$tables_row['id']?>)" class="btn btn-default btn-sm"> <?=$lang['split']?> </span>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
	</div>
</form>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>