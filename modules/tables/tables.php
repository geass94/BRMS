<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <th><?=$lang['table_no']?></th>
            <th><?=$lang['guest_count']?></th>
            <th><?=$lang['status']?></th>
            <th><?=$lang['reserve']?></th>
            <th><?=$lang['action']?></th>
        </thead>
            <tbody class="form-inline">
                <form id="table-form-0" class="form-inline new-table-form" method="POST" action="" enctype="multipart/form-data">
                    <tr>
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">№</span>
                                <input type="text" name="table_no" class="form-control" placeholder="<?=$lang['table_s']?> №">
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><?=$lang['qty']?>.</span>
                                <input type="text" name="default_guest_count" class="form-control" placeholder="<?=$lang['guest_count']?>">
                            </div>
                        </td>
                        <td>
                            <p id="response-0" class="text-info"><?=$lang['new_table']?></p>
                        </td>
                        <td class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="number" onclick="generateCode(0)" id="reserver-code-0" readonly="readonly" name="reserver_code" class="form-control input-sm" placeholder="<?=$lang['get_reservation_code']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-sm datetime" id="datetime-0" placeholder="<?=$lang['time']?>">
                                    <input type="hidden" name="reserved_time" id="reserved-date-0">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" name="save_table" value="0">
                            <button type="submit" id="save-0" module="table" class="btn btn-primary btn-sm save-button"><?=$lang['add']?></button>                            
                        </td>
                    </tr>
                </form>
            <?php
            global $MySQL, $Security;
                $query = $MySQL->query("SELECT * FROM tables ORDER BY table_no ASC");
                while($row = mysqli_fetch_array($query)){
                    $status = get_table_status($row['id']);
            ?>
                <form id="table-form-<?=$row['id']?>" class="form-inline" method="POST" action="" enctype="multipart/form-data">
                    <tr id="table-<?=$row['id']?>">
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">№</span>
                                <input type="number" min="0" name="table_no" value="<?=cout(intval($row['table_no']))?>" class="form-control" placeholder="<?=$lang['table_s']?> №">
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><?=$lang['qty']?>.</span>
                                <input type="number" min="0" name="default_guest_count" class="form-control" value="<?=cout($row['default_guest_count'])?>" placeholder="<?=$lang['guest_count']?>">
                            </div>
                        </td>
                        <td>
                            <p id="response-<?=$row['id']?>" class="<?=$status['class']?>"><?=$status['text']?></p>
                        </td>
                        <td class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="number" onclick="generateCode(<?=$row['id']?>)" id="reserver-code-<?=$row['id']?>" readonly="readonly" name="reserver_code" value="<?=cout($row['reserver_code'])?>" class="form-control input-sm" id="reserver" placeholder="<?=$lang['get_reservation_code']?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-sm datetime" id="datetime-<?=$row['id']?>" value="<?=cout($row['reserved_time'])?>" placeholder="<?=$lang['time']?>">
                                    <input type="hidden" name="reserved_time" id="reserved-date-<?=$row['id']?>" value="<?=cout($row['reserved_time'])?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" name="save_table" value="<?=$row['id']?>">
                            <div id="save-<?=$row['id']?>" module="table" class="btn btn-success btn-sm save-button"><?=$lang['save']?></div>
                            <div id="delete-<?=$row['id']?>" onclick="delete_item('table','<?=$row['id']?>')" module="table" class="btn btn-danger btn-sm"><?=$lang['delete']?></div>
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