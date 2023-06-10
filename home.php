<?php
include('authentication.php');
include('includes/header.php');

$historyData = $database->getReference('history')->orderByChild('date')->getSnapshot()->getValue();
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
                            Record Log
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-scroll">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Stock ID</th>
                                        <th>Stock Name</th>
                                        <th>Action</th>
                                        <th>Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($historyData)) {
                                        // Sort the history data array in descending order based on the date
                                        krsort($historyData);

                                        foreach ($historyData as $key => $value) {
                                        ?>
                                            <tr>
                                                <td><?= isset($value['date']) ? $value['date'] : '' ?></td>
                                                <td><?= isset($value['barcode']) ? $value['barcode'] : '' ?></td>
                                                <td><?= isset($value['name']) ? $value['name'] : '' ?></td>
                                                <td><?= isset($value['action']) ? $value['action'] : '' ?></td>
                                                <td>
                                                    <?php
                                                    if (isset($value['changes']) && is_array($value['changes'])) {
                                                        foreach ($value['changes'] as $field => $change) {
                                                            echo $field . ': ' . $change . '<br>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan='5'>No History Log Found</td>
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
