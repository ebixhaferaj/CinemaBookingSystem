<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Redirect if user is not logged in
    header("Location: index.php");
    exit;
}

// Include database connection
include "../db-connection.php";

// Check if all required fields are set
if (
    isset($_POST["name"]) && 
    isset($_POST["seats"]) &&
    isset($_POST["id"])
) {
    // Extract data from POST
    $cinema_hall_name = $_POST["name"];
    $total_seats = $_POST["seats"];
    $cinema_hall_id = $_POST["id"];

    // Simple form validation
    if (empty($cinema_hall_name)) {
        $em = "The hall name is required";
        header("Location: ../edit-hall.php?error=$em&id=$cinema_hall_id");
        exit;
    } else {
        // Update DB

        // Prepare and execute the SQL statement
        $sql = "UPDATE cinema_hall 
                SET cinema_hall_name=?, total_seats=?
                WHERE cinema_hall_id=?";
        $stmt = $conn->prepare($sql);

        // Check if the statement was prepared successfully
        if ($stmt) {
            $stmt->bind_param("sii", $cinema_hall_name, $total_seats, $cinema_hall_id);
            $stmt->execute();

            // Check if the update was successful
            if ($stmt->affected_rows > 0) {
                $success_message = "Cinema Hall updated successfully.";
                header("Location: ../edit-hall.php?success=$success_message");
                exit;
            } else {
                // Update failed
                $error_message = "Failed to update cinema hall. Please try again.";
                header("Location: ../edit-hall.php?error=$error_message&id=$cinema_hall_id");
                exit;
            }
        } else {
            // Statement preparation failed
            $error_message = "Failed to prepare the SQL statement: " . $conn->error;
            header("Location: ../edit-hall.php?error=$error_message&id=$cinema_hall_id");
            exit;
        }
    }
} else {
    // Redirect back with error message if any field is missing
    header("Location: ../edit-hall.php?error=All fields are required.");
    exit;
}
?>
