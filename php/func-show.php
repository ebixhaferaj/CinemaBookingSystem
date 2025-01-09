<?php

// Get all shows function
function get_all_shows($con){
    $sql = "SELECT * FROM movie_show ORDER BY show_date DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $shows = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $shows = 0;
    }

    return $shows;
}

// Get show by ID function
function get_show($con, $id){
    $sql = "SELECT * FROM movie_show WHERE show_id=?";
    $stmt = $con->prepare($sql);
    
    # Check if prepare() succeeded
    if (!$stmt) {
        die('Error in SQL query: ' . $con->error);
    }
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $show = $result->fetch_assoc(); 
    } else {
        $show = 0;
    }

    return $show;
}

function get_show_for_movie($con, $id){
    $sql = "SELECT * FROM movie_show WHERE movie_id_fk=?";
    $stmt = $con->prepare($sql);
    
    # Check if prepare() succeeded
    if (!$stmt) {
        die('Error in SQL query: ' . $con->error);
    }
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $shows = [];

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $shows[] = $row;
        }
    } 

    return $shows;
}
