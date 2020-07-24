<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<?php
if(isset($_POST['fdate']))
    $_SESSION['from'] = $_POST['fdate'];


if(isset($_POST['tdate']))
    $_SESSION['to'] = $_POST['tdate'];

if(isset($_POST['display_count'])){
    $_SESSION['display_count'] = intval($_POST['display_count']);
    $display_count = $_SESSION['display_count'];
}
else{
    if(isset($_SESSION['display_count']))
        $display_count = $_SESSION['display_count'];
    else
        $display_count = 25;
}

if(!empty($_SESSION['from']) && !empty($_SESSION['to'])){
    $from = $_SESSION['from'];
    $to = $_SESSION['to'];
    $date_filter = "AND (date BETWEEN '$from' AND '$to')";
}
else{
	$from = "";
	$to = "";
    $date_filter = "";
}
$total = mysqli_num_rows($MySQL->query("SELECT id FROM history WHERE module = 'warehouse' $date_filter"));
?>
<form action="<?=$url?>" method="POST" class="form-inline filter-form">
    <div class="form-group">
        <label class="sr-only" for="from-date"><?=$lang['start_date']?></label>
            <input type="text" class="form-control" value="<?=$from?>" id="from-date" placeholder="<?=$lang['start_date']?>">
            <input type="hidden" name="fdate" class="hidden" id="fd">
    </div>
    <div class="form-group">
        <label class="sr-only" for="ro-date"><?=$lang['end_date']?></label>
            <input type="text" class="form-control" value="<?=$to?>" id="to-date" placeholder="<?=$lang['end_date']?>">
            <input type="hidden" name="tdate" class="hidden" id="td">
    </div>
    <div class="form-group">
        <label class="sr-only" for="ro-date"><?=$lang['end_date']?></label>
            <select class="form-control" name="display_count">
                <option value="75">75 <?=$lang['record']?></option>
                <option value="100">100 <?=$lang['record']?></option>
                <option value="500">500 <?=$lang['record']?></option>
            </select>
    </div>
    <span class="btn btn-warning btn-sm" onclick="reset_filter()"><?=$lang['clear']?></span>
    <button type="submit" class="btn btn-primary btn-sm"><?=$lang['filter']?></button>
    <span class="btn btn-success btn-sm export-to-excel"><?=$lang['export_to_excel']?></span>
</form>
<br>
<div class="row">
		<div class="table-responsive">
		    <table class="table table-bordered table-hover">
		        <thead><th><?=$lang['product_s']?> ID</th><th><?=$lang['name']?></th><th><?=$lang['quantity']?></th><th><?=$lang['dimension']?></th><th><?=$lang['unit_price']?></th><th><?=$lang['total_price']?></th><th><?=$lang['waybill']?></th><th><?=$lang['date']?></th></thead>
		        <tbody>
		        <?php
		        $export_data = "<table border='1'><thead><th>".$lang['product_s']." ID</th><th>".$lang['name']."</th><th>".$lang['quantity']."</th><th>".$lang['dimension']."</th><th>".$lang['unit_price']."</th><th>".$lang['total_price']."</th><th>".$lang['waybill']."</th><th>".$lang['date']."</th></thead><tbody>";
		                $page = 1; $offset = 0;
		                if(isset($_GET['page']) && !empty($_GET['page'])){
		                    $page = intval($_GET['page'])-1; // If first page make page 0 so offset becomes 0 and loadst first 20 rows
		                    $offset = $page * $display_count;
		                }
		                $page = intval($_GET['page']);
		                $page_count = $total / $display_count; // How many page will be in total with 20 recors on a page (eg.: 138)
		                $page_count = ceil($page_count); // Round total pages up to get extra page in case it is more with 1 record (will get 140 with 8 records on last page)

		        	$query = $MySQL->query("SELECT * FROM history WHERE module = 'warehouse' $date_filter ORDER BY id DESC LIMIT $display_count OFFSET $offset");
					while($row = mysqli_fetch_array($query)){
						$history = get_history($row['array']);
		        ?>
		        	<tr>
		        		<td>
		        			<?php  echo cout($history['wid']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['name']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['quantity']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['dimension']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['unit_price']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['total_price']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['waybill']); ?>
		        		</td>
		        		<td>
		        			<?php  echo cout($history['refill_date']); ?>
		        		</td>
		        	</tr>
		        	<?php 
$export_data .= "<tr><td>".(cout($history['wid']))."</td><td>".(cout($history['name']))."</td><td>".(cout($history['quantity']))."</td><td>".(cout($history['dimension']))."</td><td>".(cout($history['unit_price']))."</td><td>".(cout($history['total_price']))."</td><td>".(cout($history['waybill']))."</td><td>".(cout($history['refill_date']))."</td></tr>";
		        	}
		        	$export_data .= " </tbody></table>"
		        	?>
		        </tbody>
		    </table>
		</div>
		<center>
		    <nav>
		        <ul class="pagination">
		        <?php
		            echo pagination($page, $page_count, $mGET, $cGET);
		        ?>
		        </ul>
		    </nav>
		</center>
	</div>
    <form action="index.php" id="export-form" method="POST">
        <input type="hidden" class="hidden" name="export_data" value="<?=$export_data?>">
    </form>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>