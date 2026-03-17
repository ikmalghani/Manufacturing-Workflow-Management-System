<?php
 include 'header.php';
 include 'connection.php';
 include 'auth_check.php';
 
 $t = 0;
 $sql = "SELECT * FROM sales";
 $result = $conn->query($sql);
?>
<div class="container">
  <button type="button" onclick="window.print();return false;">PDF Report</button>
  <div class="container pendingbody">
    <h3>Sales Report</h3>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">Unit Sold</th>
		  <th scope="col">Price Per Unit (RM)</th>
          <th scope="col">Total Earnings (RM)</th>
		  <th scope="col">Sold On</th>
        </tr>
      </thead>
      <tbody>
        <?php
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc($result)) {
				$t=$t+$row["total_price"];
        ?>
                <tr>
                  <td><?php echo $row["name"] ?></td>
                  <td><?php echo $row["unit_sold"] ?></td>
				  <td><?php echo $row["unitprice"] ?></td>
                  <td><?php echo $row["total_price"] ?></td>
				  <td><?php echo $row["sales_time"] ?></td>
                </tr>
        <?php 
              }
            } 
            else {
              echo "0 results";
            }
        ?>
      </tbody>
    </table>
		<?php echo "Total = RM" . $t;?>
  </div>
</div>
