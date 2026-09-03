function validateForm()
{
    // Get values from form
    let name=document.getElementById("name").value;
    let enrollment=document.getElementById("enrollment").value;
    let email=document.getElementById("email").value;
    let mobile=document.getElementById("mobile").value;
    let password=document.getElementById("password").value;
    let confirmPassword=document.getElementById("confirmPassword").value;
    let course=document.getElementById("course").value;
    let year=document.getElementById("year").value;
    let terms=document.getElementById("terms").checked;

    // Regular Expressions
    let nameRegex = /^[A-Za-z ]{3,}$/;
    let enrollmentRegex = /^(25DCE[0-9]{3}|26DCE[0-9]{3})$/;
    let emailRegex = /^[A-Za-z0-9._%+-]+@charusat\.edu\.in$/;
    let mobileRegex = /^[6-9][0-9]{9}$/;
    let passwordRegex = /^(?=.*[A-Za-z])(?=.*[0-9]).{8,}$/;

    // Check Name
    if (!nameRegex.test(name)){
        alert("Please enter a valid name.");
        return false;
    }

    // Check Enrollment Number
    if (!enrollmentRegex.test(enrollment)){
        alert("Please enter a valid enrollment number.");
        return false;
    }

    // Check Email
    if (!emailRegex.test(email)){
        alert("Please enter a valid CHARUSAT email.");
        return false;
    }
console.log(mobile);
console.log(mobileRegex.test(mobile));

    // Check Mobile Number
    if (!mobileRegex.test(mobile)){
        alert("Please enter a valid 10 digit mobile number.");
        return false;
    }
    // Check Password
    if (!passwordRegex.test(password)){
        alert("Password must contain at least 8 characters, one letter and one number.");
        return false;
    }

    // Check Confirm Password
    if (password != confirmPassword){
        alert("Password and Confirm Password do not match.");
        return false;
    }
    // Check Course
    if (course == ""){
        alert("Please select a course.");
        return false;
    }
    // Check Year
    if (year == ""){
        alert("Please select your year.");
        return false;
    }
    // Check Terms
    if (terms == false){
        alert("Please accept the Terms and Conditions.");
        return false;
    }
    // Everything is correct
    alert("Registration Successful!");
    return true;
}