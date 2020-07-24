<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }

function login($username,$password,$secret){
    echo "string";
    global $MySQL, $Security;
    $query = $MySQL->query("SELECT id, username, email, secret, approved, role, password FROM users WHERE (username = '$username' OR email = '$username') AND secret = '$secret' LIMIT 1");
    if(mysqli_num_rows($query) == 1){
        $row = mysqli_fetch_array($query);
        if (password_verify($password, $row['password'])){
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['secret'] = $row['secret'];
            $_SESSION['approved'] = $row['approved'];
            $_SESSION['role'] = $row['role'];
            $last_login = date('Y-m-d H:i:s');
            $MySQL->query("UPDATE users SET last_login = '$last_login' WHERE id = $row[0]");
            return true;
        }
        else{
            return false;
        }
    }else{
        return false;
    }
}


function user_exists($userid) {
    global $MySQL;
    global $Security;
    $selct = $MySQL->query("SELECT id FROM users WHERE id='$userid'");
    if(mysqli_num_rows($selct) == 1) {return true;} else {return false;}

}

function change_user_pass($password, $userid)
{
    global $MySQL;
    global $Security;
    $options = ['cost' => 12];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
    $MySQL->query("UPDATE users SET password='$encrypted' WHERE id ='$userid'");
}


function is_authed()
{

     if (isset($_SESSION['userid']))
     {
          return 1;
     }
     else
     {
          return 0;
     }
}

function is_admin(){
    if(isset($_SESSION['userid']) && isset($_SESSION['role']) && $_SESSION['role'] == "ROLE_ADMIN")
        return true;
    else
        return false;
}


function user_approved(){
    if (!empty($_SESSION['userid']) && $_SESSION['approved'] == 1) { return true;}else{return false;}
}

function user_has_access($accessible = array("ROLE_BARMAN")){
    global $user_id, $role, $MySQL, $Security;
        if(in_array($role,$accessible))
            return true;
        else
            return false;
}

function calculateAge($birthday){
    return floor((time() - strtotime($birthday))/31556926);
}

function logout(){
    //global $MySQL;
    //$MySQL->query('delete from user_remember_sessions where email=\''.$_SESSION['email'].'\'');
    //@session_unregister($_SESSION['userid']);
    //@session_unregister($_SESSION['approved']);
    //session_destroy();

    session_unset();
    session_destroy();
    return "success";
}


?>