<?php
ini_set('session.gc_maxlifetime', 6000);
session_start();
ob_start();
define("PROTECT", true);
date_default_timezone_set('Asia/Muscat');
$security_key = md5(date("Ymdhis"));
global $security_key;

include("config.inc.php");
include_once(CLS_DIR."main.class.php");
//-- mtavari klasi, saidanac xdeba sxva klasebis chatvirtva failshi
$mainClass = new mainClass();
$mainClass->loadClass("MySQL");
$mainClass->loadClass("Security");
$Security = new Security();
$MySQL = new MySQL();
$MySQL->connect();
//-- Set MySQL Charset to UTF-8
# /*!40101 SET NAMES 'utf8' */
$MySQL->query("SET CHARACTER SET utf8");
$MySQL->query("SET NAMES 'utf8'");
$ip = $_SERVER['REMOTE_ADDR'];

//pre functions
require_once(INC_DIR."pre.inc.php");
require_once(LANG_DIR."georgian/geo.php");
//-- shemogvaqvs funqciebi
include_once(INC_DIR."functions.inc.php");
include_once(INC_DIR."functions/login.php");

global $user_id, $username, $email, $birthday, $level, $role, $lang;
if(isset($_GET['mod'])) { $mGET = $_GET['mod']; } else { unset($mGET); } // module GET
if(isset($_GET['cat'])) { $cGET = $_GET['cat']; } else { unset($cGET); } // category GET
?>
<!DOCTYPE html>
<html>
<head>
<title>მართვის სისტემა - <?=get_page_title($_GET['mod'])?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="admin/css/style.css">
<link href="admin/css/navbar-fixed-side.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo TPL_DIR; ?>css/jquery-ui.css">
<script type="text/javascript" src="<?php echo TPL_DIR?>js/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/jquery-ui.js"></script>

<script type="text/javascript">
var mGET = '<?=$mGET?>';
var cGET = '<?=$cGET?>';
</script>
</head>

<body>
<nav class="navbar navbar-inverse navbar-custom navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/admin.php">BRMS v2.0</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul id="top-menu" class='nav navbar-nav'>
        <li menu="panel"><a href='/admin.php'>პანელი</a></li>
        <?php if( is_admin() ){ ?>
        <li menu="companies"><a href='/admin.php?mod=companies'>კომპანიები</a></li>
        <li menu="users"><a href='/admin.php?mod=users'>მომხმარებლები</a></li>
        <li menu="media"><a href='/admin.php?mod=media'>მედია</a></li>
        <li menu="menus"><a href='/admin.php?mod=menus'>ტრანზაქციები</a></li>
        <li menu="distributors"><a href='/admin.php?mod=distributors'>პარამეტრები</a></li>
        <li menu="statistics" class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>სტატისტიკა <span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='/admin.php?mod=statistics&amp;cat=orders'>შეკვეთები</a></li>
            <li><a href='/admin.php?mod=statistics&amp;cat=warehouse'>საწყობი</a></li>
          </ul>
        </li>
        <?php } // is_admin() end ?>
      </ul>
      <?php if(is_authed()){ ?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">გამარჯობა - <?=$username?>!</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">მართვა <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">პროფილის მართვა</a></li>
            <li><a href="/admin.php?mod=admin&amp;cat=waiters">პერსონალის მართვა</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="/?logout">გასვლა</a></li>
          </ul>
        </li>
      </ul>
      <?php } // user looged in ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<div class="container-fluid main-container">
<div class="row">
<div class="col-sm-3 col-lg-2 left-area">
	<nav class="navbar navbar-inverse navbar-fixed-side">
		<div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand glyphicon glyphicon-list" href="#"> ნავიგაცია</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
      <ul class="nav navbar-nav">
      <?php echo admin_sidebar_menu($mGET); ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
	</nav>
</div>

<div class="col-sm-9 col-lg-10 right-area">

<?php

include_once(ADMMOD_DIR."modules.php");

if(!isset($mGET)) { require_once("admin/main.php"); } else { getMod($mGET); }
?>

</div>
</div>



<footer>
	
</footer>
<script type="text/javascript" src="<?php echo TPL_DIR; ?>js/bootstrap.min.js"></script>
<?php include_once(TPL_DIR."js/custom.js.php"); ?>
</body>

</html>
</body>
</html>