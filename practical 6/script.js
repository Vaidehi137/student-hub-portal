// LOGIN
function login() {

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let usernamePattern = /^[0-9]{2}[A-Za-z]{3}[0-9]{3,4}$/;
    let passwordPattern = /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*@)[A-Za-z0-9@]{8,}$/;

    if (username == "" || password == "") {
        alert("Please enter username and password");
    }
    else if (!usernamePattern.test(username)) {
        alert("Invalid Username");
    }
    else if (!passwordPattern.test(password)) {
        alert("Invalid Password");
    }
    else {
        alert("Login Successful!");
        window.location.href = "dashboard.html";
    }
}


// PAGE LOAD
window.onload = function() {

// FAQ
let faq = document.createElement("button");
faq.innerHTML = "What is Student Hub?";
document.body.appendChild(faq);

let answer = document.createElement("p");
answer.innerHTML = "Student Hub helps students manage college information.";
answer.style.display = "none";
document.body.appendChild(answer);

faq.onclick = function() {

    if (answer.style.display == "none") {
        answer.style.display = "block";
    }
    else {
        answer.style.display = "none";
    }
};

  // 3. NOTIFICATION BANNER
setTimeout(function() {

    let notification = document.createElement("div");

    notification.innerHTML = "🔔 New Notice: Check the Notice Board!";

    notification.style.backgroundColor = "#4a90e2";
    notification.style.color = "white";
    notification.style.padding = "10px";
    notification.style.margin = "15px";

    document.body.appendChild(notification);

}, 2000);

    // 4. HAMBURGER MENU
    let menuButton = document.createElement("button");
    menuButton.innerHTML = "☰ Menu";

    menuButton.style.position = "fixed";
    menuButton.style.top = "10px";
    menuButton.style.right = "190px";
    menuButton.style.backgroundColor = "#4a90e2";
    menuButton.style.color = "white";

    document.body.appendChild(menuButton);

    let navigation = document.querySelector("table");

    navigation.style.display = "none";

    menuButton.onclick = function() {

        if (navigation.style.display == "none") {
            navigation.style.display = "table";
        }
        else {
            navigation.style.display = "none";
        }
    };


    // 5. DARK MODE
    let darkButton = document.createElement("button");
    darkButton.innerHTML = "Dark Mode";

    darkButton.style.backgroundColor = "#4a90e2";
    darkButton.style.color = "white";
    darkButton.style.position = "fixed";
    darkButton.style.top = "10px";
    darkButton.style.right = "100px";

    document.body.appendChild(darkButton);

    darkButton.onclick = function() {

        document.body.style.backgroundColor = "black";
        document.body.style.color = "white";
    };


    // 6. LIGHT MODE
    let lightButton = document.createElement("button");
    lightButton.innerHTML = "Light Mode";

    lightButton.style.backgroundColor = "#4a90e2";
    lightButton.style.color = "white";
    lightButton.style.position = "fixed";
    lightButton.style.top = "10px";
    lightButton.style.right = "10px";
    document.body.appendChild(lightButton);
    lightButton.onclick = function() {
        document.body.style.backgroundColor = "#dff3ff";
        document.body.style.color = "black";
    };
};
// PRACTICAL 6

let students = [];

fetch("../data/student.json")
.then(response => response.json())
.then(data => {

    students = data;
    display(students);

});


function display(data) {

    let table = "<table border='1' cellpadding='10'>";

    table += "<tr>";
    table += "<th>Name</th>";
    table += "<th>Enrollment</th>";
    table += "<th>Course</th>";
    table += "<th>Semester</th>";
    table += "</tr>";

    for(let i = 0; i < data.length; i++) {

        table += "<tr>";
        table += "<td>" + data[i].name + "</td>";
        table += "<td>" + data[i].enrollment + "</td>";
        table += "<td>" + data[i].course + "</td>";
        table += "<td>" + data[i].semester + "</td>";
        table += "</tr>";

    }

    table += "</table>";

    document.getElementById("studentData").innerHTML = table;
}


function searchStudent() {

    let text = document.getElementById("searchInput").value.toLowerCase();

    let result = students.filter(function(student) {

        return student.name.toLowerCase().includes(text);

    });

    display(result);
}


function sortStudents() {

    students.sort(function(a,b) {

        return a.name.localeCompare(b.name);

    });

    display(students);
}