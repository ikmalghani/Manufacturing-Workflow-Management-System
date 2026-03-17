<?php
SESSION_START();

if (isset($_SESSION['auth'])) {
    if ($_SESSION['auth'] != 1) {
        header("location:login.php");
		exit;
    }
} else {
    header("location:login.php");
	exit;
}
?>
<html>

<head>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-wbH//n+t/XpLZL+B73vFq1GjIl+vJpTwzrh2xG6OGJNlJ+1/0eqb7tFyyR4J1j+rd+qGKK0L/u29ZyzmGbwLxw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="index.php">Poqox Sdn Bhd</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"><i class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Dashboard</a>
        </li>
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Warehouse
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item nav-link" href="add_inventory.php">Edit Inventory</a></li>
			<li><a class="dropdown-item nav-link" href="inventory_list.php">Inventory List</a></li>
			<li><a class="dropdown-item nav-link" href="inventory_history.php">Inventory Log</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Production
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item nav-link" href="add_product.php">Add Product</a></li>
			<li><a class="dropdown-item nav-link" href="edit_product.php">Edit Product</a></li>
			<li><a class="dropdown-item nav-link" href="product_list.php">Product Catalog</a></li>
			<li><a class="dropdown-item nav-link" href="production_schedule.php">Production Schedule</a></li>
          </ul>
        </li>
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Sales
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
			<li><a class="dropdown-item nav-link" href="sales.php">Sales</a></li>
			<li><a class="dropdown-item nav-link" href="sales_report.php">Sales Report</a></li>
          </ul>
        </li>
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Production Performance
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item nav-link" href="calculator.php">Production Performance Calculator</a></li>
			<li><a class="dropdown-item nav-link" href="calculator_report.php">Current Production Performance</a></li>
          </ul>
        </li>
		<li class="nav-item">
          <a class="nav-link" href="search.php"><img src="https://png.pngtree.com/png-vector/20190321/ourmid/pngtree-vector-find-icon-png-image_854997.jpg" alt="Search Icon" height="18"></a>
        </li>
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://www.citypng.com/public/uploads/preview/white-user-member-guest-icon-png-image-31634946729lnhivlto5f.png" alt="User Icon" height="20">
			<?php echo $_SESSION['username']; ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item nav-link" href="edit_user.php">Edit User</a></li>
			<li><a class="dropdown-item nav-link" style="color:red!important;" href="logout.php">Logout</a></li>
          </ul>
		</li>
      </ul>
    </div>
  </div>
</nav>
</nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>

</html>
