<?php
session_start();
include('dbcon.php');

if(isset($_SESSION['$verify_user_id']))
{
    $uid = $_SESSION['$verify_user_id'];
    $idTokenString = $_SESSION['$idTokenString'];

    try {
        $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        //echo 'working';
    } catch (FailedToVerifyToken $e) {
        //echo 'The token is invalid: '.$e->getMessage();
        $_SESSION['expiry_status'] = "Token Expired, Logged In Again";
        header('Location: logout.php');
        exit();
    } catch (\InvalidArgumentException $e){
        // echo 'The Token could not be parsed: '.$e->getMessage();
        $_SESSION['expiry_status'] = "Token Expired, Logged In Again";
        header('Location: logout.php');
        exit();
    }
}
else
{
    $_SESSION['status'] = "Log In To View The Page";
    header('Location: login.php');
    exit();
}

?>