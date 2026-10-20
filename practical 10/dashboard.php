
<?php
session_start();

// Check login
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

// Only Student can access
if ($_SESSION["role"] != "Student") {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
</head>
<body>
    <center>
        <h1>Welcome to StudentHub!</h1>

        <h2>Hello, <?php echo htmlspecialchars($_SESSION["name"]); ?></h2>

        <p>You are successfully logged in as Student.</p>

        <a href="logout.php">Logout</a>
    </center>
</body>
</html>
