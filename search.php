<?php
include 'header.php';
include 'connection.php';
include "auth_check.php";

$searchResults = array();

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $search = $_POST['search'];

    // Query to search for similar records
    $query = "SELECT * FROM manufacturing WHERE name LIKE '%$search%'";
    $result = mysqli_query($conn, $query);

    // Fetch and store the search results
    if (mysqli_num_rows($result) > 0) {
    $echo = array(); // Initialize the $echo variable as an empty array
    while ($row = mysqli_fetch_assoc($result)) {
        $searchResults[] = $row;
        $t = ($row['rm_unit'] * $row['rm_unitprice']) + ($row['rm_unit'] * $row['rm_unitprice'] * $row['profit']);

        date_default_timezone_set('Asia/Kuala_Lumpur');
        $current_time = date('Y-m-d H:i:s');

        // Compare the current date and time with the start time and manufacturing phases
        $current_time = strtotime($current_time);
        $design_time = strtotime($row['design_time']);
        $planning_time = strtotime($row['planning_time']);
        $control_time = strtotime($row['control_time']);

        if ($current_time >= strtotime($row['start_time']) && $current_time < $design_time) {
            $echo[] = "Product '".$row['name']."' is in designing phase.";
        } elseif ($current_time >= $design_time && $current_time < $planning_time) {
            $echo[] = "Product '".$row['name']."' is in planning phase.";
        } elseif ($current_time >= $planning_time && $current_time < $control_time) {
            $echo[] = "Product '".$row['name']."' is currently being manufactured.";
        } elseif ($current_time >= $control_time) {
            $echo[] = "Product '".$row['name']."' is ready to be sold.";
        }
    }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Page</title>
</head>
<body>
    <h3>Search Page</h3>
    <form action="" method="POST">
        <input type="text" name="search" placeholder="Enter a product name" required>
        <input type="submit" name="submit" value="Search">
    </form>
    <br>

    <?php
    if (!empty($searchResults)) {
    foreach ($searchResults as $key => $result) {
        echo '<img class="product-image" src="' . $result['product_image'] . '" alt="Product Image" height="150" >';
        echo "<h5>Product: " . $result['name'] . "</h5>";
        $t = ($result['rm_unit'] * $result['rm_unitprice']) + ($result['rm_unit'] * $result['rm_unitprice'] * $result['profit']);
        echo "<p>Price: RM" . $t . "</p>";
        
        if (isset($echo[$key])) { // Check if there is a corresponding status message for this result
            echo "<p>Status: " . $echo[$key] . "</p>";
        } else {
            echo "<p>Status: Unknown</p>";
        }
        
        echo "<hr>";
    }
	} else if (isset($_POST['submit'])) {
		echo "<p>No results found.</p>";
	}
    ?>
</body>
</html>
