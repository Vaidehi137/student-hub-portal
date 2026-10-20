<?php
session_start();
include "db.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit;
}

// Count students
$result = mysqli_query($conn, "SELECT * FROM students");
$totalStudents = mysqli_num_rows($result);

// Count events
$totalEvents = 0;
$check = mysqli_query($conn, "SHOW TABLES LIKE 'events'");

if (mysqli_num_rows($check) > 0) {
    $result = mysqli_query($conn, "SELECT * FROM events");
    $totalEvents = mysqli_num_rows($result);
}

// Latest registrations
$result = mysqli_query(
    $conn,
    "SELECT * FROM students ORDER BY id DESC LIMIT 5"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>StudentHub Admin Dashboard</title>
</head>
<body>

<h2>StudentHub Admin Dashboard</h2>

<h3>Menu</h3>

<a href="index.html">Student Registration</a> |
<a href="events.php">View Events</a> |
<a href="events.php">Add Event</a> |
<a href="logout.php">Logout</a>

<hr>

<h3>Total Students: <?php echo $totalStudents; ?></h3>

<h3>Total Events: <?php echo $totalEvents; ?></h3>

<hr>

<h3>Latest Registrations</h3>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Mobile</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row["id"]; ?></td>
    <td><?php echo htmlspecialchars($row["name"]); ?></td>
    <td><?php echo htmlspecialchars($row["email"]); ?></td>
    <td><?php echo htmlspecialchars($row["mobile"]); ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
