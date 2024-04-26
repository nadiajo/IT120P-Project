<?php
// Start the session
session_start();

// Include your database connection file
include 'db_connect.php'; // make sure you have a file that returns a database connection

// Function to sanitize input data
function sanitizeData($data) {
    // Use your db_connect function to get the connection
    $conn = db_connect();
    $clean_data = mysqli_real_escape_string($conn, trim($data));
    mysqli_close($conn);
    return $clean_data;
}

// Function to handle user registration
function registerUser($email, $password, $fullName, $phoneNumber) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Database connection
    $conn = db_connect();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists.';
        return false;
    }
    
    // Prepare SQL statement for inserting the new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email_address, password, phone_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $email, $hashedPassword, $phoneNumber);
    $success = $stmt->execute();
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
    if ($success) {
        // Set a success message
        $_SESSION['success_msg'] = 'Registration successful! You can now log in.';
        header('Location: login.php'); // Redirect to the login page
        exit();
    } else {
        $_SESSION['error'] = 'Error during registration.';
        return false;
    }
}

// Function to handle user login
function loginUser($email, $password) {
    // Database connection
    $conn = db_connect();
    
    // Prepare SQL statement for selecting the user
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_address = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user'] = $user;
        $_SESSION['success'] = "You are now logged in";
        
        // Redirect to appropriate dashboard
        if ($user['is_admin']) {
            header('location: /admin/home.php');
        } else {
            header('location: /Home.php');
        }
        exit();
    } else {
        $_SESSION['error'] = 'Wrong username/password combination.';
        return false;
    }
}
