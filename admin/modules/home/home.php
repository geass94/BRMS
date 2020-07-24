<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php global $lang; if( is_admin() ){ ?>

yeyyyy

<?php }else{ echo "<div class='alert alert-danger' role='alert'>".$lang['access_denied']."</div>"; } ?>