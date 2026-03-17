<?php
 include 'header.php';
 include 'connection.php';
 include 'auth_check.php';
 
 $t_CycleTime = 0;
 $t_ProductionRate = 0;
 $t_ProductionCapacity = 0;
 $t_UtilPercent = 0;
 $t_Availability = 0;
 $t_MLT = 0;
 $sql = "SELECT * FROM cycle_time";
 $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div class="container">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="submit" name="submit" value="Refresh">
  </form>
  <button type="button" onclick="window.print();return false;">PDF Report</button>
  <div class="container pendingbody">
    <h5>Cycle Time Data</h5>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Product Name</th>
          <th scope="col">Cycle Time (hrs)</th>
		  <th scope="col">Production Rate (pc/hr)</th>
		  <th scope="col">Production Capacity (unit/week)</th>
		  <th scope="col">Utilization (%)</th>
		  <th scope="col">Availability (%)</th>
		  <th scope="col">Manufacturing Lead Time (hrs)</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $t_CycleTime = 0;
          $t_ProductionRate = 0;
          $t_ProductionCapacity = 0;
          $t_UtilPercent = 0;
          $t_Availability = 0;
          $t_MLT = 0;
          if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
              $t_CycleTime += $row['CycleTime'];
              $t_ProductionRate += $row['ProductionRate'];
              $t_ProductionCapacity += $row['ProductionCapacity'];
              $t_UtilPercent += $row['Utilization'];
              $t_Availability += $row['Availability'];
              $t_MLT += $row['MLT'];
        ?>
              <tr>
                <td><?php echo $row["Product"] ?></td>
                <td><?php echo $row["CycleTime"] ?></td>
                <td><?php echo $row["ProductionRate"] ?></td>
                <td><?php echo $row["ProductionCapacity"] ?></td>
                <td><?php echo $row["Utilization"] ?></td>
                <td><?php echo $row["Availability"] ?></td>
                <td><?php echo $row["MLT"] ?></td>
              </tr>
        <?php 
            }
          } 
          else {
            echo "0 results";
          }
        ?>
		<tr style="background-color: #afb0ae;">
			<td>Total</td>
			<td><?php echo $t_CycleTime ?></td>
			<td><?php echo $t_ProductionRate ?></td>
			<td><?php echo $t_ProductionCapacity ?></td>
			<td><?php echo $t_UtilPercent ?></td>
			<td><?php echo $t_Availability ?></td>
			<td><?php echo $t_MLT ?></td>
		</tr>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
