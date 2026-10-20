
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>

    <style>
        body {
            font-family: Arial;
            margin: 30px;
            background: #f2f2f2;
        }

        input {
            padding: 8px;
            margin: 5px;
        }

        button {
            padding: 8px;
            margin: 3px;
            cursor: pointer;
        }

        table {
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid black;
        }
    </style>
</head>

<body>

<h2>Student Registration Form</h2>

<form id="myForm">
    <input type="text" id="name" placeholder="Name" required>
    <input type="email" id="email" placeholder="Email" required>
    <input type="text" id="mobile" placeholder="Mobile" required>

    <button type="submit">Register</button>
</form>

<p id="msg"></p>

<h3>Student List</h3>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody id="list"></tbody>
</table>

<script>
const api = "api/students.php";

// LIST
function showStudents() {
    fetch(api)
    .then(res => res.json())
    .then(data => {
        let rows = "";

        data.forEach(s => {
            rows += `<tr>
                <td>${s.id}</td>
                <td>${s.name}</td>
                <td>${s.email}</td>
                <td>${s.mobile}</td>
                <td>
                    <button onclick="editStudent(${s.id}, '${s.name}', '${s.email}', '${s.mobile}')">Update</button>
                    <button onclick="deleteStudent(${s.id})">Delete</button>
                </td>
            </tr>`;
        });

        document.getElementById("list").innerHTML = rows;
    });
}

// ADD
document.getElementById("myForm").onsubmit = function(e) {
    e.preventDefault();

    let student = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        mobile: document.getElementById("mobile").value
    };

    fetch(api, {
        method: "POST",
        body: JSON.stringify(student)
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("msg").innerText = data.message;
        document.getElementById("myForm").reset();
        showStudents();
    });
};

// UPDATE
function editStudent(id, name, email, mobile) {
    let newName = prompt("Enter name", name);
    let newEmail = prompt("Enter email", email);
    let newMobile = prompt("Enter mobile", mobile);

    if (newName === null || newEmail === null || newMobile === null) return;

    fetch(api, {
        method: "PUT",
        body: JSON.stringify({
            id: id,
            name: newName,
            email: newEmail,
            mobile: newMobile
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("msg").innerText = data.message;
        showStudents();
    });
}

// DELETE
function deleteStudent(id) {
    if (confirm("Delete this student?")) {
        fetch(api + "?id=" + id, {
            method: "DELETE"
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("msg").innerText = data.message;
            showStudents();
        });
    }
}

showStudents();
</script>

</body>
</html>
