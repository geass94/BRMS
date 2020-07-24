<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<button data-toggle="modal" data-target=".modal-box" id="0" class="btn btn-info btn-sm see-product"><?=$lang['refill_stock']?></button>
<br><br>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <th><?=$lang['name']?></th>
            <th><?=$lang['quantity']?></th>
            <th><?=$lang['total_price']?></th>
            <th><?=$lang['unit_price']?></th>
            <th><?=$lang['last_refill']?></th>
            <th><?=$lang['date_added']?></th>
            <th><?=$lang['action']?></th>
        </thead>
            <tbody class="form-inline">
            <?php
                $total = mysqli_num_rows($MySQL->query("SELECT id FROM warehouse GROUP BY wid"));
                $page = 1; $offset = 0;
                if(isset($_GET['page']) && !empty($_GET['page'])){
                    $page = intval($_GET['page'])-1; // If first page make page 0 so offset becomes 0 and loadst first 20 rows
                    $offset = $page * 25;
                }
                $page = intval($_GET['page']);
                $page_count = $total / 25; // How many page will be in total with 20 recors on a page (eg.: 138)
                $page_count = ceil($page_count); // Round total pages up to get extra page in case it is more with 1 record (will get 140 with 8 records on last page)
                $query = $MySQL->query("SELECT DISTINCT id,wid,name,SUM(`quantity`) `QTY`,dimension,AVG(`unit_price`) `AVG`,MAX(refill_date) refill_date,`date`,edit_date,distributor_id,waybill FROM warehouse GROUP BY wid DESC LIMIT 25 OFFSET $offset");
                while($row = mysqli_fetch_array($query)){
                    $status = get_table_status($row['id'])["text"];
                    $class = get_table_status($row['id'])["class"];
            ?>
                    <tr id="warehouse-<?=$row['id']?>">
                        <td>
                            <p><?=cout($row['name'])?></p>
                        </td>
                        <td>
                            <p><?=cout(round($row['QTY'],2))?> - <?=cout($row['dimension'])?></p>
                        </td>
                        <td>                        
                            <p><?php echo cout(round($row['AVG']*$row['QTY'],2)); ?> - <?=$lang['currency']?></p>
                        </td>
                        <td>
                            <p><?=cout(round($row['AVG'],2))?> - <?=$lang['currency']?></p>
                        </td>
                        <td>
                            <p><?=cout($row['refill_date'])?></p>
                        </td>
                        <td>
                            <p><?=cout($row['date'])?></p>
                        </td>
                        <td>
                            <div id="delete-<?=$row['wid']?>" onclick="delete_item('warehouse','<?=$row['id']?>')" module="warehouse" class="btn btn-danger btn-sm"><?=$lang['delete']?></div>
                        </td>
                    </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

<center>
    <nav>
        <ul class="pagination">
        <?php
            echo pagination($page, $page_count, $mGET, '');
        ?>
        </ul>
    </nav>
</center>
</div>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>