<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["email"]) {
    // Include database connection
    include "../db-connection.php";

    // Check if all required fields are set
    if (isset($_POST["movie_title"]) && 
        isset($_POST["movie_description"]) &&
        isset($_POST["duration"]) && 
        isset($_POST["release_date"]) &&
        isset($_POST["genre"]) && 
        isset($_FILES["cover"]) &&
        isset($_POST["trailer"]) &&    
        isset($_POST["movie_cast"]) &&
        isset($_POST["director"])){

            
        // Extract data from POST
        $title = $_POST["movie_title"];
        $description = $_POST["movie_description"];
        $duration = $_POST["duration"];
        // Format release date as YYYY-MM-DD
        $release_date = date("Y-m-d", strtotime($_POST["release_date"]));
        $genre = $_POST["genre"];
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

        // Check if file is uploaded successfully
        if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            // File uploaded successfully, proceed with database insertion
            $cover = $file_name;

            // Insert data into database
            $sql = "INSERT INTO movie (movie_title, description, duration, release_date, genre, cover, trailer, movie_cast, director) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssss", $title, $description, $duration, $release_date, $genre, $cover, $embedUrl, $movie_cast, $director);
                               
            // Execute the statement
            $stmt->execute();

            // Check if insertion was successful
            if ($stmt->affected_rows > 0) {
                // Insertion successful
                $success_message = "Movie added successfully.";
                header("Location: ../add-movie.php?success=$success_message");
                exit;
            } else {
                // Insertion failed
                $error_message = "Failed to add movie. Please try again.";
                header("Location: ../add-movie.php?error=$error_message");
                exit;
            }
        } else {
            // File upload failed
            $error_message = "Error uploading file. Please try again.";
            header("Location: ../add-movie.php?error=$error_message");
            exit;
        }
    } else {
        // If fields not set -> error message
        $error_message = "All fields are required.";
        header("Location: ../add-movie.php?error=$error_message");
        exit;
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: ../index.php");
    exit;
}
?>
