<?php
ob_start();

include "header.php";
include "connection.php";
include "auth_check.php";

$sql = "SELECT * FROM manufacturing";
$result = $conn->query($sql);

if (isset($_POST['update_btn'])) {
    $update_id = $_POST['update_id'];
    $name = $_POST['update_name'];
    $profit = $_POST['update_profit'];
	$product_image = $_POST['update_image'];

	$update_query = mysqli_query($conn, "UPDATE `manufacturing` SET name = '$name', profit = '$profit', product_image = '$product_image' WHERE id = '$update_id'");
	
	if($update_query){
		header('location:edit_product.php'); // Move the redirect header inside the if statement
	}
};

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `product` WHERE id = '$remove_id'");
    header('location:edit_product.php');
};


ob_end_flush();
?>

<html>
<head>
    <title></title>
</head>
<body>
    <div class="container">
        <h3>Edit Product</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <!--<th scope="col">#</th>-->
                    <th scope="col">Image URL</th>
					<th scope="col">Product Name</th>
					<th scope="col">Product Unit</th>
                    <th scope="col">RM Used</th>
					<th scope="col">RM Unit</th>
					<th scope="col">RM Unit Price</th>
					<th scope="col">Profit</th>
                    <th scope="col">Product Unit Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['name'];
                        $raw_mat = $row['raw_mat'];
						$unit = $row['unit_required'];
                        $profit = $row['profit'];

                        // Retrieve unit and price per unit from the inventory table
                        $inventoryQuery = "SELECT priceperunit FROM inventory WHERE name = '$raw_mat'";
                        $inventoryResult = mysqli_query($conn, $inventoryQuery);
                        $inventoryRow = mysqli_fetch_assoc($inventoryResult);
                        $pricePerUnit = $inventoryRow['priceperunit'];

                        // Calculate the total price
                        $totalPrice = ($unit * $pricePerUnit) + ($profit * $unit * $pricePerUnit);
						?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <tr>
                                <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
								<td><input type="text" name="update_image" value="<?php echo $row['product_image']; ?>" style="width: 300px;"></td>
                                <td><input type="text" name="update_name" value="<?php echo $row['name']; ?>" style="width: 130px;"></td>
								<td><?php echo $row['product_unit']; ?></td>
								<td><?php echo $row['raw_mat']; ?></td>
								<td><?php echo $unit ?></td>
								<td><?php echo $pricePerUnit ?></td>
                                <td><input type="number" name="update_profit" min="0" max="1" step="0.01" value="<?php echo $row['profit']; ?>" style="width: 80;"></td>								
                                <td><?php echo $totalPrice ?></td>
                                <td><button type="submit" class="btn btn-primary" name="update_btn">update</button></td>
                                <td><a class="btn btn-primary" href="edit_product.php?remove=<?php echo $row['id']; ?>">delete</a></td>
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
    </div>
</body>
</html>
