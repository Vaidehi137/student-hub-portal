<?php
include "db.php";

$message = "";

if (isset($_POST["add_event"])) {

    $event_name = trim($_POST["event_name"]);
    $event_date = $_POST["event_date"];
    $description = trim($_POST["description"]);
    $poster = "";

    if ($event_name == "" || $event_date == "") {
        $message = "Please enter event name and date.";
    } else {

        // Check whether a poster is uploaded
        if (isset($_FILES["poster"]) &&
            $_FILES["poster"]["error"] != 4) {

            $file = $_FILES["poster"];
            $maxSize = 2 * 1024 * 1024;

            $allowedTypes = [
                "image/jpeg",
                "image/png",
                "image/webp"
            ];

            if ($file["error"] != 0) {
                $message = "Poster upload failed.";

            } elseif ($file["size"] > $maxSize) {
                $message = "Poster must be 2 MB or smaller.";

            } else {

                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileType = finfo_file(
                    $fileInfo,
                    $file["tmp_name"]
                );
                finfo_close($fileInfo);

                if (!in_array($fileType, $allowedTypes) ||
                    getimagesize($file["tmp_name"]) === false) {

                    $message = "Only valid JPG, PNG and WEBP images are allowed.";

                } else {

                    $extensions = [
                        "image/jpeg" => "jpg",
                        "image/png" => "png",
                        "image/webp" => "webp"
                    ];

                    $poster = uniqid("event_") . "." .
                              $extensions[$fileType];

                    $uploadPath = __DIR__ . "/uploads/" . $poster;

                    if (!move_uploaded_file(
                        $file["tmp_name"],
                        $uploadPath
                    )) {
                        $message = "Unable to save poster.";
                        $poster = "";
                    }
                }
            }
        }

        if ($message == "") {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO events
                (event_name, event_date, description, poster)
                VALUES (?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $event_name,
                $event_date,
                $description,
                $poster
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Event added successfully!";
            } else {
                $message = "Failed to add event.";

                if ($poster != "" &&
                    file_exists(__DIR__ . "/uploads/" . $poster)) {
                    unlink(__DIR__ . "/uploads/" . $poster);
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
    <title>Add Event</title>
</head>
<body>

<h2>Add New Event</h2>

<p>
    <?php echo htmlspecialchars($message); ?>
</p>

<form method="POST" enctype="multipart/form-data">

    <label>Event Name:</label>
    <input type="text" name="event_name" required>
    <br><br>

    <label>Event Date:</label>
    <input type="date" name="event_date" required>
    <br><br>

    <label>Description:</label>
    <textarea name="description"></textarea>
    <br><br>

    <label>Event Poster:</label>
    <input type="file" name="poster"
           accept=".jpg,.jpeg,.png,.webp">

    <p>Allowed: JPG, PNG, WEBP. Maximum size: 2 MB.</p>

    <input type="submit" name="add_event"
           value="Add Event">

</form>

<br>
<a href="view_events.php">View All Events</a>

</body>
</html>

