<?php
session_start();

include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM customers WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        // Email already exists, handle accordingly (redirect, show error, etc.)
        header("Location: signup.php?error=email_exists");
        exit();
    } else {
        // Email is unique, proceed with signup
        $signupQuery = "INSERT INTO customers (first_name, last_name, email, password, created_at, updated_at) VALUES ('$first_name', '$last_name', '$email', '$password', NOW(), NOW())";

        if ($conn->query($signupQuery) === TRUE) {
            // Signup successful, redirect to login page or any other page
            header("Location: login.php");
            exit();
        } else {
            // Error during signup, handle accordingly (redirect, show error, etc.)
            header("Location: signup.php?error=signup_failed");
            exit();
        }
    }
} else {
    // Invalid request method, handle accordingly (redirect, show error, etc.)
    header("Location: signup.php?error=invalid_request");
    exit();
}
?>
