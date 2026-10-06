<?php

include "db.php";

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $course = $_POST["course"];

    if ($name == "" || $email == "" || $mobile == "" || $course == "") {

        echo "Please fill all fields.";

    } 
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "Enter valid email.";

    } 
    else if (!preg_match("/^[0-9]{10}$/", $mobile)) {

        echo "Enter valid 10 digit mobile number.";

    } 
    else {

        $sql = "INSERT INTO students (name, email, mobile, course)
                VALUES ('$name', '$email', '$mobile', '$course')";

        if (mysqli_query($conn, $sql)) {

            echo "Registration Successful!";

        } 
        else {

            echo "Error while registering.";

        }
    }
}

?>

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

</body>

</html>