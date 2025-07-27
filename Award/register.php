<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $reg_no = $_POST['reg_no'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $abc_id = $_POST['abc_id'] ?? '';

    if (empty($full_name) || empty($reg_no) || empty($gender) || empty($email) || empty($password)) {
        die("Error: All fields except ABC ID are required.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Database connection
    $conn = new mysqli('fdb28.awardspace.net','4592354_exam','@xZPF25QpcbmJxC','4592354_exam');
    
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (full_name, reg_no, gender, email, password, abc_id) VALUES (?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssss", $full_name, $reg_no, $gender, $email, $hashed_password, $abc_id);
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: register.html");
    exit();
}
?>
