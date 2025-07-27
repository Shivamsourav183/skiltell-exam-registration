<?php
// Database connection parameters
$servername = "fdb28.awardspace.net";
$username = "4592354_exam";
$password = "@xZPF25QpcbmJxC";
$dbname = "4592354_exam";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $reg_no = $conn->real_escape_string($_POST['reg_no']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $abc_id = !empty($_POST['abc_id']) ? $conn->real_escape_string($_POST['abc_id']) : NULL;

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement
    $sql = "INSERT INTO users (full_name, reg_no, gender, email, password, abc_id) VALUES (?, ?, ?, ?, ?, ?)";

    // Initialize a prepared statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("ssssss", $full_name, $reg_no, $gender, $email, $hashed_password, $abc_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to success.html after successful registration
            header("Location: success.html");
            exit(); // Ensure no further code is executed after redirection
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
