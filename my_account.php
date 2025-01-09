<?php
session_start();
include "db-connection.php";
include "php/func-booking.php";
include "php/get-user-data.php";
include "php/update-user-data.php";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_email'])){
    $new_email = $_POST['new_email'];
    $new_email = update_user_email($conn, $new_email, $_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $new_password_confirm = $_POST['new_password_confirm'];
    $user_id = $_SESSION['user_id']; // Get the user_id from the session

    $result = update_user_password($conn, $current_password, $new_password, $new_password_confirm, $user_id);
}

$user = get_user_data($conn, $_SESSION['user_id']);
$bookings = get_all_bookings($conn, $_SESSION['user_id']);

if (isset($_SESSION['email']) && isset($_SESSION['user_id']) && $_SESSION['role'] == 'user') { 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Kino | Your Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/user_home.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column" style="background-color: rgb(32,32,32);">
    <div class="container-fluid">
        <div class="row">
            <!-- Nav Bar -->
            <nav class="navbar navbar-expand-lg" style="background-color: rgb(56,56,56);">
                <div class="container-fluid px-4">
                    <a class="navbar-brand" href="user_home.php"><img src="img/logo.png" style="width: 40px"> KINO</a>
                    <!-- Toggle Button for Small Devices -->
                    <button class="navbar-toggler navbar-toggler-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="user_home.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="top_movies.php">Top Movies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="now_showcasing.php">Now Showcasing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="coming_soon.php">Coming Soon</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="my_account.php"><i class="bi bi-person-fill-gear">My Account</i> </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php"><i class="bi bi-door-closed-fill">Log Out</i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Main -->
            <main class="col-xxl-10 col-lg-10 col-md-11 col-sm-10 px-0 mx-auto py-5" style="color: white;">
                <div class="container" style="background-color: rgb(56,56,56);">
                    <div class="row justify-content-center">
                        <!-- General Info  -->
                        <div class="col-md-5 mb-4 my-5">
                            <h5 class="card-title" style="color:red;"><?=$user['name']?></h5>
                            <hr>
                        <!-- Update Email -->
                        <form class="my-5" action="my_account.php" method="POST">
                            <div class="mb-3">
                                <p class="card-text">Current Email: <?=$user['email']?></p>
                                <input type="email" class="form-control" style="width: 300px" id="new_email" name="new_email" required>
                                <input type="hidden" name="user_id" value="<?=$user['user_id']?>">
                            </div>
                            <button class="btn btn-outline-light my-3" type="submit">Change Email</button>
                        </form>
                        </div>

                        <!-- Change Password -->
                        <div class="col-md-5 mb-4 my-5">
                            <form action="my_account.php" method="POST">
                                <div class="mb-3">
                                    <label type="password" for="currentPassword"  class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" id="currentPassword">
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label" >New Password</label>
                                    <input type="password" class="form-control" name="new_password" id="newPassword">
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="new_password_confirm" id="confirmPassword">
                                </div>
                                <button type="submit" class="btn btn-outline-light">Change Password</button>
                            </form>
                        </div>
                        <!-- Booking -->
                        <div class="row">
                            <div class="col-12">
                                <p class="card-text">This section will display your bookings.</p>
                                <p class="card-text" style="font-size:10px; color:grey;">Bookings will be deleted, as soon as the show ends.</p>
                                <hr>
                                <?php if($bookings == 0) {  ?>
                                    <div class="alert alert-warning text-center p-5 mt-3" role="alert">
                                        <img src="img/empty.png" width="100">
                                        <br>There are no bookings
                                    </div>
                                <?php } else {?>
                                <table class="table table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Movie</th>
                                            <th>Show Date</th>
                                            <th>Seats</th>
                                            <th>Total Price</th>
                                            <th>Reservation PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking) { ?>
                                            <tr>
                                                <td><?=$booking['booking_id']?></td>
                                                <td><?=$booking['movie_title']?></td>
                                                <td><?=$booking['show_date']?></td>
                                                <td><?=$booking['nr_seats']?></td>
                                                <td><?=$booking['total_price']?></td>
                                                <form method="post"  action="php/generatePDF.php">
                                                     <!-- Hidden input to send the booking ID -->
                                                    <input type="hidden" name="booking_id" value="<?=$booking['booking_id']?>">
                                                    <td><button class="btn btn-dark" type="submit">Open PDF</button></td>
                                                </form>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php } else {
    header("Location: index.php");
} ?>
