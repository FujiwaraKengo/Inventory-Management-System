<?php
include('authentication.php');
include('includes/header.php');

$StockCard = $database->getReference('Stock_card')->getValue();
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
                        <h4>Stock Card</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-scroll">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center align-middle">Stock ID</th>
                                        <th rowspan="2" class="text-center align-middle">Stock Name</th>
                                        <th colspan="3" class="text-center align-middle">Stock In</th>
                                        <th colspan="3" class="text-center align-middle">Stock Out</th>
                                    </tr>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Cost</th>
                                        <th>Value</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(!empty($StockCard))
                                        {
                                            foreach($StockCard as $key => $row)
                                            {
                                                $totalStockIn = isset($row['total_quantity']) ? $row['total_quantity'] * $row['cost'] : 0;
                                                $totalStockOut = isset($row['total_stockOut']) ? $row['total_stockOut'] * $row['price'] : 0;
                                                ?>
                                                <tr>
                                                    <td><?=$row['barcode']?></td>
                                                    <td><?=$row['item']?></td>
                                                    <td>
                                                        <?php
                                                        if (!empty($row['total_quantity'])) {
                                                            echo $row['total_quantity'];
                                                        } else {
                                                            echo 'No StockIn Found';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?=$row['cost']?></td>
                                                    <td><?=$totalStockIn?></td>
                                                    <td>
                                                        <?php
                                                        if (!empty($row['total_stockOut'])) {
                                                            echo $row['total_stockOut'];
                                                        } else {
                                                            echo 'No StockOut Found';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?=$row['price']?></td>
                                                    <td><?=$totalStockOut?></td>
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
    </div>

    <?php
    include('includes/footer.php');
    ?>

</div>
