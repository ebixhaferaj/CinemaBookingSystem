<?php 

if (isset($_SESSION['email']) && isset($_SESSION['user_id']) && $_SESSION['role'] == 'user') { 
    // Initialize variables
    $show_id = $_GET['show_id'] ?? null; 
    $movie_id = $_GET['movie_id'] ?? null;
    $selected_seats = [];
    $number_of_seats = 0;
    $seat_price = 0;
    $total_price = 0;

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_seats'])) {
        $user_id = $_SESSION['user_id'];
        $selected_seats = $_POST['seatcheckbox'];
        $number_of_seats = count($selected_seats);

        // Fetch the seat price
        $stmt = $conn->prepare("SELECT price FROM movie_show WHERE show_id = ?");
        $stmt->bind_param("i", $show_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $seat_price = $row['price'];

        } else {
            echo "<div class='alert alert-danger'>No price found for the selected show.</div>";
            exit();
        }

        // Calculate total price
        $total_price = $number_of_seats * $seat_price;

        // Store values in session
        $_SESSION['selected_seats'] = $selected_seats;
        $_SESSION['number_of_seats'] = $number_of_seats;
        $_SESSION['seat_price'] = $seat_price;
        $_SESSION['total_price'] = $total_price;

    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_seats'])) {
        // Retrieve the selected seats from session
        $user_id = $_SESSION['user_id'];
        $total_price = $_SESSION['total_price'];
        $number_of_seats = $_SESSION['number_of_seats'];

        if (isset($_SESSION['selected_seats'])) {
            $selected_seats = $_SESSION['selected_seats'];
        } else {
            echo "<div class='alert alert-warning'>No seats selected for reservation.</div>";
            exit();
        }
    
        // Check availability of seats and reserve them
        $available_seats = [];
        foreach ($selected_seats as $seat) {
            $stmt = $conn->prepare("SELECT * FROM show_seat WHERE cinema_seat_id_fk = ? AND show_id_fk = ?");
            $stmt->bind_param("si", $seat, $show_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows == 0) {
                $available_seats[] = $seat; // Seat is available
            } else {
                echo "<div class='alert alert-warning'>Seat $seat is already reserved by another user.</div>";
            }
        }
    
        // Insert booking if there are available seats
        if (!empty($available_seats)) {
            $stmt = $conn->prepare("INSERT INTO booking (user_id_fk, show_id_fk, total_price, nr_seats, booking_date) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iidi", $user_id, $show_id, $total_price, $number_of_seats);
    
            if ($stmt->execute()) {
                $booking_id = $stmt->insert_id;
                foreach ($available_seats as $seat) {
                    $stmt = $conn->prepare("INSERT INTO show_seat (cinema_seat_id_fk, show_id_fk, status, booking_id_fk) VALUES (?, ?, 1, ?)");
                    $stmt->bind_param("sii", $seat, $show_id, $booking_id);
                    $stmt->execute();
                }
                header("Location: seat-selection.php?show_id=$show_id&movie_id=$movie_id&success=Seats reserved successfully!");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Failed to create booking. Error: " . htmlspecialchars($stmt->error) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>No seats were available for booking.</div>";
        }
    }
    
}