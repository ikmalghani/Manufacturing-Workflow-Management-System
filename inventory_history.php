<?php
	include 'header.php';
	include 'connection.php';
	include 'auth_check.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventory Records</title>
	<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}

		th, td {
			text-align: left;
			padding: 8px;
			border-bottom: 1px solid #ddd;
		}

		tr:hover {
			background-color: #f5f5f5;
		}

		th {
			background-color: ; 
			color: white;
		}
	</style>
</head>
<body>
	<h3>Inventory Log</h3>
	<table>
		<thead>
			<tr>
				<th>Product Name</th>
				<th>Action</th>
				<th>Unit</th>
				<th>Unit Price</th>
				<th>Log Date</th>
			</tr>
		</thead>
		<tbody>
			<?php
				
				// Query the database for inventory history
				$sql = "SELECT product_name, description, unit, unit_price, date_stored FROM inventory_history";
				$result = $conn->query($sql);

				// Display the inventory history
				if ($result->num_rows > 0) {
				  while($row = $result->fetch_assoc()) {
				    echo "<tr>";
				    echo "<td>" . $row["product_name"] . "</td>";
				    echo "<td>" . $row["description"] . "</td>";
				    echo "<td>" . $row["unit"] . "</td>";
				    echo "<td>" . $row["unit_price"] . "</td>";
				    echo "<td>" . $row["date_stored"] . "</td>";
				    echo "</tr>";
				  }
				} else {
				  echo "<tr><td colspan='5'>No inventory history found.</td></tr>";
				}
			?>
		</tbody>
	</table>
</body>
</html>
