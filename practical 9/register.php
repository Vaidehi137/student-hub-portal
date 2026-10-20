
<?php
include "db.php";

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Backend validation
    if ($name == "" || $username == "" ||
        $email == "" || $password == "") {

        $message = "Please fill all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email.";

    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username)) {

        $message = "Username must be 3-50 characters (letters, numbers, underscore).";

    } elseif (strlen($password) < 8) {

        $message = "Password must be at least 8 characters.";

    } else {

        // Check duplicate email or username
        $check = $conn->prepare(
            "SELECT id FROM students WHERE email = ? OR username = ?"
        );

        $check->bind_param("ss", $email, $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $message = "Email or username already exists.";

        } else {

            // Secure password hashing
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert user into database
            $stmt = $conn->prepare(
                "INSERT INTO students (name, username, email, password)
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssss",
                $name,
                $username,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {
                $message = "Registration successful!";
                $success = true;
            } else {
                $message = "Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<center>
    <h1>StudentHub Registration</h1>

    <fieldset style="width:300px;">
        <form method="POST" action="register.php"
              onsubmit="return validateForm()">

            <label>Full Name</label><br>
            <input type="text" name="name" id="name"
                   required maxlength="100"><br><br>

            <label>Username</label><br>
            <input type="text" name="username" id="username"
                   required minlength="3" maxlength="50"
                   pattern="[a-zA-Z0-9_]{3,50}"
                   title="Use letters, numbers or underscore"><br><br>

            <label>Email</label><br>
            <input type="email" name="email" id="email"
                   required maxlength="100"><br><br>

            <label>Password</label><br>
            <input type="password" name="password" id="password"
                   required minlength="8"><br><br>

            <button type="submit">Register</button>
        </form>

        <p>
            <a href="pages/login.html">Already registered? Login</a>
        </p>

        <?php if ($message != ""): ?>
            <p style="color:<?php echo $success ? 'green' : 'red'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>
    </fieldset>
</center>

<script>
function validateForm() {
    let name = document.getElementById("name").value.trim();
    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;

    if (name === "" || username === "" || email === "" || password === "") {
        alert("Please fill all fields.");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters.");
        return false;
    }

    return true;
}
</script>
</body>
</html>
