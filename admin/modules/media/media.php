<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php global $lang; if( is_admin() ){ ?>
<?
global $user_id,$MySQL,$Security,$lang;
if(isset($_GET['cat']) && $_GET['cat'] != 'all'){
	if($_GET['cat'] == 'edit')
		require_once("edit.php");
	if($_GET['cat'] == 'add_new')
		require_once("add_new.php");
}else{
?>
<div class="table-responsive">
<table class="table table-hover">
	<thead>
		<th>ID</th>
		<th>სურათი</th>
		<th>სურათის დასახელება</th>
		<th>კომპანიის სახელი</th>
		<th>მოქმედება</th>
	</thead>
	<tbody>
		<?php
			$query = $MySQL->query("SELECT * FROM gallery ORDER BY id DESC");
			while($row = mysqli_fetch_array($query)){
		?>

		<tr id="users-<?=$row['id']?>">
			<td><?=$row['company_id']?></td>
			<td><img style="max-height:35px" src="data/users/<?=$row['company_id']?>/<?=$row['photo']?>" class="img-rounded"></td>
			<td><?=$row['title']?></td>
			<td><?=user($row['company_id'])['username']?></td>
			<td>
				<a class="btn btn-sm btn-primary" href="/admin.php?mod=companies&amp;cat=edit&amp;id=<?=$row['id']?>">შესწორება</a>
				<button onclick="delete_item('users',<?=$row['id']?>)" class="btn btn-sm btn-danger">წაშლა</button>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>


<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>