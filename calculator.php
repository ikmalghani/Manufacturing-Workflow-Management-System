<?php
include "header.php";
include "connection.php";
include "auth_check.php";

$ProductionRate=0;
$Utilization=0;
$Avail=0;

$Product = $_POST["Product"] ?? null;
$ProcessingTime = $_POST["ProcessingTime"] ?? null;
$HandlingTime = $_POST["HandlingTime"] ?? null;
$ToolHandlingTime = $_POST["ToolHandlingTime"] ?? null;
$SetupTime = $_POST["SetupTime"] ?? null;
$Quantity = $_POST["Quantity"] ?? null;
$NoWorkCenters = $_POST["NoWorkCenters"] ?? null;
$ShiftPerWeek = $_POST["ShiftPerWeek"] ?? null;
$HourPerShift = $_POST["HourPerShift"] ?? null;
$MTBF = $_POST["MTBF"] ?? null;
$MTTR = $_POST["MTTR"] ?? null;
$NoOperations = $_POST["NoOperations"] ?? null;
$NonOperationTime = $_POST["NonOperationTime"] ?? null;

$CycleTime = $ProcessingTime + $HandlingTime + $ToolHandlingTime;
$BatchTime = $SetupTime + ($Quantity * $CycleTime);
if ($BatchTime > 0) {
$ProductionRate = 60 / $BatchTime;
$ProductionRate = number_format($ProductionRate, 2);
} else {
echo "";
}
$ProductionCapacity = $NoWorkCenters * $ShiftPerWeek * $HourPerShift * $ProductionRate;

if ($ProductionCapacity > 0) {
$Utilization = $Quantity / $ProductionCapacity;
$Utilization = number_format($Utilization, 3);
} else {
echo "";
}
$UtilPercent = $Utilization * 100;
if ($MTBF > 0) {
$Avail = ($MTBF - $MTTR) / $MTBF;
$Availability = number_format($Avail, 3);
} else {
echo "";
}
$Availability = $Avail * 100;
$MLT = $NoOperations * ($BatchTime + $NonOperationTime);

$stmt = $conn->prepare("SELECT * FROM `manufacturing` WHERE `name` = ?");
if ($stmt === false) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$stmt->bind_param('s', $Product);
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
    // Product exists in the product table
    echo "Product " . $Product . " exists in the database.";
	echo nl2br("\n");

    // Check if the product exists in the cycle_time table
    $stmt = $conn->prepare("SELECT * FROM `cycle_time` WHERE `Product` = ?");
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param('s', $Product);
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
        // Product exists in the cycle_time table, update the CycleTime value
        $updatesql = "UPDATE `cycle_time` SET `CycleTime` = '$CycleTime', `ProductionRate` = '$ProductionRate', `ProductionCapacity` = '$ProductionCapacity', `Utilization` = '$UtilPercent', `Availability` = '$Availability', `MLT` = '$MLT' WHERE `Product` = '$Product'";
        if ($conn->query($updatesql) === TRUE) {
            echo " Cycle Time for $Product is updated to $CycleTime hour(s).";
			echo nl2br("\n");
			echo "Production rate for $Product is updated to $ProductionRate pc/hr.";
			echo nl2br("\n");
			echo "Production capacity for $Product is updated to $ProductionCapacity unit/week.";
			echo nl2br("\n");
			echo "Utilization for $Product is updated to $UtilPercent%.";
			echo nl2br("\n");
			echo "Availability for $Product is updated to $Availability%.";
			echo nl2br("\n");
			echo "Manufacturing Lead Time for $Product is updated to $MLT hour(s).";
        } else {
            echo " Error updating data: " . $conn->error;
        }
    } else {
        // Product does not exist in the cycle_time table, insert the new record
        $sql = "INSERT INTO `cycle_time` (`Product`, `CycleTime`, `ProductionRate`, `ProductionCapacity`, `Utilization`, `Availability`, `MLT`) VALUES ('$Product', '$CycleTime', '$ProductionRate', '$ProductionCapacity', '$UtilPercent', '$Availability', '$MLT')";
        if ($conn->query($sql) === TRUE) {
            echo " Cycle Time for $Product is $CycleTime hour(s).";
			echo nl2br("\n");
			echo "Production rate for $Product is $ProductionRate pc/hr.";
			echo nl2br("\n");
			echo "Production capacity for $Product is $ProductionCapacity unit/week.";
			echo nl2br("\n");
			echo "Utilization for $Product is $UtilPercent%.";
			echo nl2br("\n");
			echo "Availability for $Product is $Availability%.";
			echo nl2br("\n");
			echo "Manufacturing Lead Time for $Product is $MLT hour(s).";
        } else {
            echo " Error inserting data: " . $conn->error;
        }
    }
} else {
    // Product does not exist in the product table
    echo nl2br("Please enter the correct information. \n" . '<a href="product_list.php">Click here to view the list of existing products in the database.</a>');
}
  
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        h1 {
            text-align: center;
        }
        
        form {
            max-width: 500px;
            margin: 0 auto;
        }
        
        label {
            display: block;
            margin-top: 10px;
        }
        
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 5px;
        }
        
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>My Form</h1>
    <form action="calculator.php" method="post">
        <label for="product">Product:</label>
        <input type="text" id="product" name="Product">
        
        <label for="processingTime">Processing Time:</label>
        <input type="number" id="processingTime" name="ProcessingTime">
        
        <label for="handlingTime">Handling Time:</label>
        <input type="number" id="handlingTime" name="HandlingTime">
        
        <label for="toolHandlingTime">Tool Handling Time:</label>
        <input type="number" id="toolHandlingTime" name="ToolHandlingTime">
        
        <label for="setupTime">Setup Time:</label>
        <input type="number" id="setupTime" name="SetupTime">
        
        <label for="quantity">Quantity In A Batch:</label>
        <input type="number" id="quantity" name="Quantity">
        
        <label for="NoWorkCenters">Number of Work Centers:</label>
        <input type="number" id="NoWorkCenters" name="NoWorkCenters">
        
        <label for="ShiftPerWeek">Shift Per Week:</label>
        <input type="number" id="ShiftPerWeek" name="ShiftPerWeek">
        
        <label for="HourPerShift">Hour Per Shift:</label>
        <input type="number" id="HourPerShift" name="HourPerShift">
        
        <label for="mtbf">Mean Time Before Failure:</label>
        <input type="number" id="mtbf" name="MTBF">
        
        <label for="mttr">Mean Time To Repair:</label>
        <input type="number" id="mttr" name="MTTR">
        
        <label for="noOperations">Number of Operations:</label>
        <input type="number" id="noOperations" name="NoOperations">
        
        <label for="nonOperationTime">Non-Operation Time:</label>
        <input type="number" id="nonOperationTime" name="NonOperationTime">
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>

