<?php
session_start();
include "../db-connection.php";

if (isset($_POST['email'], $_POST['password'], $_POST['role'], $_POST['name'])) {

    // Function to sanitize input data
    function test_input($data) {
        return htmlspecialchars(trim($data));
    }

    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    // Validating input fields
    if (empty($email)) {
        header("Location: ../register.php?error=Email is Required");
        exit();
    } elseif (empty($password)) {
        header("Location: ../register.php?error=Password is Required");
        exit();
    } elseif (empty($name)) {
        header("Location: ../register.php?error=Full name is Required");
        exit();
    } else {
        // Hashing the password 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user data into the database using a prepared statement
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                // Get the last inserted user ID
                $user_id = mysqli_insert_id($conn);
            
                // Store user info in the session
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $user_id; // Set user_id in session
            
                // Redirect based on role
                if ($role == 'admin') {
                    header("Location: ../admin_home.php");
                } else {
                    header("Location: ../user_home.php");
                }
                exit();
            }
            
        } else {
            $error_msg = mysqli_error($conn);  // Capture preparation error
            header("Location: ../register.php?error=Failed to prepare SQL statement: " . urlencode($error_msg));
            exit();
        }
        
    }

} else {
    header("Location: ../index.php");
    exit();
}
