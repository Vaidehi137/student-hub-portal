
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Session timeout: 15 minutes
if (time() - $_SESSION["last_activity"] > 900) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

$_SESSION["last_activity"] = time();

// Only Admin can access
if ($_SESSION["role"] != "Admin") {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <center>
        <h1>Welcome to StudentHub Admin Panel!</h1>

        <p>Hello, <?php echo htmlspecialchars($_SESSION["name"]); ?></p>

        <p>You are logged in as Admin.</p>

        <a href="logout.php">Logout</a>
    </center>
</body>
</html>
