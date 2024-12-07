<?php
session_start();

// Include database connection file
include "../db_conn.php";

// Include validation helper functions if needed
// include "func-validation.php";

// Check if registration form fields are submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get data from POST request
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];
    $confirmPassword = $_POST['confirm_password'];

    // Check for empty fields
    $user_input = 'email=' . $email . '&username=' . $username . '&userType=' . $userType;

    // Simple validation for empty fields
    if (empty($email) || empty($username) || empty($password) || empty($userType)) {
        $em = "All fields are required!";
        header("Location: ../registration.php?error=$em&$user_input");
        exit;
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $em = "Passwords do not match!";
        header("Location: ../registration.php?error=$em&$user_input");
        exit;
    }

    try {
        // Check if the email is already registered
        $check_email_sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->execute([$email]);
        $existingEmail = $stmt->fetch();

        if ($existingEmail) {
            $em = "This email is already registered!";
            header("Location: ../registration.php?error=$em&$user_input");
            exit;
        }

        // Hash the password (recommended for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user data into the admin table
        $sql = "INSERT INTO admin (email, password, username, userType) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $res = $stmt->execute([$email, $hashed_password, $username, $userType]);
        
        if ($res) {
            $sm = "Registration successful!";
            header("Location: ../registration.php?success=$sm");
            exit;
        } else {
            throw new Exception("Registration failed");
        }
    } catch (Exception $e) {
        $em = $e->getMessage();
        header("Location: ../registration.php?error=$em&$user_input");
        exit;
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: ../registration.php");
    exit;
}