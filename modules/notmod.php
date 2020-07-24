<?php if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<div class="main-page_container" style="padding:15px;width: 553px;">
<style>
.er404{
	background:#f4f4f4;
	height:160px;
	width:400px;
	padding:10px;
}
.er404 span:first-child{
	font-size:14px;
}
.sr-input{
	height: 20px !important;
	padding: 5px !important;
	margin-top: 6px;
	border-radius: 3px;
	border: 1px solid #CCC;
	width: 272px !important;
	float: left;
	margin-left: 0 !important;
}
</style>
<h2>OOPS!</h2>
<img src="templates/img/404.png" style="float:right">
<div class="er404">
<span>Looks like we lost our way :(   Sail Back to  <a href="/yapee" class="red-link"><b>Home Page</b></a></span>
<div class="header-bold"><br>Or use Search</div>
<input type="text" class="sr-input"  onclick="this.value=''" onblur="this.value=!this.value?'Search by Keyword':this.value;" value="Search by Keyword"><br><br><br><br>
<input type="submit" class="srch" value="Search">
</div>
</div>