<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "health360");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Collect form data
$id = $_POST['id'];
$password = $_POST['password'];
$role = $_POST['role'];

if (empty($id) || empty($password) || empty($role)) {
    echo "<script>alert('Please fill in all fields.'); window.location.href='index.html';</script>";
    exit();
}

// Determine table and fields based on role
switch ($role) {
    case "User":
        $table = "users";
        $idField = "user_id";
        $redirect = "home.html";
        break;
    case "Admin":
        $table = "admins";
        $idField = "admin_id";
        $redirect = "admin.html";
        break;
    case "Pharmacy":
        $table = "pharmacy_staff";
        $idField = "pharmacy_id";
        $redirect = "pharmacy.html";
        break;
    default:
        echo "<script>alert('Invalid role selected.'); window.location.href='index.html';</script>";
        exit();
}

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM $table WHERE $idField = ? AND password = ?");
$stmt->bind_param("ss", $id, $password); // "ss" means two strings
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows == 1) {
    // Login success — redirect by role
    echo "<script>alert('Login successful! Redirecting...'); window.location.href='$redirect';</script>";
} else {
    // Invalid login
    echo "<script>alert('Invalid ID, Password, or Role. Please try again.'); window.location.href='index.html';</script>";
}

$stmt->close();
$conn->close();
?>
