<?php
ob_start(); 

include "header.php";
include "connection.php";
include "auth_check.php";

if (isset($_POST['update_btn'])) {
    $update_id = $_POST['update_id'];
    $username = $_POST['update_username'];
    $password = $_POST['update_password'];

    $update_query = mysqli_query($conn, "UPDATE `user` SET username = '$username', password = '$password' WHERE id = '$update_id'");
    if ($update_query) {
        header('location:edit_user.php');
        exit();
    }
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `user` WHERE id = '$remove_id'");
    header('location:edit_user.php');
    exit();
}

// Code to handle the form submission for adding a new user
if (isset($_POST['add_btn'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];

    $insert_query = mysqli_query($conn, "INSERT INTO `user` (username, password) VALUES ('$username', '$password')");
    if ($insert_query) {
        header('location:edit_user.php');
        exit();
    }
}

$sql = "SELECT * FROM user";
$result = $conn->query($sql);

ob_end_flush();
?>

<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <div class="container">
        <h5>Edit User</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <tr>
                                <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
                                <td><input type="text" name="update_username" value="<?php echo $row['username']; ?>"></td>
                                <td><input type="password" name="update_password" value="<?php echo $row['password']; ?>"></td>
                                <td>
                                    <button type="submit" class="btn btn-primary" name="update_btn">Update</button>
                                    <a class="btn btn-primary" href="edit_user.php?remove=<?php echo $row['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        </form>
                        <?php
                    }
                } else {
                    echo "0 results";
                }
                ?>

                <!-- Form to add a new user -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <tr>
                        <td><input type="text" name="new_username" placeholder="New Username"></td>
                        <td><input type="password" name="new_password" placeholder="New Password"></td>
                        <td>
                            <button type="submit" class="btn btn-primary" name="add_btn">Add</button>
                        </td>
                    </tr>
                </form>
            </tbody>
        </table>
    </div>
</body>
</html>
