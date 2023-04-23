<?php
session_start();
unset($_SESSION['$verify_user_id']);
unset($_SESSION['$idTokenString']);


if(isset($_SESSION['expiry_status']))
{
    $_SESSION['status'] = "Logged Out Successfully";
}
else
{
    $_SESSION['status'] = "Session Has Expired, Sign In Again";
    
}
header('Location: login.php');
exit();
?>