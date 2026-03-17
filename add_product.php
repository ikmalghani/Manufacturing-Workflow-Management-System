<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        /* Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        h3 {
            color: #ff6600;
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-group input[type="file"] {
            padding: 4px;
        }

        .form-group .btn {
            background-color: #ff6600;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .form-group .btn:hover {
            background-color: #e65c00;
        }

        .time-fields {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .time-fields label {
            margin-right: 10px;
        }

        .time-fields input[type="date"],
        .time-fields input[type="time"] {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<?php
include 'connection.php';
include 'header.php';
include "auth_check.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['raw_materials'], $_POST['design_time'], $_POST['planning_time'], $_POST['control_time'], $_POST['unit_required'])) {
        $productName = $_POST['name'];
        $rawMaterials = $_POST['raw_materials'];
        $rawMaterialsString = implode(",", $rawMaterials);
        $unitRequired = $_POST['unit_required'];
		$profit = $_POST['profit'];
		$product_unit = $_POST['product_unit'];
		$product_image = $_POST['product_image'];
		
		$startDate = $_POST['start_date'];
		$startTime = $_POST['start_time'];
		// Set the default time value if the time input is empty
		if (empty($startTime)) {
			$startTime = '00:00:00';
		}
		// Combine the date and time inputs
		$startDateTime = $startDate . ' ' . $startTime;
		
		$designDate = $_POST['design_date'];
		$designTime = $_POST['design_time'];
		// Set the default time value if the time input is empty
		if (empty($designTime)) {
			$designTime = '00:00:00';
		}
		// Combine the date and time inputs
		$designDateTime = $designDate . ' ' . $designTime;
		
		$planningDate = $_POST['planning_date'];
		$planningTime = $_POST['planning_time'];
		// Set the default time value if the time input is empty
		if (empty($planningTime)) {
			$planningTime = '00:00:00';
		}
		// Combine the date and time inputs
		$planningDateTime = $planningDate . ' ' . $planningTime;
		
		$controlDate = $_POST['control_date'];
		$controlTime = $_POST['control_time'];
		// Set the default time value if the time input is empty
		if (empty($controlTime)) {
			$controlTime = '00:00:00';
		}
		// Combine the date and time inputs
		$controlDateTime = $controlDate . ' ' . $controlTime;

        // Check if raw material names exist in the inventory table
        $validMaterials = true;
        $errorMaterials = [];
        foreach ($rawMaterials as $material) {
            $stmt = $conn->prepare("SELECT * FROM inventory WHERE name = ?");
            if ($stmt === false) {
                echo "Error preparing statement: " . $conn->error;
                exit;
            }
            $stmt->bind_param('s', $material);
            if ($stmt === false) {
                echo "Error binding parameters: " . $stmt->error;
                exit;
            }
            $stmt->execute();
            if ($stmt === false) {
                echo "Error executing statement: " . $stmt->error;
                exit;
            }
            $result = $stmt->get_result();
            if ($result === false) {
                echo "Error retrieving result set: " . $stmt->error;
                exit;
            }
            if ($result->num_rows === 0) {
                $validMaterials = false;
                $errorMaterials[] = "Raw material $material does not exist in the inventory.";
            }
        }

        // Check if the unit_required exceeds the available units in the inventory table
        $validUnits = true;
        $errorUnits = [];
        foreach ($rawMaterials as $material) {
            $stmt = $conn->prepare("SELECT unit FROM inventory WHERE name = ?");
            if ($stmt === false) {
                echo "Error preparing statement: " . $conn->error;
                exit;
            }
            $stmt->bind_param('s', $material);
            if ($stmt === false) {
                echo "Error binding parameters: " . $stmt->error;
                exit;
            }
            $stmt->execute();
            if ($stmt === false) {
                echo "Error executing statement: " . $stmt->error;
                exit;
            }
            $result = $stmt->get_result();
            if ($result === false) {
                echo "Error retrieving result set: " . $stmt->error;
                exit;
            }
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $availableUnits = $row['unit'];

                if ($unitRequired > $availableUnits) {
                    $validUnits = false;
                    $unitError = "Insufficient units for raw material $material. Available units: $availableUnits";
                    $errorUnits[] = $unitError;
                }
            } else {
                // Handle the case where the raw material is not found in the inventory
                $validUnits = false;
                $unitError = "Raw material $material does not exist in the inventory.";
                $errorUnits[] = $unitError;
            }
        }

        if ($validMaterials && $validUnits) {
            $historyQuery = "SELECT priceperunit FROM inventory WHERE name = '$rawMaterialsString'";
            $historyResult = mysqli_query($conn, $historyQuery);
            $historyRow = mysqli_fetch_assoc($historyResult);
            $unitprice = $historyRow['priceperunit'];
			
			// Insert data into the 'product' table
            $rawMaterialsString = implode(",", $rawMaterials);
			$query = "INSERT INTO manufacturing (name, product_unit, raw_mat, rm_unit, rm_unitprice, start_time, design_time, planning_time, control_time, unit_required, profit, product_image) VALUES ('$productName', '$product_unit', '$rawMaterialsString', '$availableUnits', '$unitprice', '$startDateTime', '$designDateTime', '$planningDateTime', '$controlDateTime', '$unitRequired', '$profit', '$product_image')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Update the number of units left in the 'inventory' table
                foreach ($rawMaterials as $material) {
                    $updateQuery = "UPDATE inventory SET unit = unit - $unitRequired WHERE name = '$material'";
                    $updateResult = mysqli_query($conn, $updateQuery);
                }
                // Success! Perform any additional actions or display a success message
                echo "Product added successfully!";
				
				date_default_timezone_set('Asia/Kuala_Lumpur');
				$actual_time = date('Y-m-d H:i:s');
				$edited_des = "RM -> Products.";
				
				$history_query = mysqli_query($conn, "INSERT INTO inventory_history (product_name, description, unit, unit_price, date_stored) VALUES ('$rawMaterialsString', '$edited_des', '$unitRequired', '$unitprice', '$actual_time')");
            } else {
                // Error occurred during the insertion
                echo "Error adding product: " . mysqli_error($conn);
            }
        } else {
            // Display errors for invalid materials and units
            if (!$validMaterials) {
                foreach ($errorMaterials as $error) {
                    echo $error . "<br>";
                }
            }
            if (!$validUnits) {
                foreach ($errorUnits as $error) {
                    echo $error . "<br>";
                }
            }
        }
    } else {
        // Required fields are missing
        echo "Please fill in all required fields.";
    }
}
?>


<!-- HTML Form -->
<div class="container">
    <h3>Add Product</h3>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="product_unit">Product Unit:</label>
            <input type="number" id="product_unit" name="product_unit" required>

            <label for="raw_materials">Raw Materials:</label>
            <select id="raw_materials" name="raw_materials[]" multiple required>
                <?php
                // Retrieve raw materials from the inventory table
                $rawMaterialsQuery = "SELECT name FROM inventory";
                $rawMaterialsResult = mysqli_query($conn, $rawMaterialsQuery);

                if ($rawMaterialsResult && mysqli_num_rows($rawMaterialsResult) > 0) {
                    while ($row = mysqli_fetch_assoc($rawMaterialsResult)) {
                        $rawMaterial = $row['name'];
                        echo "<option value='$rawMaterial'>$rawMaterial</option>";
                    }
                }
                ?>
            </select>
			
			<label for="unit_required">Units Required:</label>
            <input type="number" id="unit_required" name="unit_required" min="1" required>
				
			<label for="profit">Profit (0-1):</label>
            <input type="number" id="profit" name="profit" min="0" max="1" step="0.01" required>
			
            <div class="time-fields">
                <label for="start_date">Start Date:</label>
				<input type="date" id="start_date" name="start_date" required>

                <label for="design_date">Design Date:</label>
				<input type="date" id="design_date" name="design_date" required>
				
				<label for="planning_date">Planning Date:</label>
				<input type="date" id="planning_date" name="planning_date" required>
				
				<label for="control_date">Control Date:</label>
				<input type="date" id="control_date" name="control_date" required>
            </div>
			
			<div class="time-fields">
                <label for="start_time">Start Time (optional):</label>
				<input type="time" id="start_time" name="start_time">

                <label for="design_time">Design Time (optional):</label>
				<input type="time" id="design_time" name="design_time">
				
				<label for="planning_time">Planning Time (optional):</label>
				<input type="time" id="planning_time" name="planning_time">
				
				<label for="control_time">Control Time (optional):</label>
				<input type="time" id="control_time" name="control_time">
            </div>

            <label for="product_image">Product Image URL:</label>
            <input type="text" id="product_image" name="product_image" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Add Product</button>
        </div>
    </form>
</div>

</body>
</html>
