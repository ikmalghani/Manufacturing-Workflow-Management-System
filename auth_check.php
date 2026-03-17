<?php

// Define an array of authorized usernames for each file
$authorizedUsers = array(
    'index.php' => array('admin', 'business', 'control', 'design', 'planning'),
	'add_inventory.php' => array('admin', 'business'),
	'inventory_list.php' => array('admin', 'business', 'control'),
	'inventory_history.php' => array('admin', 'business', 'control', 'planning'),
	'add_product.php' => array('admin', 'business', 'design'),
	'edit_product.php' => array('admin', 'business'),
	'product_list.php' => array('admin', 'business', 'control', 'design', 'planning'),
	'production_schedule.php' => array('admin', 'planning'),
	'sales.php' => array('admin', 'business'),
	'sales_report.php' => array('admin', 'business', 'control', 'planning'),
	'calculator.php' => array('admin', 'control'),
	'calculator_report.php' => array('admin', 'business', 'control', 'planning'),
	'search.php' => array('admin', 'business', 'control', 'design', 'planning'),
	'edit_user.php' => array('admin')
);

// Get the current file name
$currentFile = basename($_SERVER['PHP_SELF']);

// Check if the user is authenticated
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
    header("location: login.php");
    exit;
}

// Check if the current user is authorized to access the current file
$username = $_SESSION['username'];
if (!in_array($username, $authorizedUsers[$currentFile])) {
    echo "<div style='color: red; text-align: center; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'>You are not authorized to view this page.</div>";;
    exit;
}
?>
