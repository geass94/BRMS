<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
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
		<th>კომპანიის სახელი</th>
		<th>ანგარიშის ტიპი</th>
		<th>რეგისტრაციის თარიღი</th>
		<th>რეგისტრაციის IP</th>
		<th>აქტივაცია</th>
		<th>მოქმედება</th>
	</thead>
	<tbody>
		<?php
			$query = $MySQL->query("SELECT * FROM users WHERE plan IS NULL ORDER BY id DESC");
			while($row = mysqli_fetch_array($query)){
		?>

		<tr id="users-<?=$row['id']?>">
			<td><?=$row['id']?></td>
			<td><?=$row['username']?></td>
			<td><?=role($row['role'],$row['role'])['text']?></td>
			<td><?=$row['reg_date']?></td>
			<td><?=$row['reg_ip']?></td>
			<td><?php if($row['approved']){ echo "<p class='p bg-success'>აქტიური</p>"; }else{ echo "<p class='p bg-warning'><a href='/admin.php?mod=users&amp;cat=edit&amp;id=".$row['id']."'>გასააქტიურებელი</a></p>"; } ?></td>
			<td>
				<a class="btn btn-sm btn-primary" href="/admin.php?mod=users&amp;cat=edit&amp;id=<?=$row['id']?>">შესწორება</a>
				<button onclick="delete_item('users',<?=$row['id']?>)" class="btn btn-sm btn-danger">წაშლა</button>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>