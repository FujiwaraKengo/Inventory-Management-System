<?php
include ('authentication.php');
include ('includes/header.php');
use Picqer\Barcode\BarcodeGeneratorSvg;
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
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
                            Registered Users
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Number of Users</th>
                                    <th>Display Name</th>
                                    <th>Phone Number</th>
                                    <th>Email ID</th>
                                    <th>User Role</th>
                                    <th>Account Status</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('dbcon.php');
                                $users = $auth->listUsers();

                                $i = 1;
                                foreach ($users as $user)
                                {
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$user->displayName ?></td>
                                        <td><?=$user->phoneNumber ?></td>
                                        <td><?=$user->email ?></td>
                                        <td>
                                        <h4 class="border bg-warning">
                                            <?php
                                                $claims = $auth->getUser($user->uid)->customClaims;
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
                                        </td>
                                        <td>
                                            <?php
                                            if($user->disabled)
                                            {
                                                echo "Disabled";
                                            }
                                            else
                                            {
                                                echo "Enabled";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="user-edit.php?id=<?=$user->uid?>" class="btn btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <!-- <a href="user-delete.php" class="btn btn-danger">Delete</a> -->
                                            <form action="code.php" method="POST">
                                                <button type="submit" name="deleteUser" value="<?=$user->uid?>" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include ('includes/footer.php');
?>
