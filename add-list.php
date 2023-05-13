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

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        Add Items
                        <a href='index.php' class='btn btn-danger float-end'> Back </a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <div class="form-group mb-3">
                            <label for="">Item Name</label>
                            <input type="text" name="itemName" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Item Type</label>
                            <input type="text" name="itemType" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Item Price</label>
                            <input type="text" name="itemPrice" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Quantity</label>
                            <input type="number" name="itemQuantity" class="form-control">
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

<?php
include ('includes/footer.php');
?>
