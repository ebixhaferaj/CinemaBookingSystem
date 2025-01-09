<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    // Redirect if user is not logged in
    header("Location: index.php");
    exit;
}

// Include database connection
include "../db-connection.php";

// If all required fields are set
if (
    isset($_POST["movie_title"])        && 
    isset($_POST["movie_description"])  &&
    isset($_POST["duration"])           && 
    isset($_POST["release_date"])       &&
    isset($_POST["genre"])              && 
    isset($_POST["trailer"])            && 
    isset($_POST["movie_cast"])         && 
    isset($_POST["director"])           && 
    isset($_POST["id"])
) {
    // Extract data from POST and store them in var
    $movie_id = $_POST["id"];
    $movie_title = $_POST["movie_title"];
    $movie_description = $_POST["movie_description"];
    $duration = $_POST["duration"];
    // Format as YYYY-MM-DD
    $release_date = date("Y-m-d", strtotime($_POST["release_date"]));
    $genre = $_POST["genre"];
    $current_cover = isset($_POST['current_cover']) ? $_POST['current_cover'] : '';
    $trailer = $_POST["trailer"];
    $movie_cast = $_POST["movie_cast"];
    $director = $_POST["director"];

    // Extract the video ID from the YouTube URL for embedding
    $videoId = parse_url($trailer, PHP_URL_QUERY);
    parse_str($videoId, $params);
    $embedUrl = "https://www.youtube.com/embed/" . $params['v'];

    // File upload handling
    $file_name = $_FILES["cover"]["name"];
    $file_tmp = $_FILES["cover"]["tmp_name"];
    $file_type = $_FILES["cover"]["type"];
    $file_size = $_FILES["cover"]["size"];

    // Specify upload directory
    $upload_dir = "../img/";

    // Simple form validation
    if (empty($movie_title)) {
        $error_message = "The movie title is required.";
        header("Location: ../edit-movie.php?error=" . urlencode($error_message) . "&id=" . $movie_id);
        exit;
    } else {
        // Check if a new file is uploaded
        if (!empty($file_name)) {
            // File uploaded successfully, proceed with database update including cover
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $cover = $file_name;

                // Prepare and execute the SQL statement with cover update
                $sql = "UPDATE movie 
                        SET movie_title=?, description=?, duration=?, release_date=?, genre=?, cover=?, trailer=?, movie_cast=?, director=?
                        WHERE movie_id=?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ssissssssi", $movie_title, $movie_description, $duration, $release_date, $genre, $cover, $embedUrl, $movie_cast, $director, $movie_id);
                    $stmt->execute();

                    // Check if the update was successful
                    if ($stmt->affected_rows > 0) {
                        $success_message = "Movie updated successfully.";
                        header("Location: ../edit-movie.php?success=" . urlencode($success_message));
                        exit;
                    } else {
                        // Update failed
                        $error_message = "Failed to update movie. Please try again.";
                        header("Location: ../edit-movie.php?error=" . urlencode($error_message) . "&id=" . $movie_id);
                        exit;
                    }
                } else {
                    // Statement preparation failed
                    $error_message = "Failed to prepare the SQL statement: " . $conn->error;
                    header("Location: ../edit-movie.php?error=" . urlencode($error_message) . "&id=" . $movie_id);
                    exit;
                }
            } else {
                // File upload failed
                $error_message = "Error uploading file. Please try again.";
                header("Location: ../edit-movie.php?error=" . urlencode($error_message));
                exit;
            }
        } else {
            // No new file uploaded, use current cover if it exists
            $cover = $current_cover;

            // Prepare and execute the SQL statement without cover update
            $sql = "UPDATE movie 
                    SET movie_title=?, description=?, duration=?, release_date=?, genre=?, trailer=?, movie_cast=?, director=?
                    WHERE movie_id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssisssssi", $movie_title, $movie_description, $duration, $release_date, $genre, $embedUrl, $movie_cast, $director, $movie_id);
                $stmt->execute();

                // Check if the update was successful
                if ($stmt->affected_rows > 0) {
                    $success_message = "Movie updated successfully.";
                    header("Location: ../edit-movie.php?success=" . urlencode($success_message));
                    exit;
                } else {
                    // Update failed
                    $error_message = "Failed to update movie. Please try again.";
                    header("Location: ../edit-movie.php?error=" . urlencode($error_message) . "&id=" . $movie_id);
                    exit;
                }
            } else {
                // Statement preparation failed
                $error_message = "Failed to prepare the SQL statement: " . $conn->error;
                header("Location: ../edit-movie.php?error=" . urlencode($error_message) . "&id=" . $movie_id);
                exit;
            }
        }
    }
} else {
    // Redirect back with error message if any field is missing
    header("Location: ../edit-movie.php?error=All fields are required.");
    exit;
}
?>
