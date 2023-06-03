<?php
include('authentication.php');
include('dbcon.php');
include('includes/header.php');


if(isset($_GET['id']))
{
    $key_child = $_GET['id'];
    $ref_table = 'supplier_contact';
    $getData = $database->getReference($ref_table)->getChild($key_child)->getValue();

    if($getData > 0)
    {
?>

<div class="container custom-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php
                    if (isset($_SESSION['status'])) {
                        echo "<h5 class='alert alert-success'>" . $_SESSION['status'] . "</h5>";
                        unset($_SESSION['status']);
                    }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>
                            Update Item
                            <a href='supplier.php' class='btn btn-danger float-end'> Back </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="key" value="<?=$key_child;?>">
                            <div class="form-group mb-3">
                                <label for="">Supplier Name</label>
                                <input type="text" name="supplierName" value= <?=$getData['supplier'];?> class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Address</label>
                                <input type="text" name="addressName" value= <?=$getData['address'];?> class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Contact Number</label>
                                <input type="text" name="contactNumber" value= <?=$getData['contact'];?> class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="updateContact" class="btn btn-primary">Update Contact</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
    else
    {
        $_SESSION['status'] = "Invalid ID";
        header('Location: supplier.php');
        exit();
    }
}
else
{
    $_SESSION['status'] = "Not Found";
    header('Location: supplier.php');
    exit();
}

include ('includes/footer.php');
?>
