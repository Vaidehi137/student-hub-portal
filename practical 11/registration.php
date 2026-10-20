
<?php
include "db.php";

$message = "";
$messageType = "";

if (isset($_POST["submit"])) {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");

    if ($name === "" || $email === "" ||
        $mobile === "" || $course === "") {
        $message = "Please fill all fields.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email.";
        $messageType = "error";

    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $message = "Enter a valid 10 digit mobile number.";
        $messageType = "error";

    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO students (name, email, mobile, course)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "ssss", $name, $email, $mobile, $course
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Registration successful!";
            $messageType = "success";
        } else {
            $message = "Registration failed: " .
                       mysqli_stmt_error($stmt);
            $messageType = "error";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
</head>
<body>

<h2>Student Registration Form</h2>

<?php if ($message !== ""): ?>
    <p style="color:<?= $messageType === "success" ? "green" : "red" ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="post">
    Name:
    <input type="text" name="name" required>
    <br><br>

    Email:
    <input type="email" name="email" required>
    <br><br>

    Mobile:
    <input type="text" name="mobile" maxlength="10" required>
    <br><br>

    Course:
    <input type="text" name="course" required>
    <br><br>

    <input type="submit" name="submit" value="Register">
</form>

</body>
</html>
