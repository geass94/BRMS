<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php 
global $lang;
if(is_authed()){
  header("Location: /?mod=orders");
}else{

?>
<div class="container-fluid main-container">
<div class="col-md-8 col-md-offset-2">
  <center><img class="loader hidden" src="templates/images/loader.gif" /></center>
  <p id="response"></p>
    <form method="POST" id="login" action="" class="form-horizontal" enctype="multipart/form-data">
      <div class="form-group">
        <label for="username" class="col-sm-2 control-label"><?=$lang['username']?></label>
        <div class="col-sm-10">
          <input type="text" name="username" class="form-control" id="username" placeholder="<?=$lang['username']?>">
        </div>
      </div>
      <div class="form-group">
        <label for="secret" class="col-sm-2 control-label"><?=$lang['identificator']?></label>
        <div class="col-sm-10">
          <input type="text" name="secret" class="form-control" id="secret" placeholder="<?=$lang['identificator']?>">
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-2 control-label"><?=$lang['password']?></label>
        <div class="col-sm-10">
          <input type="password" name="password" class="form-control" id="password" placeholder="<?=$lang['password']?>">
          <input type="hidden" name="login" value="<?=date('Y-m-d H:i:s')?>">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="btn btn-default login"><?=$lang['login']?></div>
        </div>
      </div>
    </form>
    </div>
</div>
<?php } ?>