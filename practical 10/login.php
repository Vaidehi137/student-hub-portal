
<?php
session_start();
include "../db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username == "" || $password == "") {
        $error = "Please enter username and password.";
    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, username, email, password, role
             FROM students
             WHERE username = ? OR email = ?"
        );

        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["last_activity"] = time();

                if ($user["role"] == "Admin") {
                    header("Location: admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();

            } else {
                $error = "Invalid username or password.";
            }

        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style.css">
    <title>Student Hub</title>
</head>

<body>
    <div class="login-page">
        <br>

        <img src="../images/student hub.png"
             width="300" alt="Student Hub Logo">

        <br><br>

        <fieldset class="login-box">
            <h1>LOGIN</h1>
            <p>Welcome Back! Please login to continue.</p>

            <form action="login.php" method="POST">

                <h2>Username</h2>
                <input type="text" name="username"
                       placeholder="Enter your username or email"
                       required>

                <br>

                <h2>Password</h2>
                <input type="password" name="password"
                       placeholder="Enter your password"
                       required>

                <br><br>

                <input type="submit" value="Login"
                       style="width:120px; height:40px; font-size:18px;">

            </form>

            <?php if ($error != ""): ?>
                <p style="color:red;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <p><a href="../register.php">Create an account</a></p>
        </fieldset>
    </div>

    <br>
    <hr>

    <center>
        <p>&copy; 2026 Student Hub Portal</p>
        <a href="contact.html">Contact</a> |
        <a href="#">Privacy Policy</a> |
        <a href="#">Help</a>
    </center>
</body>

</html>
