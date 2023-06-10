<?php
include('authentication.php');
include('includes/header.php');

$key_child = $_GET['id'];
$ref_table = 'Stock_card';
$getData = $database->getReference($ref_table)->getChild($key_child)->getValue();
$uHistory = isset($getData['uHistory']) ? $getData['uHistory'] : array();

$totalStockIn = 0;
$totalStockOut = 0;

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

                <div class="card mt-6">
                    <div class="card-header text-center">
                        <h4>Stock Card</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-scroll custom-table-width" style="float: left;">
                                <!-- Stock Card Details -->
                                <thead>
                                    <tr>
                                        <th class="text text-center">Stock ID:</th>
                                        <td><?= isset($getData['barcode']) ? $getData['barcode'] : '' ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text text-center">Stock Name:</th>
                                        <td><?= isset($getData['item']) ? $getData['item'] : '' ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text text-center">Stock Cost:</th>
                                        <td>₱<?= isset($getData['cost']) ? $getData['cost'] : '' ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text text-center">Stock Price:</th>
                                        <td>₱<?= isset($getData['price']) ? $getData['price'] : '' ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text text-center">Stock On Hand:</th>
                                        <td><?= isset($getData['stock_on_hand']) ? $getData['stock_on_hand'] : '' ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-scroll">
                                <!-- Table Header -->
                                <thead>
                                    <tr>
                                        <th class="text">Date</th>
                                        <th class="text">Action</th>
                                        <th class="text">Quantity</th>
                                        <th class="text">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($uHistory)) {
                                        krsort($uHistory);
                                        foreach ($uHistory as $historyItem) {
                                            $date = isset($historyItem['date']) ? $historyItem['date'] : '';
                                            $action = isset($historyItem['action']) ? $historyItem['action'] : '';
                                            $changes = isset($historyItem['changes']) ? $historyItem['changes'] : array();
                                            $quantity = isset($changes['quantity']) ? $changes['quantity'] : '';
                                            $amount = '';

                                            if ($action === 'StockIn') {
                                                $amount = isset($getData['cost']) ? $getData['cost'] * $quantity : '';
                                                $totalStockIn += $amount;
                                            } elseif ($action === 'StockOut') {
                                                $amount = isset($getData['price']) ? $getData['price'] * $quantity : '';
                                                $totalStockOut += $amount;
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $date ?></td>
                                                <td><?= $action ?></td>
                                                <td><?= $quantity ?></td>
                                                <td>₱<?= $amount ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4">No Stock Record Found</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center align-middle"></th>
                                        <th rowspan="1" class="text-center align-middle">Total Stock In Value</th>
                                        <th rowspan="1" class="text-center align-middle">Total Stock Out Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th colspan="2" class="text-center align-middle"></th>
                                        <th class="text-center align-middle">₱<?= $totalStockIn ?></th>
                                        <th class="text-center align-middle">₱<?= $totalStockOut ?></th>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center mt-4">
                                <button class="btn btn-primary" onclick="printTable()">Print as PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printTable() {
        window.print();
    }
</script>
