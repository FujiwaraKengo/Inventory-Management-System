<?php
session_start();
include('dbcon.php');

if (isset($_SESSION['$verify_user_id'])) {
    $uid = $_SESSION['$verify_user_id'];
    $idTokenString = $_SESSION['$idTokenString'];

    try {
        $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        $claims = $verifiedIdToken->claims();

        if ($claims->get('superAdmin') === true || $claims->get('admin') === true) {
            // User has superAdmin or admin access
            // No further action required
        } else {
            $_SESSION['status'] = "Access Denied. You're Not Authorized";
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    } catch (FailedToVerifyToken $e) {
        $_SESSION['expiry_status'] = "Token Expired, Logged In Again";
        header('Location: logout.php');
        exit();
    } catch (\InvalidArgumentException $e) {
        $_SESSION['expiry_status'] = "Token Expired, Logged In Again";
        header('Location: logout.php');
        exit();
    }
} else {
    $_SESSION['status'] = "Log In To View The Page";
    header('Location: login.php');
    exit();
}

?>
