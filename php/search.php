<?php

function searchMovies($search, $conn) {
    $movies = [];
    
    if (!empty($search)) {
        // Escape the search input to avoid SQL injection
        $search = mysqli_real_escape_string($conn, $search);

        // Prepare the SQL query
        $query = "SELECT * FROM movie 
                  WHERE movie_title LIKE '%$search%' 
                  OR description LIKE '%$search%' 
                  OR genre LIKE '%$search%'";

        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
            // Loop through the result and store the data in the $movies array
            while ($row = mysqli_fetch_assoc($result)) {
                $movies[] = $row;
            }
        } else {
            return "Error: " . mysqli_error($conn);
        }
    } else {
        return "No search term provided.";
    }

    // Return the array of movies
    return $movies;
}


