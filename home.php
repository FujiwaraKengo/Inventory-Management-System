<?php
include ('authentication.php');
include ('includes/header.php');
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

            
        </div>
        
        <div class = "row g-3 my-2">
            <class class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <h3 class="fs-5">Total Items: <?php echo isset($_SESSION['item_count']) ? $_SESSION['item_count'] : 0; ?></p>
                    </div>
                </div>
            </class>

            <class class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <h3 class="fs-5">Lowest Stock Item: </p>
                    </div>
                </div>
            </class>

            <class class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <h3 class="fs-5">Out Of Stock Items: </p>
                    </div>
                </div>
            </class>

            <class class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                    <div>
                        <h3 class="fs-5">Most Stock Item: </p>
                    </div>
                </div>
            </class>
        </div>
        

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Item</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>