<?php
session_start();
include('dbcon.php');


if(isset($_POST['userRole']))
{
    $uid = $_POST['roleUserId'];
    $roles = $_POST['roleAs'];

    if($roles == 'superAdmin')
    {
        $auth->setCustomUserClaims($uid, ['superAdmin' => true]);
        $msg = "User Role is Now a Super Admin";
    }
    elseif($roles == 'admin')
    {
        $auth->setCustomUserClaims($uid, ['admin' => true]);
        $msg = "User Role is Now an Admin";
    }
    elseif($roles == 'noRole')
    {
        $auth->setCustomUserClaims($uid, null);
        $msg = "User Role is Now Removed";
    }

    if($msg)
        {
            $_SESSION['status'] = "$msg";
            header("Location: user-edit.php?id=$uid");
            exit();
        }
        else
        {
            $_SESSION['status'] = "Password Not Updated";
            header("Location: user-edit.php?id=$uid");
            exit();
        }
}


if(isset($_POST['changePass']))
{
    $newPass = $_POST['newPass'];
    $retypePass = $_POST['retypePass'];
    $uid = $_POST['changePassUser'];

    if($newPass === $retypePass)
    {
        if(strlen($newPass) < 6) {
            $_SESSION['status'] = "Password must be at least 6 characters";
            header("Location: user-edit.php?id=$uid");
            exit();
        }
        $updatedUser = $auth->changeUserPassword($uid, $newPass);
        if($updatedUser)
        {
            $_SESSION['status'] = "Password Updated";
            header('Location: user-list.php');
            exit();
        }
        else
        {
            $_SESSION['status'] = "Password Not Updated";
            header('Location: user-list.php');
            exit();
        }
    }
    else
    {
        $_SESSION['status'] = "Password Does Not Matched, Try Again";
        header("Location: user-edit.php?id=$uid");
        exit();
    }
}




// Function that Lets Lets the User Status Be Enable or Disabled
if(isset($_POST['accStatus']))
{
    $enable_disable = $_POST['selectAccStatus'];
    $uid = $_POST['EnaDisaUser'];
    if($enable_disable == "disable")
    {

        $updatedUser = $auth->disableUser($uid);
        $msg = "Account Has Been Disabled";
    }
    else
    {
        $updatedUser = $auth->enableUser($uid);
        $msg = "Account Has Been Enabled";
    }

    if($updatedUser)
    {
        $_SESSION['status'] = $msg;
        header('Location: user-list.php');
        exit();
    }
    else
    {
        $_SESSION['status'] = "Something Went Wrong, Try Again";
        header('Location: user-list.php');
        exit();
    }
}


// Function that will Delete User
if(isset($_POST['deleteUser']))
{
    $uid = $_POST['deleteUser'];
    
    try
    {
        $auth->deleteUser($uid);
        $_SESSION['status'] = "User Deleted Successfully";
        header('Location: user-list.php');
        exit();
    }
    catch(Exception $e)
    {
        $_SESSION['status'] = "No ID Found";
        header('Location: user-list.php');
        exit();
    }
}

// Function That Will Update User
if(isset($_POST['updateUser']))
{
    $fullname = $_POST['fname'];
    $phoneNum = $_POST['phone'];

    $uid = $_POST['user_id'];
    $properties = [
        'displayName' => $fullname,
        'phoneNumber' => $phoneNum,
    ];

    $updatedUser = $auth->updateUser($uid, $properties);

    if($updatedUser)
    {
        $_SESSION['status'] = "User Updated Successfully";
        header('Location: user-list.php');
        exit();
    }
    else
    {
        $_SESSION['status'] = "User Not Updated";
        header('Location: user-list.php');
        exit();
    }
}

// Function that will Register an Account
if(isset($_POST['registerAcc']))
{
    $fullname = $_POST['fname'];
    $phoneNum = $_POST['phone'];
    $emailAdd = $_POST['email'];
    $password = $_POST['password'];

    if(strlen($password) < 6) {
        $_SESSION['status'] = "Password must be at least 6 characters";
        header('Location: register.php');
        exit();
    }

    $userProperties = [
        'email' => $emailAdd,
        'emailVerified' => false,
        'phoneNumber' => '+63'.$phoneNum,
        'password' => $password,
        'displayName' => $fullname,
        'disabled' => false,
    ];
    
    $createdUser = $auth->createUser($userProperties);
    
    if($createdUser)
    {
        $_SESSION['status'] = "User Registered Successfully";
        header('Location: register.php');
        exit();
    }
    else
    {
        $_SESSION['status'] = "User Failed to Register";
        header('Location: register.php');
        exit();
    }
}


if(isset($_POST['delete_btn']))
{
    $delete_item = $_POST['delete_btn'];

    $ref_table = 'items/'.$delete_item;
    $deleteQuery_result = $database->getReference($ref_table)->remove();

    if($deleteQuery_result)
    {
        $_SESSION['status'] = 'Item Deleted Successfully';
        header('Location: index.php');
    }
    else
    {
        $_SESSION['status'] = 'Item Not Deleted';
        header('Location: index.php');
    }
}


if(isset($_POST['updateItem']))
{
    $key = $_POST['key'];
    $item = $_POST['itemName'];
    $type = $_POST['itemType'];
    $quantity = $_POST['itemQuantity'];
    $code = $_POST['itemBarcode'];
    $price = $_POST['itemPrice'];

    $updateData = [
        'item' => $item,
        'type' => $type,
        'quantity' => $quantity,
        'barcode' => $code,
        'price' => $price
    ];


    $ref_table = 'items/'.$key;
    $updateQuery_result = $database->getReference($ref_table)->update($updateData);


    if($updateQuery_result)
{
    $_SESSION['status'] = 'Item Updated Successfully';
    header('Location: index.php');
}
else
{
    $_SESSION['status'] = 'Item Not Updated';
    header('Location: index.php');
}

}




if(isset($_POST['saveItem']))
{
    $item = $_POST['itemName'];
    $type = $_POST['itemType'];
    $quantity = $_POST['itemQuantity'];
    $code = $_POST['itemBarcode'];
    $price = $_POST['itemPrice'];


    $postData = [
        'item' => $item,
        'type' => $type,
        'quantity' => $quantity,
        'barcode' => $code,
        'price' => $price,
    ];

    $ref_table = 'items';
    $postRef_result = $database->getReference($ref_table)->push($postData);


if($postRef_result)
{
    $_SESSION['status'] = 'Item Added Successfully';
    header('Location: index.php');
}
else
{
    $_SESSION['status'] = 'Item Not Added';
    header('Location: index.php');
}

}



?>