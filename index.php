<!DOCTYPE html>
<html>
<head>
    <title>Welcome Page</title>
    <style>
        h1 {
        text-align: center;
        padding-top: 30px;
		}
    
		#projectionChart {
			width: 70%;
			float: right;
		}
    
		#totalsContainer {
			width: 30%;
			float: left;
		}
    
		.purchaseTotal {
			background-color: #008FFB;
			padding: 10px;
			margin-top: 10px;
			margin-bottom: 10px;
			color: white;
		}
    
		.salesTotal {
			background-color: #00E396;
			padding: 10px;
			margin-top: 10px;
			margin-bottom: 10px;
			color: white;
		}
    </style>
    <?php 
        include "header.php";
        include "connection.php";
        include "auth_check.php";
        
        $username = $_SESSION['username'];

        // Fetch data for purchase projection by month
		$purchaseData = array();
		$query = "SELECT MONTH(purchase_time) AS month, SUM(total_price) AS total_price FROM purchase GROUP BY MONTH(purchase_time)";
		$result = mysqli_query($conn, $query);

		// Initialize the purchaseData array with all months set to 0
		$allMonths = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		foreach ($allMonths as $month) {
			$purchaseData[$month] = 0;
		}

		// Process the query result and populate the purchaseData array
		while ($row = mysqli_fetch_assoc($result)) {
			$monthNumber = $row['month'];
			$totalPrice = $row['total_price'];

			// Convert month number to month name
			$monthName = date("F", mktime(0, 0, 0, $monthNumber, 1));

			// Store the data in the purchaseData array
			$purchaseData[$monthName] = $totalPrice;
		}
		
		// Fetch data for sales projection by month
		$salesData = array();
		$query = "SELECT MONTH(sales_time) AS month, SUM(total_price) AS total_price FROM sales GROUP BY MONTH(sales_time)";
		$result = mysqli_query($conn, $query);

		// Initialize the salesData array with all months set to 0
		$allMonths = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		foreach ($allMonths as $month) {
		$salesData[$month] = 0;
}

		// Process the query result and populate the salesData array
		while ($row = mysqli_fetch_assoc($result)) {
		$monthNumber = $row['month'];
		$totalPrice = $row['total_price'];

		// Convert month number to month name
		$monthName = date("F", mktime(0, 0, 0, $monthNumber, 1));

		// Store the data in the salesData array
		$salesData[$monthName] = $totalPrice;
		}

    ?>
</head>
<body>
    <h1>Welcome <?php echo $username; ?>!</h1>
    <p style="text-align:center;">Lorem Ipsum</p>

    <!-- Display purchase and sales projection by months line graph -->
    <div id="projectionChart"></div>
	
	<!-- Display total purchase and sales for the year -->
    <div id="totalsContainer">
        <div class="purchaseTotal">
            <?php
                $totalPurchase = array_sum(array_values($purchaseData));
                echo "Total Purchase for 2023: RM " . $totalPurchase;
            ?>
        </div>
        <div class="salesTotal">
            <?php
                $totalSales = array_sum(array_values($salesData));
                echo "Total Sales for 2023: RM " . $totalSales;
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.28.3/dist/apexcharts.min.js"></script>
    <script>
        var purchaseData = <?php echo json_encode($purchaseData); ?>;
        var salesData = <?php echo json_encode($salesData); ?>;

        var options = {
            chart: {
                type: 'line',
                height: 500,
                toolbar: {
                    show: false
                }
            },
            series: [
                {
                    name: 'Purchase',
                    data: Object.values(purchaseData)
                },
                {
                    name: 'Sales',
                    data: Object.values(salesData)
                }
            ],
            xaxis: {
                categories: Object.keys(purchaseData)
            },
            colors: ['#008FFB', '#00E396'],
            legend: {
                position: 'bottom'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: '100%'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#projectionChart"), options);
        chart.render();
    </script>
</body>
</html>
