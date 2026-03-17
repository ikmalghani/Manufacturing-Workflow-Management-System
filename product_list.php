<!DOCTYPE html>
<html>
<head>
    <title>Manufacturing Table</title>
    <style>
        /* Styles */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
<?php
include 'connection.php';
include 'header.php';
include "auth_check.php";

// Retrieve data from the manufacturing table
$query = "SELECT * FROM manufacturing";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    ?>
    <table>
        <tr>
            <th>Product Image</th>
			<th>Product Name</th>
            <th>Product Unit</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td>
                    <?php if (!empty($row['product_image'])) { ?>
                        <img class="product-image" src="<?php echo $row['product_image']; ?>" alt="Product Image">
                    <?php } ?>
                </td>
				<td><?php echo $row['name']; ?></td>
                <td><?php echo $row['product_unit']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
} else {
    echo "No records found in the manufacturing table.";
}

mysqli_close($conn);
?>
</body>
</html>