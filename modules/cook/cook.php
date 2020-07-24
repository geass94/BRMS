<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $MySQL, $user_id, $username, $lang, $url, $secret;
?>
<div class="container-fluid main-container">
<?php if(user_has_access(array("ROLE_MANAGER","ROLE_ADMIN","ROLE_COOK"))){ ?>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead><th>ID</th><th>ანგარიშის სახელი</th><th>სახელი</th><th>გვარი</th><th>ბოლოს შემოვიდა</th><th>მოქმედება</th></thead>
			<tbody>
			<?php
				$query = $MySQL->query("SELECT id, username, firstname, lastname, last_login FROM users WHERE role = 'ROLE_COOK' AND secret = '$secret'");
				while($row = mysqli_fetch_array($query)){
			?>
				<tr>
					<td><?php echo cout($row['id']); ?></td>
					<td><?php echo cout($row['username']); ?></td>
					<td><?php echo cout($row['firstname']); ?></td>
					<td><?php echo cout($row['lastname']); ?></td>
					<td><?php echo cout($row['last_login']); ?></td>
					<td><button class="btn btn-danger btn-sm">წაშლა</button></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>
</div>