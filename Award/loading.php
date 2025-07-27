<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection parameters – update as needed.
$db_host = 'fdb28.awardspace.net';
$db_user = '4592354_exam';
$db_pass = '@xZPF25QpcbmJxC'; // Replace with your actual password
$db_name = '4592354_exam';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user's full name and profile photo filename.
$sql = "SELECT full_name, profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($full_name, $profile_photo);
$stmt->fetch();
$stmt->close();
$conn->close();

// If no profile photo, use a default image. Otherwise, prefix with "uploads/".
if (empty($profile_photo)) {
    $profile_photo = "uploads/default.png";
} else {
    $profile_photo = "uploads/" . $profile_photo;
}
?>


<!-- Include dashboard.html -->
<?php include 'loading.html'; ?>


