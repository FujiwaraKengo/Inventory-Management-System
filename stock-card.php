<?php
include('dbcon.php');
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
                                        <th class="text-center align-middle">Stock ID</th>
                                        <th class="text-center align-middle">Stock Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($StockCard)) {
                                        foreach ($StockCard as $key => $row) {
                                            $totalStockIn = isset($row['total_quantity']) ? $row['total_quantity'] * $row['cost'] : 0;
                                            $totalStockOut = isset($row['total_stockOut']) ? $row['total_stockOut'] * $row['price'] : 0;
                                            ?>
                                            <tr>
                                                <td><?= $row['barcode'] ?></td>
                                                <td><?= $row['item'] ?></td>
                                                <td>
                                                    <form action="stock-cardItems.php" method="GET">
                                                        <input type="hidden" name="id" value="<?= $key ?>">
                                                        <input type="hidden" name="barcode" value="<?= $row['barcode'] ?>">
                                                        <input type="hidden" name="item" value="<?= $row['item'] ?>">
                                                        <button type="submit" class="btn btn-success btn-sm btn-fixed-width">Enter</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan='2'>No Items Found</td>
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
