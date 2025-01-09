<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Redirect if user is not logged in
    header("Location: ../index.php");
    exit;
}

// Include database connection
include "../db-connection.php";

// Check if cinema hall id is submitted
if (isset($_GET["id"])) {

    // Extract data from GET request and store it in a variable
    $id = $_GET["id"];

    // Simple form validation
    if (empty($id)) {
        $em = "Error Occurred!";
        header("Location: ../admin_home.php?error=$em");
        exit;
    } else {
        // Get cinema hall from DB
        $sql2 = "SELECT * FROM cinema_hall WHERE cinema_hall_id=?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            // DELETE the cinema hall from DB
            $sql = "DELETE FROM cinema_hall WHERE cinema_hall_id=?";
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
                    $error_message = "Failed to remove hall. Please try again.";
                    header("Location: ../admin_home.php?error=$error_message");
                    echo("error");
                    exit;
                }
            } else {
                $error_message = "Failed to prepare the SQL statement: " . $conn->error;
                header("Location: ../admin_home.php?error=$error_message");
                exit;
            }
        } else {
            $em = "Hall not found!";
            header("Location: ../admin_home.php?error=$em");
            exit;
        }
    }
} else {
    header("Location: ../admin_home.php?error=All fields are required.");
    exit;
}
?>
