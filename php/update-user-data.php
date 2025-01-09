<?php
include "db-connection.php";
// Function to update the user's email
function update_user_email($conn, $new_email, $user_id) {
    // Ensure the new email is valid and not empty
    if (!empty($new_email) && filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        // Prepare and execute the update query
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_email, $user_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>E-Mail changed successfully!</div>";
            return $new_email; // Return the new email if successful
        } else {
            echo "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
            return false;
        }
    } else {
        echo "<div class='alert alert-success'>E-mail invalid. Please try again.</div>";
        return false; 
    }
}


function update_user_password($conn, $current_password, $new_password, $new_password_confirm, $user_id) {
    // Check if the new passwords match
    if ($new_password != $new_password_confirm) {
        echo "<div class='alert alert-danger'>Passwords do not match. Please try again.</div>";
        return false;
    }
    
    // Ensure the new password is not empty
    if (empty($new_password)) {
        echo "<div class='alert alert-danger'>New Password is empty. Please try again.</div>";
        return false;
    }
    
    $hashed_password_from_db = null;

    // Fetch the user's current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password_from_db);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (!password_verify($current_password, $hashed_password_from_db)) {
        echo "<div class='alert alert-danger'>Current password is incorrect. Please try again.</div>";
        return false;
    }

    // Hash the new password before storing it
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to update the password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_hashed_password, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Password changed successfully!</div>";
        return true;
    } else {
        echo "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
        return false;
    }
}
