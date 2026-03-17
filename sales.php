<?php
ob_start(); 
	
include "connection.php";
include "header.php";
include "auth_check.php";

$sql = "SELECT * FROM manufacturing";
$result = mysqli_query($conn, $sql);

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $product_unit = $_POST['product_unit'];
    $rm_unitprice = $_POST['rm_unitprice'];
    $profit = $_POST['profit'];
    $unitsale = $_POST['unitsale'];
    $product_unitprice = ($product_unit * $rm_unitprice) + ($profit * $product_unit * $rm_unitprice);
    $total_price = $product_unitprice * $unitsale;
    $new_unit = $product_unit - $unitsale;

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $actual_time = date('Y-m-d H:i:s');

    $edited_des = "Sales of $name is made.";

    // Retrieve control_time from manufacturing table
    $control_time_query = mysqli_query($conn, "SELECT control_time FROM manufacturing WHERE id = '$id'");
    $control_time_row = mysqli_fetch_assoc($control_time_query);
    $control_time = $control_time_row['control_time'];

    if ($actual_time >= $control_time) { // Check if the current time exceeds control_time
        if ($product_unit >= $unitsale) {
            $insertsql = "INSERT INTO sales(name, unit_sold, unitprice, total_price, sales_time) VALUES ('$name', '$unitsale', '$product_unitprice', '$total_price','$actual_time')";

            if ($conn->query($insertsql) === TRUE) {
                echo " Sell successfully";
                header('location:sales.php');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $update_quantity_query = "UPDATE `manufacturing` SET product_unit = '$new_unit'  WHERE id = '$id'";

            if ($conn->query($update_quantity_query) === TRUE) {
                echo " Update successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $out_of_stock_message = "Not Enough Stock";
        }
    } else {
        $product_in_production_message = "Product $name is still in production.";
    }
}

ob_end_flush();
?>
<html>
<head>
    <title></title>
</head>
<body>
    <div class="container">
        <h5>Sales</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <!--<th scope="col">#</th>-->
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Unit</th>
                    <th scope="col">Product Unit Price (RM)</th>
                    <th scope="col">Sell Unit</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        $unit_required = $row['unit_required'];
                        $rm_unitprice = $row['rm_unitprice'];
                        $profit = $row['profit'];
                        $product_unitprice = ($unit_required * $rm_unitprice) + ($profit * $unit_required * $rm_unitprice);
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                            <tr>
                                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                                <input type="hidden" name="name" value="<?php echo $row['name'];?>">
                                <input type="hidden" name="product_unit" value="<?php echo $row['product_unit'];?>">
                                <input type="hidden" name="rm_unitprice" value="<?php echo $row['rm_unitprice'];?>">
                                <input type="hidden" name="profit" value="<?php echo $row['profit'];?>">
                                <td><?php echo $row['name'];?></td>
                                <td><?php echo $row['product_unit'];?></td>
                                <td><?php echo $product_unitprice ?></td>
                                <td>
                                    <div class="mb-3">
                                        <input type="number" name="unitsale" class="form-control" id="exampleInputUnit">
                                    </div>
                                </td>
                                <td><button type="submit" class="btn btn-primary" name="submit">Sell Now</button></td>
                            </tr>
                        </form>
                        <?php
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </tbody>
        </table>
        <?php if (isset($out_of_stock_message) && $out_of_stock_message != "") { ?>
            <p><?php echo $out_of_stock_message; ?></p>
        <?php } ?>
        <?php if (isset($product_in_production_message) && $product_in_production_message != "") { ?>
            <p><?php echo $product_in_production_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
