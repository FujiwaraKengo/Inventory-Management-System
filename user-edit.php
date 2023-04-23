<?php
include ('authentication.php');
include ('includes/header.php');
include ('dbcon.php');

?>

    <div class="container">
            <?php
            if(isset($_SESSION['status']))
            {
                echo "<h5 class='alert alert-success'>".$_SESSION['status']."</h5>";
                unset($_SESSION['status']);
            }
            ?>
        <div class="row justify-content-center">
            <div class="col-md-6">

                <?php
                if(isset($_SESSION['status']))
                {
                    echo "<h5 class='alert alert-success'>".$_SESSION['status']."</h5>";
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>
                            Update User Data
                            <a href='user-list.php' class='btn btn-danger float-end'> Back </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">


                            <?php
                                include('dbcon.php');

                                if(isset($_GET['id']))
                                {
                                    $uid = $_GET['id'];

                                    try {
                                        $user = $auth->getUser($uid);
                                        
                                        ?>
                                            <input type="hidden" name="user_id" value="<?=$uid;?>">
                                            <div class="form-group mb-3">
                                                <label for="">Display Name</label>
                                                <input type="text" name="fname" value="<?=$user->displayName;?>" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="">Phone Number</label>
                                                <input type="text" name="phone" value="<?=$user->phoneNumber;?>" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <button type="submit" name="updateUser" class="btn btn-primary">Update User</button>
                                            </div>
                                        <?php

                                    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e)
                                    {
                                        echo $e->getMessage();
                                    }
                                }
                                
                            ?>

                        </form>
                    </div>
                </div>
            </div>
                                
            <?php
                if(isset($_SESSION['$verify_user_id']))
                {
                    $currentUser = $auth->getUser($_SESSION['$verify_user_id']);
                    $claims = $currentUser->customClaims;

                    if(isset($claims['superAdmin']) || isset($claims['admin']))
                    {
                        ?>
                        <div class="col-md-6">
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Account Status Controller</h4>
                                </div> 
                                <div class="card-body">
                                    <form action="code.php" method="POST">
                                        
                                        <?php
                                        if(isset($_GET['id']))
                                        {
                                            $uid = $_GET['id'];
                                            try {
                                                $user = $auth->getUser($uid);
                                                ?>
                                                <input type="hidden" name="EnaDisaUser" value="<?= $uid; ?>">
                                                <div class="input-group mb-3">
                                                    <select name="selectAccStatus" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <option value="disable">Disable</option>
                                                        <option value="enable">Enable</option>
                                                    </select>
                                                    <button type="submit" name="accStatus" class="input-group-text btn btn-primary">
                                                        Submit
                                                    </button>
                                                </div>
                                                <?php

                                            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                                                echo $e->getMessage();
                                            }
                                        }
                                        else
                                        {
                                            echo "No User ID Found";
                                        }
                                        ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            ?>

            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6">
                <div class="card mt-1 mb-5">
                    <div class="card-header">
                        <h4>Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">    
                            <?php
                            if(isset($_GET['id']))
                            {
                                $uid = $_GET['id'];
                                try
                                {
                                    $user = $auth->getUser($uid);
                                    ?>
                                    <input type="hidden" name="changePassUser" value="<?=$uid;?>">
                                    <div class="form-group mb-3">
                                        <label for="">New Password</label>
                                        <input type="text" name="newPass" required class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="">Confirm Password</label>
                                        <input type="text" name="retypePass" required class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <button type="submit" name="changePass" class="btn btn-primary">Submit</button>
                                    </div>
                                    <?php
                                }
                                catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e)
                                {
                                    echo $e->getMessage();
                                }
                            }
                            else
                            {
                                echo "No ID Found";
                            }
                            ?>      
                        </form>
                    </div>
                </div>
                
            </div>
            
            <?php
            if(isset($_SESSION['$verify_user_id']))
            {
                $currentUser = $auth->getUser($_SESSION['$verify_user_id']);
                $claims = $currentUser->customClaims;

                if(isset($claims['superAdmin']) == true)
                {
                    ?>
                        <div class="col-md-6">
                            <div class="card mt-4 mb-4">
                                <div class="card-header">
                                    <h4>User Roles</h4>
                                </div>
                                <div class="card-body">
                                    <form action="code.php" method="POST">
                                        
                                        <?php
                                            if(isset($_GET['id']))
                                            {
                                                $uid = $_GET['id'];
                                                ?>
                                                

                                                <input type="hidden" name="roleUserId" value="<?=$uid; ?>">
                                                <div class="form-group mb-3">
                                                    <select name="roleAs" class="form-control" required>
                                                        <option value="">Select Role</option>
                                                        <option value="superAdmin">Super Admin</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="noRole">Regular Employee</option>
                                                    </select>
                                                </div>
                                                <label for="">Current User Role:</label>
                                                <h4 class="border bg-warning p-2">
                                                    <?php
                                                        $claims = $auth->getUser($uid)->customClaims;
                                                        if(isset($claims['superAdmin']) == true)
                                                        {
                                                            echo "Super Admin";
                                                        }
                                                        elseif(isset($claims['admin']) == true)
                                                        {
                                                            echo "Admin";
                                                        }
                                                        elseif($claims == null)
                                                        {
                                                            echo "Regular";
                                                        }
                                                    ?>
                                                </h4>
                                                <div class="form-group mb-3">
                                                    <button type="submit" name="userRole" class="btn btn-primary">Submit</button>
                                                </div>
                                                <?php
                                            }
                                        ?>

                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            }
            ?>

        </div>
    </div>

<?php
include('includes/footer.php');
?>