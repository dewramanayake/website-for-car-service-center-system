<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'car_service');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update packages logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_package'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE packages SET name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $name, $price, $description, $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Package updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating package: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Update customization logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['total_projects'])) {
    $total_projects = $_POST['total_projects'];
    $transparency = $_POST['transparency'];
    $done_projects = $_POST['done_projects'];
    $got_awards = $_POST['got_awards'];

    $stmt = $conn->prepare("UPDATE customisation SET total_projects = ?, transparency = ?, done_projects = ?, got_awards = ? WHERE id = 1");
    $stmt->bind_param("ssss", $total_projects, $transparency, $done_projects, $got_awards);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Customisation updated successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating customisation: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Admin actions logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_action'])) {
    $action = $_POST['admin_action'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Admin added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error adding admin: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    if ($action === 'update') {
        $stmt = $conn->prepare("UPDATE admin SET username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $password, $id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Admin updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating admin: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Admin deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting admin: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

// Handle appointment actions delete and update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_action'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['appointment_action'];

    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error: Error deleting appointment: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action === 'update_status') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'complete' WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error: Error updating appointment status: " . $stmt->error;
        }
        $stmt->close();
    }
    exit;
}



// Fetch packages
$packages_result = $conn->query("SELECT * FROM packages");

// Fetch customization data
$customisation_result = $conn->query("SELECT * FROM customisation WHERE id = 1");
$customisation_data = $customisation_result->fetch_assoc();

// Fetch admin data
$admin_result = $conn->query("SELECT * FROM admin");

// Fetch appointments
$appointments_result = $conn->query("SELECT * FROM appointments ORDER BY created_at DESC");



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("assets/gg.jpg");
            background-size: 100%;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            margin-left: 200px;
        }
        h2 {
            margin-bottom: 10px;
            font-size: 24px;
            text-align: center;
            color: #4CAF50;
        }
        h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
            outline: none;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
            margin: 5px 0;
        }
        .btn-primary {
            background-color: #4CAF50;
            border: 1px solid #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .btn-secondary {
            background-color: #007BFF;
            border: 1px solid #007BFF;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            color: white;
            text-align: center;
            opacity: 1;
            transition: opacity 0.5s linear;
        }
        .alert-success {
            background-color: #4CAF50;
        }
        .alert-danger {
            background-color: #f44336;
        }
        .button-container {
            position: absolute;
            left: 50px;
            top: 80px;
            display: flex;
            flex-direction: column;
        }
        .hidden {
            display: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button class="btn btn-secondary" onclick="showSection('services')">Services</button>
        <button class="btn btn-secondary" onclick="showSection('customisation')">Customisation</button>
        <button class="btn btn-secondary" onclick="showSection('admins')">Admins</button>
        <button class="btn btn-secondary" onclick="showSection('appointment')">Appointment</button>
        <a href="index.php" class="btn btn-secondary">Back to Website</a>
    </div>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <div id="customisation" class="hidden">
            <h3>Customisation Update</h3>
            <form method="post">
                <div class="form-group">
                    <label for="total_projects">Total Projects:</label>
                    <input type="text" class="form-control" id="total_projects" name="total_projects" value="<?= $customisation_data['total_projects'] ?>">
                </div>
                <div class="form-group">
                    <label for="transparency">Transparency:</label>
                    <input type="text" class="form-control" id="transparency" name="transparency" value="<?= $customisation_data['transparency'] ?>">
                </div>
                <div class="form-group">
                    <label for="done_projects">Done Projects:</label>
                    <input type="text" class="form-control" id="done_projects" name="done_projects" value="<?= $customisation_data['done_projects'] ?>">
                </div>
                <div class="form-group">
                    <label for="got_awards">Got Awards:</label>
                    <input type="text" class="form-control" id="got_awards" name="got_awards" value="<?= $customisation_data['got_awards'] ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <div id="services">
            <h3>Services Update</h3>
            <?php while ($row = $packages_result->fetch_assoc()): ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="update_package" value="1">
                    <div class="form-group">
                        <label for="name">Package Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" class="form-control" id="price" name="price" value="<?= $row['price'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= $row['description'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            <?php endwhile; ?>
        </div>

        <div id="admins" class="hidden">
            <h3>Admin Management</h3>
            <form method="post">
                <input type="hidden" name="admin_action" value="add">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Admin</button>
            </form>

            <div id="admins">
            <h3>Admin Actions</h3>
            <form method="post">
                <div class="form-group">
                    <label for="admin_select">Select Admin:</label>
                    <select class="form-control" id="admin_select" name="id" onchange="loadAdminData(this.value)">
                        <option value="">Select an admin</option>
                        <?php while ($row = $admin_result->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['username'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="admin_action">Action:</label>
                    <select class="form-control" id="admin_action" name="admin_action">
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <div id="appointment" class="hidden">
    <h3>Appointments</h3>
    <?php if ($appointments_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['message'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <button type="button" onclick="deleteAppointment(<?= $row['id'] ?>, this.closest('tr'))" class="btn btn-danger">Delete</button>
                            <?php if ($row['status'] === 'pending'): ?>
                                <button type="button" onclick="updateAppointmentStatus(<?= $row['id'] ?>, this.closest('tr'))" class="btn btn-success">Mark as Complete</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>


    <script>
        function showSection(sectionId) {
            document.getElementById('customisation').classList.add('hidden');
            document.getElementById('services').classList.add('hidden');
            document.getElementById('admins').classList.add('hidden');
            document.getElementById('appointment').classList.add('hidden');
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function loadAdminData(adminId) {
            if (adminId === "") {
                document.getElementById('username').value = "";
                document.getElementById('password').value = "";
                return;
            }

            const admins = <?= json_encode($admin_result->fetch_all(MYSQLI_ASSOC)) ?>;
            const admin = admins.find(a => a.id == adminId);

            if (admin) {
                document.getElementById('username').value = admin.username;
                document.getElementById('password').value = admin.password;
            }
        }

        function updateAppointmentStatus(appointmentId, row) {
    const formData = new FormData();
    formData.append('appointment_action', 'update_status');
    formData.append('appointment_id', appointmentId);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            row.querySelector('td:nth-child(5)').textContent = 'complete'; // Update the status cell
            row.querySelector('.btn-success').remove(); // Remove the "Mark as Complete" button
        } else {
            alert('Failed to update appointment status: ' + data);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function deleteAppointment(appointmentId, row) {
    const formData = new FormData();
    formData.append('appointment_action', 'delete');
    formData.append('appointment_id', appointmentId);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            row.remove();
        } else {
            alert('Failed to delete appointment: ' + data);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}


        // Show services section by default
        document.addEventListener("DOMContentLoaded", function() {
            showSection('services');
        });

        // Function to hide alert
        function hideAlert() {
            var alert = document.querySelector('.alert');
            if (alert) {
                alert.style.opacity = 0;
                setTimeout(function() {
                    alert.classList.add('hidden');
                }, 500);
            }
        }

        // Hide alert when clicking anywhere else on the page
        document.addEventListener('click', function(event) {
            var alert = document.querySelector('.alert');
            if (alert && !alert.contains(event.target)) {
                hideAlert();
            }
        });
    </script>
</body>
</html>
