<?php
session_start();

// Check if the user is logged in; if not, redirect to login page.
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


// Database connection parameters.
$host = 'fdb28.awardspace.net';
$username = '4592354_exam';
$password = '@xZPF25QpcbmJxC'; // Replace with your actual password
$dbname = '4592354_exam';

// Create a new MySQLi connection.
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['email'];

// Fetch the user's full name and profile photo from the database.
$sql  = "SELECT full_name, profile_photo FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $full_name     = $row['full_name'];
    // If a profile photo exists, prepend "uploads/" to the filename; otherwise, you may choose to show a default image.
    $profile_photo = !empty($row['profile_photo']) ? "uploads/" . $row['profile_photo'] : "default_profile.png";
} else {
    // In case no user data is found.
    $full_name     = "User";
    $profile_photo = "default_profile.png";
}

$stmt->close();
$conn->close();
?>

<!-- Include dashboard.html -->
<?php include 'dashboard.html'; ?>

