<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the user is logged in (email is stored in session)
if (!isset($_SESSION['email'])) {
    die("User not logged in. Please login first.");
}

$email = $_SESSION['email'];

// Database connection settings – update these with your actual credentials.
$host = 'fdb28.awardspace.net';
$dbUsername = '4592354_exam';
$dbPassword = '@xZPF25QpcbmJxC'; // Replace with your actual password
$dbName = '4592354_exam';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the BA column is already "yes"
$stmt = $conn->prepare("SELECT SEAD FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($baStatus);
$stmt->fetch();
$stmt->close();

// Debug output (you may remove this after testing)
// echo "Email: $email, BA Status: $baStatus";

if ($baStatus === "yes") {
    // If already registered, display a message
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>Already Registered</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #ffffff;
          color: #000000;
          margin: 0;
          padding: 20px;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
        }
        .message-box {
          text-align: center;
          padding: 20px;
          border: 2px solid #000000;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
          background-color: #ffffff;
        }
        .message-box h2 {
          margin-bottom: 15px;
        }
        .home-button {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          background-color: #000000;
          color: #ffffff;
          padding: 10px 20px;
          border-radius: 5px;
          text-decoration: none;
          font-weight: bold;
          transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .home-button:hover {
          background-color: #333333;
          transform: scale(1.05);
        }
        .home-icon {
          width: 20px;
          height: 20px;
          filter: invert(1);
        }
      </style>
    </head>
    <body>
      <div class='message-box'>
        <h2>Already Registered for SEAD Course.</h2>
        <p><a href='dashboard.php' class='home-button'>
          <img src='https://cdn-icons-png.flaticon.com/512/25/25694.png' alt='Home' class='home-icon'> Home
        </a></p>
      </div>
    </body>
    </html>";
    exit();
} else {
    // Update the BA column to 'yes'
    $updateStmt = $conn->prepare("UPDATE users SET SEAD = 'yes' WHERE email = ?");
    if (!$updateStmt) {
        die("Prepare update failed: " . $conn->error);
    }
    $updateStmt->bind_param("s", $email);
    if ($updateStmt->execute()) {
        // Redirect to success_BA.html after a successful update.
        header("Location: success_SEAD.html");
        exit();
    } else {
        die("Update execution failed: " . $updateStmt->error);
    }
    $updateStmt->close();
}

$conn->close();
?>
