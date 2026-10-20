<?php
include "db.php";

$message = "";

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("Invalid event ID.");
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$event) {
    die("Event not found.");
}

if (isset($_POST['update_event'])) {

    $name = trim($_POST['event_name']);
    $date = $_POST['event_date'];
    $description = trim($_POST['description']);
    $poster = $event['poster'];

    if ($name == "" || $date == "") {
        $message = "Event name and date are required.";
    } else {

        if (isset($_FILES['poster']) &&
            $_FILES['poster']['error'] != 4) {

            $file = $_FILES['poster'];
            $allowed = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp"
            ];

            if ($file['error'] != 0) {
                $message = "Poster upload failed.";
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $message = "Poster must be 2 MB or smaller.";
            } else {

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $type = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!isset($allowed[$type]) ||
                    getimagesize($file['tmp_name']) === false) {

                    $message = "Only valid JPG, PNG and WEBP images are allowed.";
                } else {

                    $newPoster = uniqid("event_") . "." . $allowed[$type];
                    $path = __DIR__ . "/uploads/" . $newPoster;

                    if (move_uploaded_file($file['tmp_name'], $path)) {
                        $poster = $newPoster;
                    } else {
                        $message = "Unable to upload poster.";
                    }
                }
            }
        }

        if ($message == "") {

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE events
                 SET event_name = ?, event_date = ?,
                     description = ?, poster = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt, "ssssi",
                $name, $date, $description, $poster, $id
            );

            if (mysqli_stmt_execute($stmt)) {

                if ($poster != $event['poster'] &&
                    !empty($event['poster'])) {

                    $oldPath = __DIR__ . "/uploads/" .
                               basename($event['poster']);

                    if (is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }

                header("Location: view_events.php");
                exit;

            } else {
                $message = "Unable to update event.";

                if ($poster != $event['poster']) {
                    $newPath = __DIR__ . "/uploads/" .
                               basename($poster);
                    if (is_file($newPath)) {
                        unlink($newPath);
                    }
                }
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
</head>
<body>

<h2>Update Event</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST" enctype="multipart/form-data">

    Event Name:
    <input type="text" name="event_name"
           value="<?php echo htmlspecialchars($event['event_name']); ?>"
           required>
    <br><br>

    Event Date:
    <input type="date" name="event_date"
           value="<?php echo htmlspecialchars($event['event_date']); ?>"
           required>
    <br><br>

    Description:
    <textarea name="description"><?php
        echo htmlspecialchars($event['description'] ?? '');
    ?></textarea>
    <br><br>

    Current Poster:
    <?php if (!empty($event['poster']) &&
              is_file(__DIR__ . "/uploads/" . basename($event['poster']))) { ?>
        <img src="<?php echo 'uploads/' . rawurlencode(basename($event['poster'])); ?>"
             width="100" alt="Current poster">
    <?php } else { ?>
        No poster
    <?php } ?>
    <br><br>

    Change Poster:
    <input type="file" name="poster"
           accept=".jpg,.jpeg,.png,.webp">
    <p>JPG, PNG or WEBP. Maximum 2 MB.</p>

    <button type="submit" name="update_event">Update Event</button>

</form>

<br>
<a href="view_events.php">Back to Events</a>

</body>
</html>