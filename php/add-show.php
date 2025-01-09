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
    isset($_POST["show_date"]) && 
    isset($_POST["show_start"]) &&
    isset($_POST["show_end"]) && 
    isset($_POST["cinema_hall"]) &&
    isset($_POST["movie"])
) {
    // Extract data from POST
    $show_date = date("Y-m-d", strtotime($_POST["show_date"]));
    $show_start = $_POST["show_start"];
    $show_end = $_POST["show_end"];
    $price = $_POST["price"];
    $cinema_hall_id_fk = $_POST["cinema_hall"];
    $movie_id_fk = $_POST["movie"];

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO movie_show (show_date, show_start, show_end, price, cinema_hall_id_fk, movie_id_fk) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsi", $show_date, $show_start, $show_end, $price, $cinema_hall_id_fk, $movie_id_fk);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        $success_message = "Show added successfully.";
        header("Location: ../add-show.php?success=$success_message");
        exit;
    } else {
        // Insertion failed
        $error_message = "Failed to add show. Please try again.";
        header("Location: ../add-show.php?error=$error_message");
        exit;
    }
} else {
    // Redirect back with error message if any field is missing
    header("Location: ../add-show.php?error=All fields are required.");
    exit;
}
