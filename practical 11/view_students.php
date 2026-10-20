
<?php
include "db.php";

// Show success messages
$message = "";

if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "updated") {
        $message = "Student updated successfully!";
    } elseif ($_GET["msg"] == "deleted") {
        $message = "Student deleted successfully!";
    }
}

// Search student
$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

// Filter by course
$course = "";

if (isset($_GET["course"])) {
    $course = $_GET["course"];
}

// Fetch student records
$sql = "SELECT * FROM students
        WHERE (name LIKE ? OR email LIKE ?)";

$searchValue = "%" . $search . "%";

if ($course != "") {
    $sql = $sql . " AND course = ?";
}

$stmt = mysqli_prepare($conn, $sql);

if ($course != "") {
    mysqli_stmt_bind_param(
        $stmt, "sss",
        $searchValue, $searchValue, $course
    );
} else {
    mysqli_stmt_bind_param(
        $stmt, "ss",
        $searchValue, $searchValue
    );
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
</head>

<body>

<h2>Student Management System</h2>

<p>
    <?php echo htmlspecialchars($message); ?>
</p>

<h3>Search and Filter Students</h3>

<form method="GET">

    Search:
    <input type="text" name="search"
           placeholder="Enter name or email"
           value="<?php echo htmlspecialchars($search); ?>">

    Course:

    <select name="course">
        <option value="">All Courses</option>

        <?php
        $courseResult = mysqli_query(
            $conn,
            "SELECT DISTINCT course FROM students ORDER BY course"
        );

        while ($c = mysqli_fetch_assoc($courseResult)) {
        ?>
            <option
                value="<?php echo htmlspecialchars($c["course"]); ?>"
                <?php
                if ($course == $c["course"]) {
                    echo "selected";
                }
                ?>>
                <?php echo htmlspecialchars($c["course"]); ?>
            </option>
        <?php } ?>

    </select>

    <input type="submit" value="Search / Filter">

    <a href="view_students.php">Show All</a>

</form>

<br>

<h3>Student Records</h3>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Course</th>
        <th>Action</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
    ?>

    <tr>
        <td><?php echo $row["id"]; ?></td>

        <td>
            <?php echo htmlspecialchars($row["name"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($row["email"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($row["mobile"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($row["course"]); ?>
        </td>

        <td>
            <a href="edit_student.php?id=<?php echo $row['id']; ?>">
                Edit
            </a>

            <form action="delete_student.php" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this student?');">

                <input type="hidden" name="id"
                       value="<?php echo $row['id']; ?>">

                <input type="submit" name="delete"
                       value="Delete">
            </form>
        </td>
    </tr>

    <?php
        }

    } else {
    ?>

    <tr>
        <td colspan="6">No students found.</td>
    </tr>

    <?php } ?>

</table>

<br>

<a href="registration.php">Add New Student</a>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
