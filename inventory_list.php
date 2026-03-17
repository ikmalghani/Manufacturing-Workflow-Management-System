<?php
 include 'header.php';
 include 'connection.php';
 include "auth_check.php";
 
 $t = 0;
 $sql = "SELECT * FROM inventory";
 $result = $conn->query($sql);
?>
<div class="container">
  <button type="button" onclick="window.print();return false;">PDF Report</button>
  <div class="container pendingbody">
    <h3>Inventory List</h3>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Unit</th>
		  <th scope="col">Date Modified</th>
        </tr>
      </thead>
      <tbody>
        <?php
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc($result)) {
                
        ?>
                <tr>
                  <td><?php echo $row["name"] ?></td>
                  <td><?php echo $row["unit"] ?></td>
				  <td><?php echo $row['inventory_date'] ?></td>
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
  </div>
</div>
