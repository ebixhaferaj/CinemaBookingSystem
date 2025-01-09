<?php

// Get all BOOKINGS function
function get_all_bookings($conn, $user_id) {
    $sql = "
    SELECT b.booking_id, m.movie_title, s.show_date, b.nr_seats, b.total_price
    FROM booking AS b
    JOIN movie_show AS s ON b.show_id_fk = s.show_id
    JOIN movie AS m ON s.movie_id_fk = m.movie_id
    WHERE b.user_id_fk = ?";
    
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if (!$stmt) {
        die('Error preparing SQL query: ' . $conn->error);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bookings = $result->fetch_all(MYSQLI_ASSOC); // Use fetch_all to return all rows as an array
    } else {
        $bookings = []; // Return an empty array if no results
    }

    return $bookings;
}

// Get booking by ID
function get_booking($con, $id) {
    $sql = "SELECT b.booking_id, m.movie_title, s.show_date, b.nr_seats, b.total_price
    FROM booking AS b
    JOIN movie_show AS s ON b.show_id_fk = s.show_id
    JOIN movie AS m ON s.movie_id_fk = m.movie_id
    WHERE b.booking_id = ?";
    $stmt = $con->prepare($sql);
    
    # Check if prepare() succeeded
    if (!$stmt) {
        die('Error in SQL query: ' . $con->error);
    }
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize an empty array for bookings
    $bookings = array();

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Fetch all bookings and store them in the $bookings array
        while ($booking = $result->fetch_assoc()) {
            $bookings[] = $booking;  // Add each booking to the array
        }
    } else {
        $bookings = 0;  // If no results, return 0
    }

    return $bookings;
}