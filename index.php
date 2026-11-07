<?php
include "includes/db_connect.php";

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user == "admin" && $pass == "admin123") {
        $_SESSION['admin'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library MS - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="login-body">

    <div class="login-box">
        <h2>Library Management</h2>
        <p>Librarian Login</p>

        <?php
        if ($error != "") {
            echo "<p style='color:red'>$error</p>";
        }
        ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Enter username" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>

</body>
</html>
