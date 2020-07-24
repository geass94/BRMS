<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<button data-toggle="modal" data-target=".modal-box" id="0" class="btn btn-info btn-sm edit-menu"><?=$lang['menu_edit']?></button>
<br><br>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <th><?=$lang['name']?></th>
            <th><?=$lang['price']?></th>
            <th><?=$lang['selftcost']?></th>
            <th><?=$lang['cook_time']?></th>
            <th><?=$lang['action']?></th>
        </thead>
            <tbody class="form-inline">
            <form id="menus-form-0" class="form-inline" method="POST" action="" enctype="multipart/form-data">

            </form>
            <?php
                $total = mysqli_num_rows($MySQL->query("SELECT id FROM menus"));
                $page = 1; $offset = 0;
                if(isset($_GET['page']) && !empty($_GET['page'])){
                    $page = intval($_GET['page'])-1; // If first page make page 0 so offset becomes 0 and loadst first 20 rows
                    $offset = $page * 25;
                }
                $page = intval($_GET['page']);
                $page_count = $total / 25; // How many page will be in total with 20 recors on a page (eg.: 138)
                $page_count = ceil($page_count); // Round total pages up to get extra page in case it is more with 1 record (will get 140 with 8 records on last page)
                $query = $MySQL->query("SELECT id,name,cost_price,sell_price,average_time FROM menus ORDER BY id DESC LIMIT 25 OFFSET $offset");
                while($row = mysqli_fetch_array($query)){
                    $status = get_table_status($row['id'])["text"];
                    $class = get_table_status($row['id'])["class"];
            ?>
                    <tr id="menus-<?=$row['id']?>">
                        <td>
                            <div class="form-group">
                            	<?=cout($row['name'])?>
                            </div>
                        </td>
                        <td>
                            <?=cout(round($row['sell_price'],2))?> - <?=$lang['currency']?>
                        </td>
                        <td>                        
                            <?php echo cout(round($row['cost_price'])); ?> - <?=$lang['currency']?>
                        </td>
                        <td>
                            <div class="form-group"><?=cout($row['average_time'])?> - <?=$lang['minute']?></div>
                        </td>
                        <td>
                            <div id="delete-<?=$row['id']?>" onclick="delete_item('menus','<?=$row['id']?>')" module="menus" class="btn btn-danger btn-sm"><?=$lang['delete']?></div>
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