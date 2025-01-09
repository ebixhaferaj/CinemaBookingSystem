<?php

session_start();
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
include "../db-connection.php";
include "func-booking.php";
include "get-user-data.php";
include "func-movie.php";


require('../fpdf/fpdf.php');

$booking_id = $_POST['booking_id'];

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$user = get_user_data($conn, $_SESSION['user_id']);

// Check if user data is returned
if (!$user) {
    die("User not found");
}


// Fetch bookings
$bookings = get_booking($conn, $booking_id);


    // Create PDF
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // PDF Header
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(71, 10, '', 0, 0);
    $pdf->Cell(59, 5, 'Reservation', 0, 5);
    $pdf->Cell(59, 10, '', 0, 1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(71, 5, 'KINO Cinema', 0, 0);
    $pdf->Cell(59, 5, '', 0, 0);
    $pdf->Cell(59, 5, 'Details', 0, 1);

    $pdf->SetFont('Arial', '', 10);


    $pdf->Cell(130, 5, 'St. Murat Toptani', 0, 0);
    $pdf->Cell(25, 5, 'Costumer ID:', 0, 0);
    $pdf->Cell(34, 5, $user['user_id'], 0, 1);

    $pdf->Cell(130, 5, 'Tirana', 0, 0);
    $pdf->Cell(25, 5, '', 0, 0);
    $pdf->Cell(34, 5, '', 0, 1);

    $pdf->Ln(20);
    $pdf->Cell(20, 10, 'Booking ID', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Movie Title', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Show Date', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Number of Seats', 1, 0, 'C');
    $pdf->Cell(40, 10,'Total Price', 1, 1, 'C');



    // Add booking data to PDF
    $pdf->SetFont('Arial', '', 12);
    if (!empty($bookings)) {
        foreach ($bookings as $row) {
            $pdf->Cell(20, 10, $row['booking_id'], 1, 0, 'C');
            $pdf->Cell(40, 10, $row['movie_title'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['show_date'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['nr_seats'], 1, 0, 'C');
            $pdf->Cell(40, 10, '$' . number_format($row['total_price'],2), 1, 1, 'C');
        }
    } else {
        $pdf->Cell(0, 10, 'No bookings found', 0, 1);
    }

    // Output the PDF
    $pdf->Output();
