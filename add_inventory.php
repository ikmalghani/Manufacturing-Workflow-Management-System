<?php
ob_start(); 
	
	include "header.php";
	include "connection.php";
	include "auth_check.php";

$sql = "SELECT * FROM inventory";
$result = $conn -> query ($sql);

// Code to handle the form submission for adding a new user
if (isset($_POST['add_btn'])) {
    $name = $_POST['new_name'];
    $unit = $_POST['new_unit'];
	$priceperunit = $_POST['new_priceperunit'];
	
	date_default_timezone_set('Asia/Kuala_Lumpur');
	$actual_time = date('Y-m-d H:i:s');
	
	$edited_des = "Inventory added.";
	
	$history_query = mysqli_query($conn, "INSERT INTO inventory_history (product_name, description, unit, unit_price, date_stored) VALUES ('$name', '$edited_des', '$unit', '$priceperunit', '$actual_time')");
    
	$tp = $unit * $priceperunit;
	$purchase_query = mysqli_query($conn, "INSERT INTO purchase (name, total_price, purchase_time) VALUES ('$name', '$tp', '$actual_time')");
	
	$insert_query = mysqli_query($conn, "INSERT INTO `inventory` (name, unit, priceperunit) VALUES ('$name', '$unit', '$priceperunit')");
    if ($insert_query) {
        header('location:add_inventory.php');
        exit();
    }
}

if(isset($_POST['update_btn'])){
  $update_id = $_POST['update_id'];
  $name = $_POST['update_name'];
  $unit = $_POST['update_unit'];
  $priceperunit = $_POST['update_priceperunit'];
  
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $actual_time = date('Y-m-d H:i:s');
  
  $edited_des = "Inventory updated.";
  
  $insert_query = mysqli_query($conn, "INSERT INTO inventory_history (product_name, description, unit, unit_price, date_stored) VALUES ('$name', '$edited_des', '$unit', '$priceperunit', '$actual_time')");
    
  $update_query = mysqli_query($conn, "UPDATE `inventory` SET priceperunit = '$priceperunit' , name='$name',  unit='$unit', inventory_date = '$actual_time'  WHERE id = '$update_id'");
  if($update_query){
     header('location:add_inventory.php');
  };
  
	$tp = $unit * $priceperunit;
	$purchase_query = mysqli_query($conn, "INSERT INTO purchase (name, total_price, purchase_time) VALUES ('$name', '$tp', '$actual_time')");
};

if(isset($_GET['remove'])){
  $remove_id = $_GET['remove'];
  
  // Retrieve the details of the product being removed
  $select_query = mysqli_query($conn, "SELECT * FROM `inventory` WHERE id = '$remove_id'");
  $product = mysqli_fetch_assoc($select_query);
  
  mysqli_query($conn, "DELETE FROM `inventory` WHERE id = '$remove_id'");
  
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $actual_time = date('Y-m-d H:i:s');
  
  $product_name = $product['name'];
  $unit = $product['unit'];
  $priceperunit = $product['priceperunit'];
  $edited_des = "Inventory deleted.";
  
  $insert_query = mysqli_query($conn, "INSERT INTO inventory_history (product_name, description, unit, unit_price, date_stored) VALUES ('$product_name', '$edited_des', '$unit', '$priceperunit', '$actual_time')");
  header('location:add_inventory.php');
};


ob_end_flush();
?>

<html>
<head>
    <title></title>
</head>
<body>
    <div class="container">
    <h5>Edit Inventory</h5>
    <table class="table table-striped">
  <thead>
    <tr>
      <!--<th scope="col">#</th>-->
      <th scope="col">Product Name</th>
      <th scope="col">Unit</th>
      <th scope="col">Unit Price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
   
      <?php
          if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
              ?>
             <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
               <tr>
                <input type="hidden" name="update_id"  value="<?php echo $row['id'];?>">
                <td><input type="text" name="update_name"  value="<?php echo $row['name'];?>"></td>
                <td><input type="number" name="update_unit"  value="<?php echo $row['unit'];?>"></td>
                <td><input type="number" name="update_priceperunit"  value="<?php echo $row['priceperunit'];?>"></td>
                <td><button type="submit" class="btn btn-primary" name="update_btn">update</button></td>
                <td><a  class="btn btn-primary" href="add_inventory.php?remove=<?php echo $row['id']; ?>">delete</a></td>
                </tr>
                </form>
                <?php }
        } else {
            echo "0 results";
        }
        ?>
		
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <tr>
                <td><input type="text" name="new_name" placeholder="Material Name"></td>
                <td><input type="number" name="new_unit" placeholder="Unit"></td>
				<td><input type="number" name="new_priceperunit" placeholder="Price Per Unit"></td>
                <td>
                     <button type="submit" class="btn btn-primary" name="add_btn">Add</button>
                </td>
            </tr>
         </form>
    
  </tbody>
</table>
</div>
</body>
</html>