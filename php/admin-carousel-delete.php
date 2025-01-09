<?php
session_start();

$imageName = $_POST['delete_image'];
$imgPath = "C:/xampp/htdocs/CinemaBookingSystem/" . $imageName;

// Centering the content
echo '<div style="display: flex; justify-content: center; align-items: center; flex-direction: column; min-height: 100vh;">';

echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
echo "<p><b>Trying to delete:</b> <i>" . $imgPath . "</i></p>";
echo '</div>';

if (file_exists($imgPath)) {
    if (unlink($imgPath)) {
        // Success message
        echo '<div style="padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "File deleted successfully.";
        echo '</div>';
        
        echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "<p><b>Please go back to continue.</b></p>";
        echo '</div>';
    } else {
        // Failure message
        echo '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "Failed to delete the file.";
        echo '</div>';
        
        echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "<p><b>Please go back to try again.</b></p>";
        echo '</div>';
    }
} else {
    // File not found message
    echo '<div style="padding: 10px; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 5px; font-family: Arial, sans-serif; margin-bottom: 20px;">';
    echo "File does not exist.";
    echo '</div>';
    
    echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
    echo "<p><b>Please go back to try again.</b></p>";
    echo '</div>';
}

echo '</div>'; // Close the centered div
