<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $MySQL, $user_id, $username, $lang, $url, $secret;
?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN"))){ ?>
<div class="row">
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead><th>ანგარიშის სახელი</th><th>ანგარიშის ტიპი</th><th>პაროლი</th><th>სახელი</th><th>გვარი</th><th>ბოლოს შემოვიდა</th><th>მოქმედება</th></thead>
			<tbody>
			<form id="waiters-form-0" class="form-inline" method="POST" action="" enctype="multipart/form-data">
				<tr id="waiters-0">
					<input type="hidden" name="id" value="0" class="hidden">
					<td><input type="text" name="username" class="form-control" placeholder=""></td>
					<td>
						<select name="role" class="form-control">
							<option value="choose">აირჩიეთ ანგარიშის ტიპი</option>
							<option value="ROLE_BARMAN">მიმტანი</option>
							<option value="ROLE_COOK">მზარეული</option>
							<option value="ROLE_ADMIN">ადმინისტრატორი</option>
						</select>
					</td>
					<td><input type="password" name="password" class="form-control"></td>
					<td><input type="text" name="firstname" class="form-control" placeholder=""></td>
					<td><input type="text" name="lastname" class="form-control" placeholder=""></td>
					<td><p class="text-info">ახალი პერსონალი</p></td>
					<td>
						<span id="save-0" module="waiters" class="btn btn-primary btn-sm save-button">დამატება</span>
					</td>
				</tr>
				<input type="hidden" class="hidden" name="save_waiter" value="<?=date('Ymd')?>">
			</form>
			<?php
				$query = $MySQL->query("SELECT * FROM users WHERE secret = '$secret'");
				while($row = mysqli_fetch_array($query)){
			?>
			<form id="waiters-form-<?=$row['id']?>" class="form-inline" method="POST" action="" enctype="multipart/form-data">
				<tr id="waiters-<?=$row['id']?>">
					<input type="hidden" name="id" class="hidden" value="<?php echo cout($row['id']); ?>">
					<td><input type="text" name="username" class="form-control" placeholder="" value="<?php echo cout($row['username']); ?>"></td>
					<td>
						<select name="role" class="form-control">
							<option <?=role($row['role'],"choose")['state']?> value="choose">აირჩიეთ ანგარიშის ტიპი</option>
							<option <?=role($row['role'],"ROLE_BARMAN")['state']?> value="ROLE_BARMAN">მიმტანი</option>
							<option <?=role($row['role'],"ROLE_COOK")['state']?> value="ROLE_COOK">მზარეული</option>
							<option <?=role($row['role'],"ROLE_ADMIN")['state']?> value="ROLE_ADMIN">ადმინისტრატორი</option>
						</select>
					</td>
					<td><input type="password" name="password" class="form-control"></td>
					<td><input type="text" name="firstname" class="form-control" placeholder="" value="<?php echo cout($row['firstname']); ?>"></td>
					<td><input type="text" name="lastname" class="form-control" placeholder="" value="<?php echo cout($row['lastname']); ?>"></td>
					<td><span id="response-<?=$row['id']?>"><?php echo cout($row['last_login']); ?></span></td>
					<td>
						<span id="save-<?=$row['id']?>" module="waiters" class="btn btn-success btn-sm save-button">შენახვა</span>
						<span class="btn btn-danger btn-sm" onclick="delete_item('waiters','<?=$row['id']?>')">წაშლა</span>
					</td>
				</tr>
				<input type="hidden" class="hidden" name="save_waiter" value="<?=date('Ymd')?>">
			</form>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>