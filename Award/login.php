<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Database connection
    $conn = new mysqli('fdb28.awardspace.net','4592354_exam','@xZPF25QpcbmJxC','4592354_exam');

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare a query to check if the user exists
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if a user with the provided email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify the provided password with the hashed password
        if (password_verify($password, $hashed_password)) {
            // Login successful
            $_SESSION['user_id'] = $user_id; // Store user ID in session
            $_SESSION['email'] = $email; // Store email in session
            header("Location: loading.php"); // Redirect to the dashboard
            exit();
        } else {
            // Wrong password
            echo "<script>alert('Wrong password! Please try again.'); window.location.href = 'login.html';</script>";
        }
    } else {
        // No user found with the provided email
        echo "<script>alert('Data not found! Please register first.'); window.location.href = 'register.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.html");
    exit();
}
?>
