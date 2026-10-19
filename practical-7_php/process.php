
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username == "" || $password == "") {
        echo "Please enter username and password.";
        exit;
    }

    // CSV file create karvi
    $file = fopen("login_data.csv", "a");

    if ($file !== false) {

        // Username ane date/time save karva
        fputcsv($file, [
            $username,
            date("Y-m-d H:i:s")
        ]);

        fclose($file);

    } else {
        die("Unable to create CSV file.");
    }

    // Dashboard open karvu
    header("Location: pages/dashboard.html");
    exit;

} else {
    header("Location: pages/login.html");
    exit;
}

?>
