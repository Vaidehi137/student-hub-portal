<?php
session_start();

$msg = "";

if (isset($_POST["login"])) {
    if ($_POST["username"] == "admin" &&
        $_POST["password"] == "1234") {

        $_SESSION["role"] = "admin";
        header("Location: admin.php");
        exit();

    } else {
        $msg = "Wrong username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

<h2>Admin Login</h2>

<form method="post">
    Username:
    <input type="text" name="username" required>
    <br><br>

    Password:
    <input type="password" name="password" required>
    <br><br>

    <button type="submit" name="login">Login</button>
</form>

<p><?php echo $msg; ?></p>

</body>
</html>
