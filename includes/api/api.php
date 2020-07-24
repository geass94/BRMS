<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $MySQL, $Security, $key;
if(isset($_GET['api']) && !empty($_GET['api']) && $_GET['key'] == $key){
$location = $_GET['api'];
$query = $MySQL->query("SELECT * FROM `$location`");
$return = array();
while ($row = mysqli_fetch_assoc($query)) {
	if (isset($row)){ array_push($return, $Security->return_autocomplete($row)); }
}
echo json_encode($return);
exit;
}