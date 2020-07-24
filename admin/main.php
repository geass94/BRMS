<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
if( is_admin() ){
  header("Location: /admin.php?mod=home");
}else{
?>
<center><img class="loader hidden" src="templates/images/loader.gif" /></center>
<center><p id="response"></p></center>
<form id="login" action="admin.php" method="POST">
<div class="form-group">
<label for="username">მომხმარებლის სახელი</label>
	<input type="text" class="form-control" id="username" name="username">
</div>

<div class="form-group">
<label for="secret">მომხმარებლის იდენტიფიკატორი</label>
	<input type="text" readonly="readonly" value="geass" class="form-control" id="secret" name="secret">
</div>

<div class="form-group">
<label for="password">მომხმარებლის პაროლი</label>
	<input type="password" class="form-control" id="password" name="password">
	<input type="hidden" class="hidden" name="login" value="<?=date('Y-m-d H:i:s')?>">
</div>

<div class="form-group">
	<span class="btn btn-primary login">ავტორიზაცია</span>
</div>


</form>
<?php } ?>