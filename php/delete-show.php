<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Redirect if user is not logged in
    header("Location: ../index.php");
    exit;
}

// Include database connection
include "../db-connection.php";

// Check if show id is submitted
if (isset($_GET["id"])) {

    // Extract data from GET request and store it in a variable
    $id = $_GET["id"];

    // Simple form validation
    if (empty($id)) {
        $em = "Error Occurred!";
        header("Location: ../admin_home.php?error=$em");
        exit;
    } else {
        // Get show from DB
        $sql2 = "SELECT * FROM movie_show WHERE show_id=?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            // DELETE the movie from DB
            $sql = "DELETE FROM movie_show WHERE show_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt) {
                $stmt->execute();

                // Check if the delete was successful
                if ($stmt->affected_rows > 0) {
                    $success_message = "Successfully removed.";
                    header("Location: ../admin_home.php?success=$success_message");
                    exit;
                } else {
                    $error_message = "Failed to remove show. Please try again.";
                    header("Location: ../admin_home.php?error=$error_message");
                    exit;
                }
            } else {
                $error_message = "Failed to prepare the SQL statement: " . $conn->error;
                header("Location: ../admin_home.php?error=$error_message");
                exit;
            }
        } else {
            $em = "ShowS not found!";
            header("Location: ../admin_home.php?error=$em");
            exit;
        }
    }
} else {
    header("Location: ../admin_home.php?error=All fields are required.");
    exit;
}
?>
