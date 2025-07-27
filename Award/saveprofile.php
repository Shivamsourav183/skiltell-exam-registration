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

$user_email = $_SESSION['email'];
$age = $_POST['age'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$profile_photo = ""; // Default empty value

// Process profile photo upload if provided
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = basename($_FILES["profile_photo"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    
    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $profile_photo = $file_name; // Save only the filename
        } else {
            echo "Error uploading the profile photo.";
            exit();
        }
    } else {
        echo "Only JPG, JPEG, PNG & GIF files are allowed for the profile photo.";
        exit();
    }
}

// Build the update query based on whether a new profile photo was uploaded
if (!empty($profile_photo)) {
    $update_sql = "UPDATE users SET age = ?, phone = ?, address = ?, profile_photo = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssss", $age, $phone, $address, $profile_photo, $user_email);
} else {
    $update_sql = "UPDATE users SET age = ?, phone = ?, address = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssss", $age, $phone, $address, $user_email);
}

if ($update_stmt->execute()) {
    // Redirect back to the profile display page (fetchprofile.php)
    header("Location: fetchprofile.php");
    exit();
} else {
    echo "Error updating profile: " . $conn->error;
}

$update_stmt->close();
$conn->close();
?>
