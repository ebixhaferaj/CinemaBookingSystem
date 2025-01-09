<?php

# Get all movies function
function get_all_halls($con){
    $sql = "SELECT * FROM cinema_hall ORDER BY cinema_hall_id ASC";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $halls = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $halls = 0;
    }

    return $halls;
}

# Get halls by ID
function get_hall($con, $id){
    $sql = "SELECT * FROM cinema_hall WHERE cinema_hall_id=?";
    $stmt = $con->prepare($sql);
    
    # Check if prepare() succeeded
    if (!$stmt) {
        die('Error in SQL query: ' . $con->error);
    }
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $hall = $result->fetch_assoc(); 
    } else {
        $hall = 0;
    }

    return $hall;
}
