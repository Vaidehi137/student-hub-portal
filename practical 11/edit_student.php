
<?php
include "db.php";

$message = "";
$id = 0;
$name = "";
$email = "";
$mobile = "";
$course = "";

// Get student details
if (isset($_GET["id"])) {

    $id = (int) $_GET["id"];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM students WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $name = $row["name"];
        $email = $row["email"];
        $mobile = $row["mobile"];
        $course = $row["course"];
    } else {
        die("Student not found.");
    }

    mysqli_stmt_close($stmt);

} else {
    die("Student ID is missing.");
}

// Update student details
if (isset($_POST["update"])) {

    $id = (int) $_POST["id"];
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $mobile = trim($_POST["mobile"]);
    $course = trim($_POST["course"]);

    if ($name == "" || $email == "" ||
        $mobile == "" || $course == "") {

        $message = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE students
             SET name = ?, email = ?, mobile = ?, course = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $name,
            $email,
            $mobile,
            $course,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: view_students.php?msg=updated");
            exit;

        } else {

            $message = "Failed to update student.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>

<h2>Edit Student Details</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">

    <input type="hidden" name="id"
           value="<?php echo $id; ?>">

    Name:
    <input type="text" name="name"
           value="<?php echo htmlspecialchars($name); ?>"
           required>
    <br><br>

    Email:
    <input type="email" name="email"
           value="<?php echo htmlspecialchars($email); ?>"
           required>
    <br><br>

    Mobile:
    <input type="text" name="mobile"
           value="<?php echo htmlspecialchars($mobile); ?>"
           required>
    <br><br>

    Course:
    <input type="text" name="course"
           value="<?php echo htmlspecialchars($course); ?>"
           required>
    <br><br>

    <input type="submit" name="update"
           value="Update Student">

</form>

<br>

<a href="view_students.php">Back to Student List</a>

</body>
</html>
