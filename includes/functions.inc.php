<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }

function fullmetal_referer($referrer) {
    $referrer = str_replace('www.','',$referrer);
    $referrer = str_replace('https','http',$referrer);
    preg_match('/http:\/\/(.*?)\//is', $referrer, $matches);
    if($matches[1] == 'ggchamp.com' OR $matches[1] == '192.168.1.2'){
        return true;} else {return false;}
}

function STOP() {
    echo "<h3 style=\"color:#FF0000;\">STOP!</h3>";
}

function isEmail($email){
    if(eregi('^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}(\.[a-zA-Z]{2,3})?(\.[a-zA-Z]{2,3})?$', $email))
    {return true;}else {return false;}
}

function isphoto($extension){
    if (($extension == "jpg") OR ($extension == "jpeg") OR ($extension == "gif") OR ($extension == "png")){return true;}  else {return false;}
}

function isvideo($extension){
    if (($extension == "avi") OR ($extension == "mpg") OR ($extension == "mov") OR ($extension == "mp4") OR ($extension == "flv") OR ($extension == "wmv")){return true;}  else {return false;}
}

function get_style($mod){
    if(!isset($mod)){
        return "class='templateIndex'";
    }
    elseif(isset($mod) && $mod == 'register'){
        return "class='template'";
    }
}

function cryptpwd($str) {
$crypt = md5(str_rot13(md5($str)));
return $crypt;
}
/*
function createcode() { 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 10) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 
*/

function createcode($digits = 4){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}


function cout($text){
    global $Security;
    return $Security->return_htmltags($text);
}

function cin($text){
    global $Security;
    return $Security->filter_text($text);
}

function get_page_title($mod){
    switch ($mod) {
        case 'tables':
            return "მაგიდები";
            break;
        case 'orders':
            return "შეკვეთები";
            break;
        
        default:
            return "მართვის სისტემა";
            break;
    }
}

function get_user_option($id,$key){
    global $MySQL,$Security;
    $id = cin(intval($id));
    $key = cin($key);
    $query = $MySQL->query("SELECT `$key` FROM users WHERE id='$id' LIMIT 1");
    $row = mysqli_fetch_array($query);
    return cout($row[0]);
}

function user($id){
    global $MySQL, $Security;
    $id = intval($id);
    $query = $MySQL->query("SELECT * FROM users WHERE id='$id' LIMIT 1");
    $row = mysqli_fetch_array($query);
    $array = array(
            "username"=>cout($row['username']),
            "firstname"=>cout($row['firstname']),
            "lastname"=>cout($row['lastname']),
            "email"=>cout($row['email']),
            "secret"=>cout($row['secret']),
            "pay_day"=>cout($row['pay_day']),
            "expire_day"=>cout($row['expire_day']),
            "plan"=>cout($row['plan']),
            "logo"=>$row['logo'],
            "role"=>$row['role'],
            "api_key"=>$row['api_key']
        );
    return $array;
}

function get_state($state){
    global $Security;
    if($state = 1)
        return "ჩანს";
    else
        return "დამალულია";
}

function get_table_status($id,$module = 'tables'){
    global $MySQL, $Security, $lang;
    $id = cin(intval($id));
    $module = cin($module);
    $query = $MySQL->query("SELECT state FROM `$module` WHERE id = $id LIMIT 1");
    $row = mysqli_fetch_array($query);
    // Patern for status layout: [0]Text, [1]class for paragraph, [2]class for button
    if($row[0] == 1){
        return array(
            "state"=>"1",
            "text" => $lang['serving'],
            "class" => "bg-success",
            "button_class" => "btn btn-success btn-sm see-order",
            "panel_class"=>"panel panel-success"
            );
    }
    elseif($row[0] == 2){
        return array(
            "state"=>"2",
            "text" => $lang['reserved'],
            "class" => "bg-warning",
            "button_class" => "btn btn-warning btn-sm open-order",
            "panel_class"=>"panel panel-warning"
            );
    }
    elseif($row[0] == 3){
        return array(
            "state"=>"3",
            "text" => $lang['merged'],
            "class" => "bg-info",
            "button_class" => "btn btn-info btn-sm open-order",
            "panel_class"=>"panel panel-info"
            );
    }
    else{
        return array(
            "state"=>"0",
            "text" => $lang['free'],
            "class" => "bg-default",
            "button_class" => "btn btn-default btn-sm open-order",
            "panel_class"=>"panel panel-default"
            );
    }
}

function create_session($id,$date){
    global $MySQL,$Security,$user_id;
    $id = cin(intval($id));
    $date = cin($date);
    $mimi = md5($date);
    $check = $MySQL->query("SELECT session_id,state,merge FROM tables WHERE id = $id LIMIT 1");
    $chrow = mysqli_fetch_array($check);
    if($chrow['merge'] != $table_id){
    $aditional_condition = " OR merge = '".$chrow['merge']."'";
    }else{
        $aditional_condition = "";
    }
    if($chrow[0] == NULL && $chrow[1] != 2){
        $MySQL->query("UPDATE tables SET session_id = '$mimi', state = 1, servant_id = $user_id WHERE id = $id $aditional_condition");
        return cout($mimi);
    }
    else{
        return cout($chrow[0]);
    }
}

function date_difference($d1,$d2){
    return round(abs(strtotime($d1)-strtotime($d2))/86400);
}

function get_menu_item($id){
    global $MySQL, $Security;
    $id = cin(intval($id));
    $query = $MySQL->query("SELECT * FROM menus WHERE id = $id LIMIT 1");
    $row = mysqli_fetch_array($query);
    $name = cout($row['name']);
    $sell_price = cout($row['sell_price']);
    $cost_price = cout($row['cost_price']);
    $avg_time = cout($row['average_time']);
    $ingredients = cout($row['ingredients']);
    // Pattern for layout: name[0], sell price[1], cost price[2], average time to coock[3], quantity[4]
    return array( 
        "name" => $name, 
        "sell_price" => $sell_price, 
        "cost_price" =>$cost_price, 
        "avg_time" => $avg_time,
        "ingredients" => $ingredients 
        );
}

function get_ingredients($id,$data){
    global $MySQL, $Security;
    $data = explode("|", $data);
    $size = count($data);
    $ret = "";
    for($i = 0; $i < $size; $i++){
        $data[$i] = str_replace("[", "", $data[$i]);
        $data[$i] = str_replace("]", "", $data[$i]);
        $ings = explode(",", $data[$i]);
        /* First name, Second quantity, Third dimension */
        $ret .= "<p>".$ings[0]." ".$ings[1]." ".$ings[2]." <button class='btn btn-danger btn-sm'>წაშლა</button></p>";
    }
    return $ret;
}

function get_table_details($id){
    global $MySQL,$Security;
    $id = cin(intval($id));
    $query = $MySQL->query("SELECT * FROM tables WHERE id = $id LIMIT 1");
    $row = mysqli_fetch_array($query);
    // Pattern for layout: [0]=>Table No [1]=>Session ID [2]=>Default Guest Count [3]=>Reserver Code [4]=>Reserved Time [5]=>Discount [6]=>Special Guest [7]=>Payment Method [8]=>Cash [9]=>Card
    return array( 
        "table_no" => cout($row['table_no']),
        "session_id" => cout($row['session_id']),
        "guest_count" => cout($row['default_guest_count']),
        "reserver_code" => cout($row['resever_code']),
        "reserved_time" => cout($row['reserved_time']),
        "discount" => cout($row['discount']),
        "spec_guest" => cout($row['special_guest']),
        "payment_method" => cout($row['payment_method']),
        "cash" => cout($row['cash']),
        "card" => cout($row['card']),
        "status" => cout($row['state']),
        "servant_id"=>$row['servant_id'],
        "merge"=>$row['merge']  
        );
}

function pagination($current_page, $last_page, $module, $cat){
    global $lang;
        $first_page = 1;
        if(empty($cat))
            $cat = '';
        else
            $cat = "&cat=".$cat;
        $link = $module.$cat;
        if(empty($current_page))
            $current_page = $first_page;

        $prev = $current_page - 1;
        $next = $current_page + 1;

        $start = $current_page - 2;
        if($start < 1)
            $start = 1;

        $limit = $start + 4;

        if($last_page - $start < 5)
            $start = $last_page - 4;

        if($limit > $last_page)
            $limit = $last_page;

        if($prev <= 0)
            $prev = 1;
        
        if($next >= $last_page)
            $next = $last_page;

        if($last_page < 4)
            $start = 1;

        $ret = "<li><a href='/?mod=".$link."&page=".($prev)."'><span aria-hidden='true'>&laquo;</span></a></li>";
        //if($last_page == 1){
            if($current_page - 1 > 2)
                $ret .= "<li><a href='/?mod=".$link."&page=".$first_page."'><span aria-hidden='true'>".$lang['first']."</span></a></li>";
            for($i = $start; $i <= $limit; $i ++)
            {
                if( $i == $current_page )
                    $active = 'active';
                else
                    $active = '';
                $ret .= "<li class='".$active."'><a href='/?mod=".$link."&page=".abs($i)."'>".abs($i)."</a></li>";
            }
            if($last_page - $current_page > 2)
                $ret .= "<li><a href='/?mod=".$link."&page=".($last_page)."'><span aria-hidden='true'>".$lang['last']."</span></a></li>";
        //}
        $ret .= "<li><a href='/?mod=".$link."&page=".($next)."'><span aria-hidden='true'>&raquo;</span></a></li>";
    return $ret;
}

function get_total_price($id,$sid){
    global $MySQL, $Security;
    $id = cin(intval($id));
    $sid = cin($sid);
    $sum_cost_price = 0;
    $sum_sell_price = 0;
    $query = $MySQL->query("SELECT menu_item_id, quantity FROM orders WHERE table_id = $id AND session_id = '$sid' ");
    while($row = mysqli_fetch_array($query)){
        $sum_sell_price += get_menu_item($row[0])['sell_price']*$row[1];
        $sum_cost_price += get_menu_item($row[0])['cost_price']*$row[1];
    }
    return array( "total_sell_price" => $sum_sell_price, "total_cost_price" => $sum_cost_price );
}

function plan($text,$static = "choose"){
    global $lang;
    if($text == "PLAN_BASIC" && $text == $static)
        return array("text"=>$lang['plan_basic'],"state"=>"SELECTED","price"=>"25");
    elseif($text == "PLAN_PRO" && $text == $static)
        return array("text"=>$lang['plan_pro'],"state"=>"SELECTED","price"=>"50");
    else
        return array("text"=>$lang['not_choosen'],"","price"=>"");
}

function role($text,$static = "choose"){
    global $lang;
    if($text == "ROLE_BARMAN" && $text == $static)
        return array("text"=>$lang['role_barman'],"state"=>"SELECTED");
    elseif($text == "ROLE_COOK" && $text == $static)
        return array("text"=>$lang['role_cook'],"state"=>"SELECTED");
    elseif($text = "ROLE_ADMIN" && $text == $static)
        return array("text"=>$lang['role_admin'],"state"=>"SELECTED");
    elseif($text = "ROLE_USER" && $text == $static)
        return array("text"=>$lang['role_user'],"state"=>"SELECTED");
    elseif($text = "ROLE_MANAGER" && $text == $static)
        return array("text"=>$lang['role_manager'],"state"=>"SELECTED");
    else
        return array("text"=>$lang['not_choosen'],"");
}

function get_payment_method($pm,$static = "choose"){
    global $lang;
    if($pm == "cash" && $pm == $static){

        return array("text"=>$lang['using_cash'],"state"=>"SELECTED");

    }elseif($pm == "card" && $pm == $static){

        return array("text"=>$lang['using_card'],"state"=>"SELECTED");

    }elseif($pm == "both" && $pm == $static){

        return array("text"=>$lang['using_both'],"state"=>"SELECTED");

    }elseif ($pm == "debt" && $pm == $static){

        return array("text"=>$lang['using_debit'],"state"=>"SELECTED");

    }else{

        return array("text"=>$lang['not_choosen'],"state"=>"");

    }
}

function history($module,$array,$date){
    global $MySQL,$Security;
    $array = base64_encode(serialize($array));
    $history = $MySQL->query("INSERT INTO history (module,array,`date`) VALUES ('$module','$array','$date')");
    if($history === true)
        return true;
    else
        return false;
}

function get_history($array){
    return unserialize(base64_decode($array));
}

function clean_export_data($str)
  {
// escape tab characters
    $str = preg_replace("/\t/", "\\t", $str);

    // escape new lines
    $str = preg_replace("/\r?\n/", "\\n", $str);

    // convert 't' and 'f' to boolean values
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';

    // force certain number/date formats to be imported as strings
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }

    // escape fields that include double quotes
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    return $str;
  }

function reset_table($id){
    global $MySQL;
    $reset = $MySQL->query("UPDATE tables SET visible=1,merge=$id,cash=0,card=0,payment_method='choose',special_guest=0,discount=0,servant_id='',state=0,reserver_code='',reserved_time='',session_id='' WHERE id = $id ");
    if($reset)
        return true;
    else
        return false;
}


function admin_sidebar_menu($mGET){
    $menu = "";
    if( $mGET == 'companies' )
    {
        $menu = "
            <li menu='all'><a href='/admin.php?mod=companies&cat=all'>ყველა კომპანია</a></li>
            <li menu='add_new'><a href='/admin.php?mod=companies&cat=add_new'>ახლის დამატება</a></li>
            <li menu='plans'><a href='/admin.php?mod=companies&cat=plans'>პაკეტები</a></li>
        ";
    }
    if( $mGET == 'users' )
    {
        $menu = "
            <li menu='all'><a href='/admin.php?mod=users&cat=all'>ყველა მომხმარებელი</a></li>
            <li menu='add_new'><a href='/admin.php?mod=users&cat=add_new'>ახლის დამატება</a></li>
            <li menu='plans'><a href='/admin.php?mod=users&cat=plans'>პაკეტები</a></li>
        ";
    }
    if( $mGET == 'media' )
    {
        $menu = "
            <li menu='all'><a href='/admin.php?mod=media&cat=all'>ყველა სურათი</a></li>
            <li menu='add_new'><a href='/admin.php?mod=media&cat=add_new'>ახლის დამატება</a></li>
        ";
    }
    return $menu;
}


?>