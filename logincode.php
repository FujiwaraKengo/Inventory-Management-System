<?php
session_start();
include('dbcon.php');

if(isset($_POST['loginAcc']))
{
    $email = $_POST['email'];
    $clearTextPassword = $_POST['password'];

    try
    {
        $user = $auth->getUserByEmail($email);
        
        if($user->disabled)
        {
            $_SESSION['status'] = "Account is Disabled. Contact Admin to Enable Again.";
            header('Location: login.php');
            exit();
        }

        try
        {
            $signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
            $idTokenString = $signInResult->idToken();
            
        
            try
            {
                $verifiedIdToken = $auth->verifyIdToken($idTokenString);
                $uid = $verifiedIdToken->claims()->get('sub');

                $claims = $auth->getUser($uid)->customClaims;
                if(isset($claims['superAdmin']) == true)
                {
                    $_SESSION['$verify_superAdmin'] = true;
                    $_SESSION['$verify_user_id'] = $uid;
                    $_SESSION['$idTokenString'] = $idTokenString;
                }
                elseif(isset($claims['admin']) == true)
                {
                    $_SESSION['$verify_admin'] = true;
                    $_SESSION['$verify_user_id'] = $uid;
                    $_SESSION['$idTokenString'] = $idTokenString;
                }
                elseif($claims == null)
                {
                    $_SESSION['$verify_user_id'] = $uid;
                    $_SESSION['$idTokenString'] = $idTokenString;
                }

                

                $_SESSION['status'] = "Logged In Successfully. Welcome " . $user->displayName. "!";
                header('Location: home.php');
                exit();
            }
            catch (FailedToVerifyToken $e)
            {
                echo 'The token is invalid: '.$e->getMessage();
            }

        }
        catch(Exception $e)
        {
            $_SESSION['status'] = "Wrong Password";
            header('Location: login.php');
            exit();
        }
        
    }
    catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e)
    {
        $_SESSION['status'] = "Invalid Email";
        header('Location: login.php');
        exit();
    }
}
else
{
    $_SESSION['status'] = "Not Allowed";
    header('Location: login.php');
    exit();
}

?>
