<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $lang;
?>
<form action="/index.php" method="POST" enctype="multipart/form-data">
<div class="form-group">
<label for="title">Title</label>
<input type="text" id="title" class="form-control" name="title" placeholder="Title">
</div>
<div class="form-group">
<label for="caption">Caption</label>
<textarea id="caption" class="form-control" name="caption">
</textarea>
</div>
<div class="form-group">
<label for="avatar">Photo</label>
<input type="file" id="avatar" class="form-control" name="avatar" placeholder="Photo">
</div>
<div class="form-group">
<input type="submit" id="add_to_gallery" class="btn btn-primary" name="add_to_gallery" value="დამატება">
</div>
</form>