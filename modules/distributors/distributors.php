<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<div class="row">
    <div class="distributors-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <th><?=$lang['name']?></th>
            <th><?=$lang['phone']?></th>
            <th><?=$lang['waybill']?></th>
            <th><?=$lang['action']?></th>
        </thead>
            <tbody class="form-inline">
                <form id="distributors-form-0" class="form-inline distributors-form-0" method="POST" action="" enctype="multipart/form-data">
                    <tr>
                        <td>
                            <div class="form-group">
                                <input type="text" id="dist-name" name="name" class="form-control" placeholder="<?=$lang['name']?>">
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><?=$lang['phone_index']?></span>
                                <input type="text" id="dist-phone" name="phone" class="form-control" placeholder="<?=$lang['phone']?>">
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="waybill" name="waybill" placeholder="<?=$lang['waybill']?>">
                        </td>
                        <td>
                            <input type="hidden" name="save_distributors" value="0" class="hidden" id="dist-id">
                            <button type="submit" id="save-0" module="distributors" class="btn btn-primary btn-sm save-button"><?=$lang['add']?></button>
                        </td>
                    </tr>
                </form>
            <?php
            global $MySQL, $Security;
                $query = $MySQL->query("SELECT * FROM distributors ORDER BY id ASC");
                while($row = mysqli_fetch_array($query)){
            ?>
                <form id="distributors-form-<?=$row['id']?>" class="form-inline" method="POST" action="" enctype="multipart/form-data">
                    <tr id="distributors-<?=$row['id']?>">
                        <td>
                            <div class="form-group">
                                <input type="text" name="name" value="<?=cout($row['name'])?>" class="form-control" placeholder="<?=$lang['name']?>">
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><?=$lang['phone_index']?></span>
                                <input type="number" min="0" name="phone" class="form-control" value="<?=cout($row['phone'])?>" placeholder="<?=$lang['phone']?>">
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="waybill" name="waybill" value="<?=cout($row['waybill'])?>" placeholder="<?=$lang['waybill']?>">
                        </td>
                        <td>
                            <input type="hidden" name="save_distributors" value="<?=$row['id']?>">
                            <div id="save-<?=$row['id']?>" module="distributors" class="btn btn-success btn-sm save-button"><?=$lang['save']?></div>
                            <div id="delete-<?=$row['id']?>" onclick="delete_item('distributors','<?=$row['id']?>')" module="distributors" class="btn btn-danger btn-sm"><?=$lang['delete']?></div>
                        </td>
                    </tr>
                </form>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>
<script type="text/javascript">
$('#dist-name').typeahead({
    name: 'dist-name',
    highlight: false,
    limit: 10,
    property: 'item-name',
    source: function (query, process) {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                dataType: 'JSON',
                data: 'distributor_search=' + query,
                success: function(data) {
                    process(data);
                }
            });
        },
    updater: function( item ) {
        $( "#dist-phone" ).val( item.phone )
        $( "#waybill" ).val( item.waybill )
        $( "#dist-id" ).val( item.id )
        return item.name;
  }
});
</script>