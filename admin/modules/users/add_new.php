<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
	global $lang;
?>
<form action="/index.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" class="hidden" name="form_type" value="user_register">
<div class="form-group">
<label for="username">Username</label>
<input type="text" id="username" class="form-control" name="username" placeholder="Username">
</div>
<div class="form-group">
<label for="email">Email</label>
<input type="text" id="email" class="form-control" name="email" placeholder="Email">
</div>
<div class="form-group">
<label for="password">Password</label>
<input type="password" id="password" class="form-control" name="password" placeholder="Password">
</div>
<div class="form-group">
<label for="secret">Secret</label>
<input type="text" id="secret" class="form-control" name="secret" placeholder="Secret">
</div>
<div class="form-group">
	<label for="role">მომხმარებლის ტიპი</label>
	<select id="role" name="role" class="form-control">
		<option value="choose">აირჩიეთ ანგარიშის ტიპი</option>
		<option value="ROLE_USER">მომხმარებელი</option>
		<option value="ROLE_MANAGER">მენეჯერი</option>
		<option value="ROLE_BARMAN">მიმტანი</option>
		<option value="ROLE_COOK">მზარეული</option>
		<option value="ROLE_ADMIN">ადმინისტრატორი</option>
	</select>
</div>
<div class="form-group">
<label for="avatar">Photo</label>
<input type="file" id="avatar" class="form-control" name="avatar" placeholder="Photo">
</div>
<div class="form-group">
<input type="submit" id="register" class="btn btn-primary" name="register" value="შექმნა">
</div>
</form>