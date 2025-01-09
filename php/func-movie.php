<?php

// Get all movies function
function get_all_movies($con){
    $sql = "SELECT * FROM movie ORDER BY movie_id DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $movies = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $movies = 0;
    }

    return $movies;
}
// Get movie by ID
function get_movie($con, $id){
    $sql = "SELECT * FROM movie WHERE movie_id=?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die('Error in SQL query: ' . $con->error);
    }
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $movie = $result->fetch_assoc(); 
    } else {
        $movie = 0;
    }

    return $movie;
}
