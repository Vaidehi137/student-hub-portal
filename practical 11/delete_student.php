
<?php
include "db.php";

if (isset($_POST["delete"]) && isset($_POST["id"])) {

    $id = (int) $_POST["id"];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM students WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: view_students.php?msg=deleted");
            exit;
        } else {
            echo "Student not found.";
        }

    } else {
        echo "Failed to delete student.";
    }

    mysqli_stmt_close($stmt);

} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>

<br>
<a href="view_students.php">Back to Student List</a>
