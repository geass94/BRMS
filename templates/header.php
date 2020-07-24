<?php if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<!DOCTYPE html>
<html>
<head>
	<title>BRMS v2.0 management - <?=get_page_title($_GET['mod'])?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/jquery-2.1.4.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>style.css">
  <link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>css/jquery.datetimepicker.css">
  <link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>css/jquery-ui.css">
  <script type="text/javascript" src="<?php echo TPL_DIR?>js/bootstrap3-typeahead.min.js"></script>
  <script type="text/javascript" src="<?php echo TPL_DIR; ?>js/jquery.datetimepicker.full.min.js"></script>
	<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="<?php echo TPL_DIR; ?>js/jquery-ui.js"></script>
  <script type="text/javascript" src="<?php echo TPL_DIR; ?>js/isotope.pkgd.min.js"></script>
	<script type="text/javascript">tinymce.init({ selector:'textarea' });</script>
  <?php
    echo "<script>var mGET = '".$mGET."'; var cGET = '".$cGET."';</script>";
  ?>
</head>
<body>
<?php include_once(TPL_DIR."modal.php"); ?>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/index.php">BRMS v2.0</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul id="top-menu" class='nav navbar-nav'>
        <li menu="orders"><a href='/?mod=orders'><?=$lang['menu_orders']?></a></li>
        <?php //if($role == 'ROLE_ADMIN' || $role == 'ROLE_MANAGER'){ ?>
        <li menu="tables"><a href='/?mod=tables'><?=$lang['menu_tables']?></a></li>
        <li menu="warehouse"><a href='/?mod=warehouse'><?=$lang['menu_warehouse']?></a></li>
        <li menu="menus"><a href='/?mod=menus'><?=$lang['menu_menus']?></a></li>
        <li menu="distributors"><a href='/?mod=distributors'><?=$lang['menu_distribution']?></a></li>
        <li menu="statistics" class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><?=$lang['menu_statistics']?> <span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='/?mod=statistics&amp;cat=orders'><?=$lang['menu_stat_orders']?></a></li>
            <li><a href='/?mod=statistics&amp;cat=warehouse'><?=$lang['menu_stat_warehouse']?></a></li>
          </ul>
        </li>
        <?php //} // ROLE end ?>
      </ul>
      <?php if(is_authed()){ ?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><?=$lang['menu_hello']?> - <?=$username?>!</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$lang['menu_manage']?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#"><?=$lang['menu_manage_profile']?></a></li>
            <li><a href="/?mod=admin&amp;cat=waiters"><?=$lang['menu_manage_staff']?></a></li>
            <li><a href="/?logout"><?=$lang['logout']?></a></li>
          </ul>
        </li>
      </ul>
      <?php } // user looged in ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
