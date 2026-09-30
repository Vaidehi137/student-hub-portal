<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
</head>

<body>

<h2>Student Registration Form</h2>

<form method="post">

    Name:
    <input type="text" name="name">
    <br><br>

    Email:
    <input type="text" name="email">
    <br><br>

    Mobile:
    <input type="text" name="mobile">
    <br><br>

    Course:
    <input type="text" name="course">
    <br><br>

    <input type="submit" name="submit" value="Register">

</form>

<?php

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $course = $_POST["course"];

    if ($name == "" || $email == "" || $mobile == "" || $course == "") {

        echo "<p>Please fill all fields.</p>";

    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<p>Enter valid email.</p>";

    }
    else if (!preg_match("/^[0-9]{10}$/", $mobile)) {

        echo "<p>Enter valid 10 digit mobile number.</p>";

    }
    else {

        $file = fopen("students.csv", "a");

        fputcsv($file, [$name, $email, $mobile, $course]);

        fclose($file);

        echo "<h3>Registration Successful!</h3>";
    }
}

?>

</body>
</html>