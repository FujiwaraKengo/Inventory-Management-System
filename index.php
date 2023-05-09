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
                            Inventory
                            <a href='add-list.php' class='btn btn-primary float-end'> Add Item </a>
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
                                    <th>Type</th>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Barcode</th>
                                    <th>Barcode Image</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include('dbcon.php');

                                    $ref_table = 'items';
                                    $fetchData = $database->getReference($ref_table)->getValue();

                                    if(isset($_GET['search'])){
                                        $filtervalues = strtolower($_GET['search']);
                                        $fetchData = array_filter($fetchData, function($item) use ($filtervalues){
                                            return strpos(strtolower($item['item']), $filtervalues) !== false || strpos(strtolower($item['barcode']), $filtervalues) !== false || strpos(strtolower($item['type']), $filtervalues) !== false;
                                        });
                                    }

                                    if(!empty($fetchData)){
                                        $i=1;
                                        $item_count = 0;
                                        $_SESSION['item_count'] = $item_count;

                                        foreach($fetchData as $key => $row)
                                        {
                                            // Generate barcode SVG
                                            $barcodeGenerator = new Picqer\Barcode\BarcodeGeneratorSVG();
                                            $barcodeSvg = $barcodeGenerator->getBarcode($row['barcode'], $barcodeGenerator::TYPE_EAN_13);

                                            // Set barcode image string in row data
                                            $row['barcodeimage'] = $barcodeSvg;
                                            $item_count++;
                                            ?>
                                            <tr>
                                                <td><?=$row['type']?></td>
                                                <td><?=$row['item']?></td>
                                                <td><?=$row['price']?></td>
                                                <td><?=$row['quantity']?></td>
                                                <td><?=$row['barcode']?></td>
                                                <td><?=$row['barcodeimage']?></td>
                                                <td>
                                                    <a href='edit-items.php?id=<?=$key;?>' class='btn btn-primary btn-sm'>Edit</a>
                                                </td>
                                                <td>
                                                    <!-- <a href='delete-items.php' class='btn btn-danger btn-sm'>Delete</a> -->
                                                    <form action="code.php" method="POST">
                                                        <button type="submit" name="delete_btn" value="<?=$key?>" class="btn btn-danger btn-sm"> Delete</button>
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
                                            <td colspan='8'>No Items Found</td>
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

<script>
    // Get the barcode input element
    var barcodeInput = document.getElementById('barcode-input');

    // Listen for input events on the barcode input element
    barcodeInput.addEventListener('input', function() {
        // If the input value is a 13-digit number, submit the form with the barcode value
        if (/^\d{13}$/.test(this.value)) {
            // Set the search input value to the barcode value
            document.getElementsByName('search')[0].value = this.value;

            // Submit the form
            document.getElementsByTagName('form')[0].submit();
        }
    });
</script>