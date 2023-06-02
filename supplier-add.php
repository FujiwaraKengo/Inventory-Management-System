<?php
include ('includes/header.php');
?>

<div class="container custom-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Add Contact
                            <a href='supplier.php' class='btn btn-danger float-end'> Back </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Supplier Name</label>
                                <input type="text" name="supplierName" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Address</label>
                                <input type="text" name="addressName" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Contact Number</label>
                                <input type="text" name="contactNumber" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="addContact" class="btn btn-primary">Save Contact</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include ('includes/footer.php');
?>
