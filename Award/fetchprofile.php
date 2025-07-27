<?php
session_start();

$host = 'fdb28.awardspace.net';
$username = '4592354_exam';
$password = '@xZPF25QpcbmJxC'; // Replace with your actual password
$dbname = '4592354_exam';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

// Fetch user details from database
$sql = "SELECT full_name, email, age, phone, address, profile_photo FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'];
    $email = $row['email'];
    $age = $row['age'];
    $phone = $row['phone'];
    $address = $row['address'];
    // Prepend the uploads folder path so the image is correctly located
    $profile_photo = $row['profile_photo'] ? "uploads/" . $row['profile_photo'] : 'https://via.placeholder.com/100';
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!-- Include myprofile.html -->
<?php include 'myprofile.html'; ?>
