<?php
include "db.php";

$result = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Events</title>
</head>
<body>

<h2>All Events</h2>

<a href="add_event.php">Add New Event</a>
<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Event Name</th>
    <th>Date</th>
    <th>Description</th>
    <th>Poster</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['event_name']); ?></td>
    <td><?php echo htmlspecialchars($row['event_date']); ?></td>
    <td><?php echo htmlspecialchars($row['description'] ?? ''); ?></td>

    <td>
        <?php if (!empty($row['poster']) &&
                  is_file(__DIR__ . "/uploads/" . basename($row['poster']))) { ?>
            <img src="<?php echo 'uploads/' . rawurlencode(basename($row['poster'])); ?>"
                 width="100" alt="Event poster">
        <?php } else { ?>
            No poster
        <?php } ?>
    </td>

    <td>
        <a href="edit_event.php?id=<?php echo $row['id']; ?>">Edit</a>

        <form action="delete_event.php" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this event?');">
            <input type="hidden" name="id"
                   value="<?php echo $row['id']; ?>">
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>