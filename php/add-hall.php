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
    isset($_POST["seats"]) 
) {
    // Extract data from POST
    $cinema_hall_name = $_POST["name"];
    $total_seats = $_POST["seats"];


    // Prepare and execute the SQL statement
    $sql = "INSERT INTO cinema_hall (cinema_hall_name, total_seats) 
    VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $cinema_hall_name, $total_seats);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        $success_message = "Cinema Hall added successfully.";
        header("Location: ../add-hall.php?success=$success_message");
        exit;
    } else {
        // Insertion failed
        $error_message = "Failed to add cinema hall. Please try again.";
        header("Location: ../add-hall.php?error=$error_message");
        exit;
    }
} else {
// Redirect back with error message if any field is missing
header("Location: ../add-hall.php?error=All fields are required.");
exit;
} 
