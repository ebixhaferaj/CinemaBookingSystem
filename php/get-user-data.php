<?php 

function get_user_data($conn, $user_id){
    $sql = 
    "SELECT * FROM users WHERE user_id = ?";
   
    $stmt = $conn->prepare($sql);
    
    if(!$stmt){
            die('Error preparing SQL query: ' . $conn->error);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        return $result->fetch_assoc(); // Return a single row as an associative array
    } else {
        return null; 
    }
    
}

function get_all_users($conn) {
    $sql = "SELECT * FROM users WHERE role = 'user'"; 

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
    } else {
        return null;
    }
}


function get_all_admins($conn) {
    $sql = "SELECT * FROM users WHERE role = 'admin'"; 

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
    } else {
        return null;
    }
}
