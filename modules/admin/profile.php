<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $user_id, $username, $lang, $url;
$user = user($user_id);
$pay_day = ($user['pay_day']);
$exp_day = ($user['expire_day']);
$total_days = date_difference($pay_day,$exp_day);
$days_left = date_difference($exp_day,date("Y-m-d"));
$percent = round((($total_days-$days_left)*100)/$total_days,2);
$plan = plan($user['plan'],$user['plan'])['text'];
$price = plan($user['plan'],$user['plan'])['price'];
?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<div class="page-header">
  <h3>თქვენი პაკეტია <small><?php echo $plan." (".$price." ლარი)"; ?></small></h3>
</div>
<div class="progress">
  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent?>%;">
    <?php echo "დარჩა ".$days_left." დღე ".$total_days." დან"; ?>
  </div>
</div>
<div class="col-md-6">
<p>სახელი: <?=$user['username']?></p>
<p>ელ-ფოსტა: <?=$user['email']?></p>
<p>იდენტიფიკატორი: <?=$user['secret']?></p>
<p>პაკეტის დასახელება: <?php echo $plan." (".$price." ლარი)"; ?></p>
<form action="<?=$url?>" method="post">
	<div class="form-group">
		<select class="form-control">
			<option value="choose" <?=plan($user['plan'],"choose")['state']?>> <?=$lang['not_choosen']?> </option>
			<option value="PLAN_BASIC" <?=plan($user['plan'],"PLAN_BASIC")['state']?>> <?=$lang['plan_basic']?> </option>
			<option value="PLAN_PRO" <?=plan($user['plan'],"PLAN_PRO")['state']?>> <?=$lang['plan_pro']?> </option>
		</select>
	</div>
	<div class="form-group">
	<button class="btn btn-warning btn-sm">პაკეტის შეცვლა</button>
	<button class="btn btn-success btn-sm">პაკეტის გახანგრძლივება</button>
	</div>
</form>
<p>გააქტიურების თარიღი: <?=$pay_day?></p>
<p>შემდეგი გადახდის თარიღი: <?=$exp_day?></p>
</div>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>