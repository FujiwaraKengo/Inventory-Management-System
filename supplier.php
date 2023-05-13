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

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>
                            Supplier List
                            <a href='supplier-add.php' class='btn btn-primary float-end'> Add Contact </a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="" method="GET">
                            <div class="input-group mb-3">
                            <input type="text" name="search" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } elseif(isset($_GET['barcode'])) {echo $_GET['barcode']; } ?>" class="form-control" placeholder="Find Item...">
                                <button type="submit" class="btn btn-secondary">Search</button>
                            </div>
                        </form>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include('dbcon.php');

                                    $ref_table = 'supplier_contact';
                                    $fetchData = $database->getReference($ref_table)->getValue();

                                    if(isset($_GET['search'])){
                                        $filtervalues = strtolower($_GET['search']);
                                        $fetchData = array_filter($fetchData, function($item) use ($filtervalues){
                                            return strpos(strtolower($item['supplier']), $filtervalues) !== false || strpos(strtolower($item['address']), $filtervalues) !== false || strpos(strtolower($item['contact']), $filtervalues) !== false;
                                        });
                                    }

                                    if(!empty($fetchData)){
                                        $i=1;
                                        $item_count = 0;
                                        $_SESSION['contact_count'] = $item_count;

                                        foreach($fetchData as $key => $row)
                                        {
                                            $item_count++;
                                            ?>
                                            <tr>
                                                <td><?=$row['supplier']?></td>
                                                <td><?=$row['address']?></td>
                                                <td><?=$row['contact']?></td>
                                                <td>
                                                    <a href='supplier-edit.php?id=<?=$key;?>' class='btn btn-primary btn-sm'>Edit</a>
                                                </td>
                                                <td>
                                                    <!-- <a href='delete-items.php' class='btn btn-danger btn-sm'>Delete</a> -->
                                                    <form action="code.php" method="POST">
                                                        <button type="submit" name="delete_cont" value="<?=$key?>" class="btn btn-danger btn-sm"> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                            <td colspan='5'>No Items Found</td>
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
