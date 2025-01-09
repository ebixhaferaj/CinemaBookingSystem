<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Redirect if user is not logged in
    header("Location: ../index.php");
    exit;
}

// Include database connection
include "../db-connection.php";

// Check if all required fields are set
if (
    isset($_POST["show_date"]) && 
    isset($_POST["show_start"]) && 
    isset($_POST["show_end"]) && 
    isset($_POST["price"]) && 
    isset($_POST["cinema_hall"]) &&
    isset($_POST["movie"]) &&
    isset($_POST["id"])
) {
    // Extract data from POST
    $show_date = $_POST["show_date"];
    $show_start = $_POST["show_start"];
    $show_end = $_POST["show_end"];
    $price = $_POST["price"];
    $cinema_hall_id_fk = $_POST["cinema_hall"];
    $movie_id_fk = $_POST["movie"];
    $show_id = $_POST["id"];

    // Simple form validation
    if (empty($show_date)) {
        $em = "Show Date is required";
        header("Location: ../edit-show.php?error=$em&id=$show_id");
        exit;
    } else {
        // Update DB

        // Prepare and execute the SQL statement
        $sql = "UPDATE movie_show 
                SET show_date=?, show_start=?, show_end=?, price=?, cinema_hall_id_fk=?, movie_id_fk=?
                WHERE show_id=?";
        $stmt = $conn->prepare($sql);

        // Check if the statement was prepared successfully
        if ($stmt) {
            $stmt->bind_param("sssdsii", $show_date, $show_start, $show_end, $price, $cinema_hall_id_fk, $movie_id_fk, $show_id);
            $stmt->execute();

            // Check if the update was successful
            if ($stmt->affected_rows > 0) {
                $success_message = "Show updated successfully.";
                header("Location: ../edit-show.php?success=$success_message&id=$show_id");
                exit;
            } else {
                // Update failed
                $error_message = "Failed to update show. Please try again.";
                header("Location: ../edit-show.php?error=$error_message&id=$show_id");
                exit;
            }
        } else {
            // Statement preparation failed
            $error_message = "Failed to prepare the SQL statement: " . $conn->error;
            header("Location: ../edit-show.php?error=$error_message&id=$show_id");
            exit;
        }
    }
} else {
    // Redirect back with error message if any field is missing
    header("Location: ../edit-show.php?error=All fields are required.");
    exit;
}
?>