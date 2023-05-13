<?php
include('dbcon.php');
include('includes/header.php');
require_once 'vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorSVG;

if(isset($_GET['id']))
{
    $key_child = $_GET['id'];
    $ref_table = 'items';
    $getData = $database->getReference($ref_table)->getChild($key_child)->getValue();

    $barcode = $getData['barcode'];
    $generator = new BarcodeGeneratorSVG();

    if($getData > 0)
    {
        
        $barcode = $getData['barcode'];
        $generator = new BarcodeGeneratorSVG();
        $barcode_svg = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        Update Item
                        <a href='index.php' class='btn btn-danger float-end'> Back </a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="key" value="<?=$key_child;?>">
                        <div class="form-group mb-3">
                            <label for="">Item Name</label>
                            <input type="text" name="itemName" value= <?=$getData['item'];?> class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Item Type</label>
                            <input type="text" name="itemType" value= <?=$getData['type'];?> class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Item Price</label>
                            <input type="text" name="itemPrice" value= <?=$getData['price'];?> class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Quantity</label>
                            <input type="number" name="itemQuantity" value= <?=$getData['quantity'];?> class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Item Barcode:</label>
                            <span name="itemBarcode"><?= $getData['barcode']; ?></span>
                            <input type="hidden" name="itemBarcode" value="<?= $getData['barcode']; ?>">
                            
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Barcode Image:</label>
                            <div><?= $barcode_svg ?></div>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" name="updateItem" class="btn btn-primary">Update Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }
    else
    {
        $_SESSION['status'] = "Invalid ID";
        header('Location: index.php');
        exit();
    }
}
else
{
    $_SESSION['status'] = "Not Found";
    header('Location: index.php');
    exit();
}

include ('includes/footer.php');
?>
