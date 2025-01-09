<?php



if (isset($_POST['submit_carousel_img'])) {
    if (isset($_FILES['carousel_image']) && $_FILES['carousel_image']['error'] === 0) {
        // Define upload directory
        $uploadDir = 'carousel/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Get image details
        $imageTmpName = $_FILES['carousel_image']['tmp_name'];
        $imageName = basename($_FILES['carousel_image']['name']);
        $imagePath = $uploadDir . $imageName;

        echo '<div style="display: flex; justify-content: center; align-items: center; flex-direction: column; min-height: 100vh;">';

        echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "<p><b>Trying to upload:</b> <i>" . $imagePath . "</i></p>";
        echo '</div>';

        // Move the uploaded file to the carousel folder
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            echo '<div style="padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-family: Arial, sans-serif; margin-bottom: 20px;">';
            echo "File uploaded successfully.";
            echo '</div>';
            
            echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
            echo "<p><b>Please go back to continue.</b></p>";
            echo '</div>';
        } else {
            echo '<div style="padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; font-family: Arial, sans-serif; margin-bottom: 20px;">';
            echo "Failed to upload the file.";
            echo '</div>';
            
            echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
            echo "<p><b>Please go back to try again.</b></p>";
            echo '</div>';
        }
    } else {
        echo '<div style="padding: 10px; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 5px; font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "File does not exist.";
        echo '</div>';
        
        echo '<div style="font-family: Arial, sans-serif; margin-bottom: 20px;">';
        echo "<p><b>Please go back to try again.</b></p>";
        echo '</div>';
    }
}
?>