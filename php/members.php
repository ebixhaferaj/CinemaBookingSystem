<?php

if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    $sql = "SELECT * FROM users ORDER BY user_id ASC";
    $res = mysqli_query($conn, $sql);
} else {
    header("Location: index.php");
}
