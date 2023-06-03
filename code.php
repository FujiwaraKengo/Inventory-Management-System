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

    // Get the item data before deleting
    $itemData = $database->getReference('items')->getChild($delete_item)->getValue();

    // Remove the item
    $deleteQuery_result = $database->getReference('items')->getChild($delete_item)->remove();

    if($deleteQuery_result)
    {
        // Log the delete action
        $historyData = [
            'barcode' => $itemData['barcode'],
            'name' => $itemData['item'],
            'date' => date('Y-m-d H:i:s'),
            'action' => 'Deleted'
        ];

        $database->getReference('history')->push($historyData);

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
    $type = $_POST['selectCategory'];
    $quantity = intval($_POST['itemQuantity']);
    $code = intval($_POST['itemBarcode']);
    $cost = intval($_POST['itemCost']);
    $price = intval($_POST['itemPrice']);
    $unit = $_POST['itemUnit'];

    $updateData = [
        'item' => $item,
        'type' => $type,
        'quantity' => $quantity,
        'barcode' => $code,
        'cost' => $cost,
        'price' => $price,
        'unit' => $unit,
    ];


    $ref_table = 'items/'.$key;

    // Get the existing item data
    $existingData = $database->getReference('items/' . $key)->getValue();

    // Compare the updated data with the existing data
    $changes = [];
    foreach ($updateData as $field => $value) {
        if ($existingData[$field] != $value) {
            $changes[$field] = $value;
        }
    }
    
    $updateQuery_result = $database->getReference($ref_table)->update($updateData);


    if($updateQuery_result)
    {
        $stockCardRef = $database->getReference('Stock_card')->getChild($key);
        $stockCardData = $stockCardRef->getValue();
        $stockCardData['cost'] = $cost;
        $stockCardData['price'] = $price;
        $stockCardRef->set($stockCardData);


        $historyData = [
            'date' => date('Y-m-d H:i:s'),
            'name' => $updateData['item'],
            'barcode' => $code,
            'changes' => $changes,
            'action' => 'Updated',

        ];

        $database->getReference('history')->push($historyData);
        
        $_SESSION['status'] = 'Item Updated Successfully';
        header('Location: index.php');
    }
    else
    {
        $_SESSION['status'] = 'Item Not Updated';
        header('Location: index.php');
    }

}




if (isset($_POST['saveItem'])) {
    $item = $_POST['itemName'];
    $type = $_POST['selectCategory'];
    $quantity = intval($_POST['itemQuantity']);
    $code = intval($_POST['itemBarcode']);
    $cost = intval($_POST['itemCost']);
    $price = intval($_POST['itemPrice']);
    $unit = $_POST['itemUnit'];

    $postData = [
        'item' => $item,
        'type' => $type,
        'quantity' => $quantity,
        'barcode' => $code,
        'cost' => $cost,
        'price' => $price,
        'unit' => $unit,
    ];

    $ref_table = 'items';
    $postRef_result = $database->getReference($ref_table)->push($postData);

    if ($postRef_result) {

        $itemUid = $postRef_result->getKey();

        // Create a new stock card entry with initial total quantity
        $stockCardData = [
            'item' => $item,
            'barcode' => $code,
            'cost' => $cost,
            'price' => $price,
            'total_quantity' => $quantity,
        ];

        $stockCardRef = $database->getReference('Stock_card')->getChild($itemUid);
        $stockCardRef->set($stockCardData);

        $historyData = [
            'date' => date('Y-m-d H:i:s'),
            'barcode' => $code,
            'name' => $item,
            'action' => 'New Stock Added',
        ];

        $database->getReference('history')->push($historyData);

        $_SESSION['status'] = 'Item Added Successfully';
        header('Location: index.php');
    } else {
        $_SESSION['status'] = 'Item Not Added';
        header('Location: index.php');
    }
}



if (isset($_POST['stock_in']) || isset($_POST['stock_out'])) {
    $itemKey = $_POST['item_key'];
    $currentQuantity = intval($_POST['current_quantity']);

    // Determine if StockIn or StockOut button was clicked
    if (isset($_POST['stock_in'])) {
        $quantityChange = intval($_POST['stock_in_quantity']);
        $newQuantity = $currentQuantity + $quantityChange;
        $action = 'StockIn';

        // Update the total_quantity in the Stock_card table
        $stockCardRef = $database->getReference('Stock_card')->getChild($itemKey);
        $stockCardData = $stockCardRef->getValue();
        $stockCardData['total_quantity'] += $quantityChange;
        $stockCardRef->set($stockCardData);
        
    } elseif (isset($_POST['stock_out'])) {
        $quantityChange = intval($_POST['stock_out_quantity']);
        
        // Check if the StockOut quantity is greater than the current quantity
        if ($quantityChange > $currentQuantity) {
            $_SESSION['status'] = 'Not Enough Quantity for The Stock';
            header('Location: index.php');
            exit(); // Stop further execution of the code
        }
        
        $newQuantity = $currentQuantity - $quantityChange;
        $newQuantity = max(0, $newQuantity); // Ensure quantity doesn't go below zero
        $action = 'StockOut';

        $stockCardRef = $database->getReference('Stock_card')->getChild($itemKey);
        $stockCardData = $stockCardRef->getValue();
        $stockCardData['total_stockOut'] += $quantityChange;
        $stockCardRef->set($stockCardData);
    }

    // Get the item data before updating the quantity
    $itemData = $database->getReference('items')->getChild($itemKey)->getValue();

    // Update the quantity in the database
    $updateData = [
        'quantity' => $newQuantity
    ];

    $ref_table = 'items/'.$itemKey;
    $updateQuery_result = $database->getReference($ref_table)->update($updateData);

    if ($updateQuery_result) {

        // Prepare the history data
        $historyData = [
            'barcode' => $itemData['barcode'],
            'name' => $itemData['item'],
            'date' => date('Y-m-d H:i:s'),
            'action' => $action,
            'changes' => [
                'quantity' => $quantityChange
            ]
        ];

        // Add the history data to the database
        $database->getReference('history')->push($historyData);

        $_SESSION['status'] = 'Quantity Updated Successfully';
        header('Location: index.php');
    } else {
        $_SESSION['status'] = 'Quantity Not Updated';
        header('Location: index.php');
    }
}




// -------------------------------For the Contact List--------------------------------//
if(isset($_POST['addContact']))
{
    $supplier = $_POST['supplierName'];
    $address = $_POST['addressName'];
    $contact = $_POST['contactNumber'];


    $postData = [
        'supplier' => $supplier,
        'address' => $address,
        'contact' => $contact,
    ];

    $ref_table = 'supplier_contact';
    $postRef_result = $database->getReference($ref_table)->push($postData);


if($postRef_result)
{
    $_SESSION['status'] = 'Item Added Successfully';
    header('Location: supplier.php');
}
else
{
    $_SESSION['status'] = 'Item Not Added';
    header('Location: supplier.php');
}

}

if(isset($_POST['updateContact']))
{
    $key = $_POST['key'];
    $supplier = $_POST['supplierName'];
    $address = $_POST['addressName'];
    $contact = $_POST['contactNumber'];

    $updateData = [
        'supplier' => $supplier,
        'address' => $address,
        'contact' => $contact,
    ];


    $ref_table = 'supplier_contact/'.$key;
    $updateQuery_result = $database->getReference($ref_table)->update($updateData);


    if($updateQuery_result)
{
    $_SESSION['status'] = 'Item Updated Successfully';
    header('Location: supplier.php');
}
else
{
    $_SESSION['status'] = 'Item Not Updated';
    header('Location: supplier.php');
}

}

if(isset($_POST['delete_cont']))
{
    $delete_item = $_POST['delete_cont'];

    $ref_table = 'supplier_contact/'.$delete_item;
    $deleteQuery_result = $database->getReference($ref_table)->remove();

    if($deleteQuery_result)
    {
        $_SESSION['status'] = 'Item Deleted Successfully';
        header('Location: supplier.php');
    }
    else
    {
        $_SESSION['status'] = 'Item Not Deleted';
        header('Location: supplier.php');
    }
}

?>