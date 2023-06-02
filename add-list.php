<?php
include ('includes/header.php');
require_once 'vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorSvg;

// Generate a 12-digit barcode number using mt_rand()
$barcodeNumber = mt_rand(100000000000, 999999999999);

// Calculate the check digit using the algorithm for TYPE_CODE_128
$checkDigit = 0;
$barcodeDigits = str_split($barcodeNumber);
for ($i = 0; $i < 12; $i++) {
    $checkDigit += ($i % 2 == 0) ? $barcodeDigits[$i] * 1 : $barcodeDigits[$i] * 3;
}
$checkDigit = (10 - ($checkDigit % 10)) % 10;

// Add the check digit to the barcode number
$barcodeNumber = intval($barcodeNumber . $checkDigit);

// Generate the SVG barcode image using Picqer\Barcode
$generator = new BarcodeGeneratorSvg();
$barcodeSvg = $generator->getBarcode($barcodeNumber, $generator::TYPE_CODE_128);


?>

<div class="container custom-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Add Stocks
                            <a href='index.php' class='btn btn-danger float-end'> Back </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Item Name</label>
                                <input type="text" name="itemName" class="form-control">
                            </div>
                            <!-- <div class="form-group mb-3">
                                <label for="">Category</label>
                                <input type="text" name="itemType" class="form-control">
                            </div> -->
                            <div class="form-group mb-3">
                                <label for="selectCategory">Category</label>
                                <div class="input-group">
                                    <select name="selectCategory" id="selectCategory" class="form-control" required>
                                        <option value="">---Select---</option>
                                        <option value="CPU">CPU</option>
                                        <option value="GPU">GPU</option>
                                        <option value="RAM">RAM</option>
                                        <option value="MOTHERBOARD">Motherboard</option>
                                        <option value="SSD">SSD</option>
                                        <option value="HDD">HDD</option>
                                        <option value="HEADSET">Headset</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Cost (PHP)</label>
                                <input type="text" name="itemCost" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Price (PHP)</label>
                                <input type="text" name="itemPrice" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Quantity</label>
                                <input type="number" name="itemQuantity" class="form-control">
                            </div>
                            <!-- <div class="form-group mb-3">
                                <label for="">Unit</label>
                                <input type="text" name="itemUnit" class="form-control">
                            </div> -->
                            <div class="form-group mb-3">
                                <label for="itemUnit">Unit</label>
                                <div class="input-group">
                                    <select name="itemUnit" id="itemUnit" class="form-control" required>
                                        <option value="">---Select---</option>
                                        <option value="Pieces">Pcs</option>
                                        <option value="Bundle">Bundle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Item Barcode:</label>
                                <div><?php echo $barcodeNumber; ?></div>
                                <input type="hidden" name="itemBarcode" value="<?php echo $barcodeNumber; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Item Barcode Image</label>
                                <div><?php echo $barcodeSvg;?></div>
                                <input type="hidden" value="<?php $barcodeSvg; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="saveItem" class="btn btn-primary">Save Item</button>
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
