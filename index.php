<?php
include ('authentication.php');
include ('includes/header.php');
use Picqer\Barcode\BarcodeGeneratorSvg;
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

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>
                            Stock Inventory Item
                            <a href='add-list.php' class='btn btn-primary float-end'> Add Stock </a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="input-group mb-3">
                            <input type="text" id="searchInput" name="search" required
                                value="<?php if (isset($_GET['search'])) {echo $_GET['search'];} elseif (isset($_GET['barcode'])) {echo $_GET['barcode'];} ?>"
                                class="form-control" placeholder="Find Item...">
                            <button id="clearButton" class="btn btn-secondary" type="button">Clear</button>
                        </div>

                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Item</th>
                                        <th>Cost</th>
                                        <th>Price(PHP)</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Barcode</th>
                                        <th>Barcode Image</th>
                                        <th>Action</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="searchResults">
                                    <?php
                                    include('dbcon.php');

                                    $ref_table = 'items';
                                    $fetchData = $database->getReference($ref_table)->getValue();

                                    if (isset($_GET['search'])) {
                                        $filtervalues = strtolower($_GET['search']);
                                        $fetchData = array_filter($fetchData, function ($item) use ($filtervalues) {
                                            return strpos(strtolower($item['item']), $filtervalues) !== false || strpos(strtolower($item['barcode']), $filtervalues) !== false || strpos(strtolower($item['type']), $filtervalues) !== false;
                                        });
                                    }

                                    if (!empty($fetchData)) {
                                        $i = 1;
                                        $item_count = 0;
                                        $_SESSION['item_count'] = $item_count;

                                        foreach ($fetchData as $key => $row) {
                                            // Generate barcode SVG
                                            $barcodeGenerator = new Picqer\Barcode\BarcodeGeneratorSVG();
                                            $barcodeSvg = $barcodeGenerator->getBarcode($row['barcode'], $barcodeGenerator::TYPE_CODE_128);

                                            // Set barcode image string in row data
                                            $row['barcodeimage'] = $barcodeSvg;
                                            $item_count++;
                                            ?>
                                    <tr>
                                        <td><?= $row['type'] ?></td>
                                        <td><?= $row['item'] ?></td>
                                        <td><?= $row['cost'] ?></td>
                                        <td><?= $row['price'] ?></td>
                                        <td><?= $row['quantity'] ?></td>
                                        <td><?= $row['unit'] ?></td>
                                        <td><?= $row['barcode'] ?></td>
                                        <td><?= $row['barcodeimage'] ?></td>
                                        <td>
                                            <form action="code.php" method="POST" class="stock-form">
                                                <input type="hidden" name="item_key" value="<?= $key ?>">
                                                <input type="hidden" name="current_quantity" value="<?= $row['quantity'] ?>">
                                                <?php if ($row['quantity'] <= 0) : ?>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#outOfStockModal<?=$key?>">Out
                                                    of Stock</button>
                                                <?php elseif ($row['quantity'] >= 20) : ?>
                                                <button type="button" class="btn btn-purple btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#overStockModal<?=$key?>">Overstock</button>
                                                <?php else : ?>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#stockInModal<?=$key?>">Stock
                                                    In</button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#stockOutModal<?=$key?>">Stock
                                                    Out</button>
                                                <?php endif; ?>
                                            </form>
                                        </td>

                                        <!-- Stock In Modal -->
                                        <div class="modal fade" id="stockInModal<?=$key?>" tabindex="-1"
                                            aria-labelledby="stockInModalLabel<?=$key?>" aria-hidden="true">
                                            <!-- modal content -->
                                        </div>

                                        <!-- Stock Out Modal -->
                                        <div class="modal fade" id="stockOutModal<?=$key?>" tabindex="-1"
                                            aria-labelledby="stockOutModalLabel<?=$key?>" aria-hidden="true">
                                            <!-- modal content -->
                                        </div>

                                        <!-- Out of Stock Modal -->
                                        <div class="modal fade" id="outOfStockModal<?=$key?>" tabindex="-1"
                                            aria-labelledby="outOfStockModalLabel<?=$key?>" aria-hidden="true">
                                            <!-- modal content -->
                                        </div>

                                        <!-- OverStock Modal -->
                                        <div class="modal fade" id="overStockModal<?=$key?>" tabindex="-1"
                                            aria-labelledby="overStockModalLabel<?=$key?>" aria-hidden="true">
                                            <!-- modal content -->
                                        </div>

                                        <td>
                                            <a href='edit-items.php?id=<?=$key;?>' class='btn btn-primary btn-sm'>Edit</a>
                                        </td>
                                        <td>
                                            <form action="code.php" method="POST">
                                                <button type="submit" name="delete_btn" value="<?=$key?>"
                                                    class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        ?>
                                    <tr>
                                        <td colspan='11'>No Items Found</td>
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
    </div>
</div>

    <!-- Old Code Dont Remove If New One Stopped Working
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
                            Stock Inventory Item
                            <a href='add-list.php' class='btn btn-primary float-end'> Add Stock </a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="" method="GET">
                            <div class="input-group mb-3">
                            <input type="text" id="searchInput" name="search" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } elseif(isset($_GET['barcode'])) {echo $_GET['barcode']; } ?>" class="form-control" placeholder="Find Item...">
                                <button type="submit" class="btn btn-secondary">Search</button>
                            </div>
                        </form>

                        <div class="table-responsive table-responsive-sm">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Item</th>
                                        <th>Cost</th>
                                        <th>Price(PHP)</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Barcode</th>
                                        <th>Barcode Image</th>
                                        <th>Action</th>
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
                                                $barcodeSvg = $barcodeGenerator->getBarcode($row['barcode'], $barcodeGenerator::TYPE_CODE_128);

                                                // Set barcode image string in row data
                                                $row['barcodeimage'] = $barcodeSvg;
                                                $item_count++;
                                                ?>
                                                <tr>
                                                    <td><?=$row['type']?></td>
                                                    <td><?=$row['item']?></td>
                                                    <td><?=$row['cost']?></td>
                                                    <td><?=$row['price']?></td>
                                                    <td><?=$row['quantity']?></td>
                                                    <td><?=$row['unit']?></td>
                                                    <td><?=$row['barcode']?></td>
                                                    <td><?=$row['barcodeimage']?></td>
                                                    <td>
                                                        <form action="code.php" method="POST" class="stock-form">
                                                            <input type="hidden" name="item_key" value="<?=$key?>">
                                                            <input type="hidden" name="current_quantity" value="<?=$row['quantity']?>">
                                                            <?php if ($row['quantity'] <= 0): ?>
                                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#outOfStockModal<?=$key?>">Out of Stock</button>
                                                            <?php elseif ($row['quantity'] >= 20): ?>
                                                                <button type="button" class="btn btn-purple btn-sm" data-bs-toggle="modal" data-bs-target="#overStockModal<?=$key?>">Overstock</button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#stockInModal<?=$key?>">Stock In</button>
                                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#stockOutModal<?=$key?>">Stock Out</button>
                                                            <?php endif; ?>
                                                        </form>
                                                    </td>

                                                    //Stock In Modal//
                                                    <div class="modal fade" id="stockInModal<?=$key?>" tabindex="-1" aria-labelledby="stockInModalLabel<?=$key?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="stockInModalLabel<?=$key?>">Stock In</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="code.php" method="POST">
                                                                        <input type="hidden" name="item_key" value="<?=$key?>">
                                                                        <input type="hidden" name="current_quantity" value="<?=$row['quantity']?>">

                                                                        <div class="mb-3">
                                                                            <label for="stockInQuantity<?=$key?>" class="form-label">Quantity</label>
                                                                            <input type="number" class="form-control" id="stockInQuantity<?=$key?>" name="stock_in_quantity" required>
                                                                        </div>

                                                                        <button type="submit" name="stock_in" class="btn btn-success">Stock In</button>
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    //Stock Out Modal//
                                                    <div class="modal fade" id="stockOutModal<?=$key?>" tabindex="-1" aria-labelledby="stockOutModalLabel<?=$key?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="stockOutModalLabel<?=$key?>">Stock Out</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="code.php" method="POST">
                                                                        <input type="hidden" name="item_key" value="<?=$key?>">
                                                                        <input type="hidden" name="current_quantity" value="<?=$row['quantity']?>">

                                                                        <div class="mb-3">
                                                                            <label for="stockOutQuantity<?=$key?>" class="form-label">Quantity</label>
                                                                            <input type="number" class="form-control" id="stockOutQuantity<?=$key?>" name="stock_out_quantity" required>
                                                                        </div>

                                                                        <button type="submit" name="stock_out" class="btn btn-danger">Stock Out</button>
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    //Out of Stock Modal
                                                    <div class="modal fade" id="outOfStockModal<?=$key?>" tabindex="-1" aria-labelledby="outOfStockModalLabel<?=$key?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="outOfStockModalLabel<?=$key?>">Out of Stock</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>The item "<?=$row['item']?>" is currently out of stock. Please Add.</p>
                                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockInModal<?=$key?>" data-bs-dismiss="modal">Stock In</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    //OverStock Modal//
                                                    <div class="modal fade" id="overStockModal<?=$key?>" tabindex="-1" aria-labelledby="overStockModalLabel<?=$key?>" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="overStockModalLabel<?=$key?>">OverStock</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>The item "<?=$row['item']?>" is overstocked. Please stock out before adding more quantity.</p>
                                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stockOutModal<?=$key?>" data-bs-dismiss="modal">Stock Out</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <td>
                                                        <a href='edit-items.php?id=<?=$key;?>' class='btn btn-primary btn-sm'>Edit</a>
                                                    </td>
                                                    <td>
                                                        <a href='delete-items.php' class='btn btn-danger btn-sm'>Delete</a>
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
                                                <td colspan='11'>No Items Found</td>
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
    </div>
    Dont Touch Until This One -->


<?php
include ('includes/footer.php');
?>

<script>

    var clearButton = document.getElementById('clearButton');
    var searchInput = document.getElementById('searchInput');
    var tableRows = document.getElementById('searchResults').getElementsByTagName('tr');

    clearButton.addEventListener('click', function () {
        searchInput.value = ''; // Clear the input field

        // Show all table rows
        for (var i = 0; i < tableRows.length; i++) {
            tableRows[i].style.display = '';
        }

        searchInput.focus(); // Set focus back to the input field
    });

    // Get the search input element
    var searchInput = document.getElementById('searchInput');

    // Listen for input events on the search input element
    searchInput.addEventListener('input', function () {
        var filter = searchInput.value.toUpperCase();
        var table = document.getElementById('searchResults');
        var rows = table.getElementsByTagName('tr');

        // Loop through all table rows and hide those that don't match the search query
        for (var i = 0; i < rows.length; i++) {
            var type = rows[i].getElementsByTagName('td')[0];
            var item = rows[i].getElementsByTagName('td')[1];
            var cost = rows[i].getElementsByTagName('td')[2];
            var price = rows[i].getElementsByTagName('td')[3];
            var quantity = rows[i].getElementsByTagName('td')[4];
            var unit = rows[i].getElementsByTagName('td')[5];
            var barcode = rows[i].getElementsByTagName('td')[6];

            if (type && item && cost && price && quantity && unit && barcode) {
                var typeText = type.textContent || type.innerText;
                var itemText = item.textContent || item.innerText;
                var costText = cost.textContent || cost.innerText;
                var priceText = price.textContent || price.innerText;
                var quantityText = quantity.textContent || quantity.innerText;
                var unitText = unit.textContent || unit.innerText;
                var barcodeText = barcode.textContent || barcode.innerText;

                var searchText =
                    typeText.toUpperCase() +
                    itemText.toUpperCase() +
                    costText.toUpperCase() +
                    priceText.toUpperCase() +
                    quantityText.toUpperCase() +
                    unitText.toUpperCase() +
                    barcodeText.toUpperCase();

                if (searchText.indexOf(filter) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    });
</script>

<!-- This an Old Code for search bar dont Delete if new script didnt work
<script>
    // Get the search input element
    var searchInput = document.getElementById('searchInput');

    // Listen for input events on the search input element
    searchInput.addEventListener('input', function() {
        // Submit the form with the updated search input value
        document.getElementsByTagName('form')[0].submit();
    });

    // Listen for keydown events on the search input element
    searchInput.addEventListener('keydown', function(event) {
        // Check if the Enter key (key code 13) is pressed
        if (event.keyCode === 13) {
            event.preventDefault(); // Prevent form submission

            // Perform the search using the current value in the search input field
            performSearch();
        }
    });

    // Function to perform the search
    function performSearch() {
        // Get the barcode value from the search input field
        var barcode = searchInput.value.trim();

        // Set the search input value to the barcode value
        searchInput.value = barcode;

        // Submit the form
        document.getElementsByTagName('form')[0].submit();
    }
</script>
-->
