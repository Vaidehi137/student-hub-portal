<?php
session_start();
include "db.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    die("Please login as admin first.");
}

if (!is_dir("uploads")) {
    mkdir("uploads");
}

// DELETE
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    mysqli_query($conn, "DELETE FROM events WHERE id=$id");
    header("Location: events.php");
    exit;
}

// ADD OR UPDATE
if (isset($_POST["save"])) {
    $id = (int)$_POST["id"];
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $date = $_POST["date"];
    $desc = mysqli_real_escape_string($conn, $_POST["description"]);
    $poster = $_POST["old_poster"];

    // Upload poster
    if (!empty($_FILES["poster"]["name"])) {
        $file = $_FILES["poster"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "webp"];

        if ($file["size"] > 2097152) {
            die("Poster must be below 2 MB.");
        }

        if (!in_array($ext, $allowed) ||
            getimagesize($file["tmp_name"]) === false) {
            die("Only JPG, PNG and WEBP images allowed.");
        }

        $poster = time() . "." . $ext;
        move_uploaded_file($file["tmp_name"], "uploads/" . $poster);
    }

    if ($id == 0) {
        $sql = "INSERT INTO events
        (event_name, event_date, description, poster)
        VALUES ('$name', '$date', '$desc', '$poster')";
    } else {
        $sql = "UPDATE events SET
        event_name='$name', event_date='$date',
        description='$desc', poster='$poster'
        WHERE id=$id";
    }

    mysqli_query($conn, $sql);
    header("Location: events.php");
    exit;
}

// EDIT
$edit = [
    "id" => 0,
    "event_name" => "",
    "event_date" => "",
    "description" => "",
    "poster" => ""
];

if (isset($_GET["edit"])) {
    $id = (int)$_GET["edit"];
    $result = mysqli_query($conn, "SELECT * FROM events WHERE id=$id");
    $edit = mysqli_fetch_assoc($result);
}

// LIST
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Events</title>
</head>
<body>

<h2>StudentHub Events</h2>

<a href="admin.php">Dashboard</a> |
<a href="logout.php">Logout</a>

<h3>Add / Update Event</h3>

<form method="post" enctype="multipart/form-data">

    <input type="hidden" name="id"
           value="<?php echo $edit['id']; ?>">

    <input type="hidden" name="old_poster"
           value="<?php echo htmlspecialchars($edit['poster'] ?? ''); ?>">

    Event Name:
    <input type="text" name="name"
           value="<?php echo htmlspecialchars($edit['event_name']); ?>"
           required><br><br>

    Event Date:
    <input type="date" name="date"
           value="<?php echo $edit['event_date']; ?>" required><br><br>

    Description:
    <input type="text" name="description"
           value="<?php echo htmlspecialchars($edit['description'] ?? ''); ?>"><br><br>

    Poster:
    <input type="file" name="poster"
           accept=".jpg,.jpeg,.png,.webp"><br>
    <small>JPG, PNG or WEBP; maximum 2 MB.</small><br><br>

    <button type="submit" name="save">Save Event</button>
</form>

<h3>All Events</h3>

<table border="1" cellpadding="6">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Date</th>
    <th>Description</th>
    <th>Poster</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row["id"]; ?></td>
    <td><?php echo htmlspecialchars($row["event_name"]); ?></td>
    <td><?php echo $row["event_date"]; ?></td>
    <td><?php echo htmlspecialchars($row["description"] ?? ""); ?></td>
    <td>
        <?php if (!empty($row["poster"])) { ?>
            <img src="uploads/<?php echo rawurlencode(basename($row["poster"])); ?>"
                 width="60" alt="Poster">
        <?php } ?>
    </td>
    <td>
        <a href="events.php?edit=<?php echo $row["id"]; ?>">Edit</a> |
        <a href="events.php?delete=<?php echo $row["id"]; ?>"
           onclick="return confirm('Delete this event?')">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
