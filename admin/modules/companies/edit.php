<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $MySQL,$Security,$role,$user_id,$lang,$url;
if(isset($_GET['id']) && !empty($_GET['id'])){
	$id = intval($_GET['id']);
	$company = user($id);
?>

<form action="/index.php" method="POST">
<div class="form-group">
	<label for="username">ანგარიშის სახელი</label>
	<input type="text" class="form-control" id="username" name="username" value="<?=$company['username']?>">
</div>
<div class="form-group">
	<label for="firstname">მომხმარებლის სახელი</label>
	<input type="text" class="form-control" id="firstname" name="firstname" value="<?=$company['firstname']?>">
</div>
<div class="form-group">
	<label for="lastname">მომხმარებლის გვარი</label>
	<input type="text" class="form-control" id="lastname" name="lastname" value="<?=$company['lastname']?>">
</div>
<div class="form-group">
	<label for="email">ელ.ფოსტა</label>
	<input type="text" class="form-control" id="email" name="email" value="<?=$company['email']?>">
</div>
<div class="form-group">
	<label for="password">პაროლი</label>
	<input type="text" class="form-control" id="password" name="password" placeholder="პაროლი" value="">
</div>
<div class="form-group">
	<label for="secret">იდენტიფიკატორი</label>
	<input type="text" class="form-control" id="secret" name="secret" placeholder="პაროლი" value="<?=$company['secret']?>">
</div>
<div class="form-group">
	<label for="role">მომხმარებლის ტიპი</label>
	<select id="role" name="role" class="form-control">
		<option <?=role($company['role'],"choose")['state']?> value="choose">აირჩიეთ ანგარიშის ტიპი</option>
		<option <?=role($company['role'],"ROLE_USER")['state']?> value="ROLE_USER">მომხმარებელი</option>
		<option <?=role($company['role'],"ROLE_MANAGER")['state']?> value="ROLE_MANAGER">მენეჯერი</option>
		<option <?=role($company['role'],"ROLE_BARMAN")['state']?> value="ROLE_BARMAN">მიმტანი</option>
		<option <?=role($company['role'],"ROLE_COOK")['state']?> value="ROLE_COOK">მზარეული</option>
		<option <?=role($company['role'],"ROLE_ADMIN")['state']?> value="ROLE_ADMIN">ადმინისტრატორი</option>
	</select>
</div>
<div class="form-group">
	<label for="plan">არჩეული პაკეტი</label>
	<select id="plan" name="plan" class="form-control">
		<option value="choose" <?=plan($company['plan'],"choose")['state']?>> <?=$lang['not_choosen']?> </option>
		<option value="PLAN_BASIC" <?=plan($company['plan'],"PLAN_BASIC")['state']?>> <?=$lang['plan_basic']?> </option>
		<option value="PLAN_PRO" <?=plan($company['plan'],"PLAN_PRO")['state']?>> <?=$lang['plan_pro']?> </option>
	</select>
</div>


<div class="form-group">
	<label form="api_key">API</label>
	<div class="input-group">
	  <input type="text" readonly="readonly" name="api_key" id="api_key" class="form-control" value="<?=$company['api_key']?>" aria-describedby="basic-addon2">
	  <span onclick="generate_api(<?=$id?>,'<?=$company['secret']?>','<?=date('Ymdhis')?>')" class="input-group-addon" id="basic-addon2">ახლის გენერირება</span>
	</div>
</div>
<div class="form-group">
	<input type="hidden" class="hidden" name="id" value="<?=$id?>">
	<input type="hidden" class="hidden" name="save_changes" value="save_company">
	<button class="btn btn-primary">ცვლილებების შენახვა</button>
	<button class="btn btn-info" onclick="window.history.back();">გაუქმება</button>
</div>
</form>

<?php
}
else{
	echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>";
}