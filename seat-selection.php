<?php
session_start();

if (isset($_SESSION['email']) && isset($_SESSION['user_id']) && $_SESSION['role'] == 'user') { 
    include "db-connection.php";
    include "php/seat-selection.php";
    include "php/func-movie.php";
    include "php/func-show.php";

    $movie = get_movie($conn, $movie_id);
    $movie_show = get_show($conn, $show_id);

    $show_id = $_GET['show_id'];
    $movie_id = $_GET['movie_id'];


    $selected_seats = [];
    $number_of_seats = 0;
    $seat_price = 0;
    $total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Kino | Your Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/seat-selection.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column" style="background-color: rgb(32,32,32);">
    <div class="container-fluid">
        <div class="row">
            <!-- NAV BAR -->
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
                                <a class="nav-link active" aria-current="page" href="user_home.php">Home</a>
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
                                <a class="nav-link" href="my_account.php"><i class="bi bi-person-fill-gear">My Account</i> </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php"><i class="bi bi-door-closed-fill">Log Out</i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- MAIN CONTENT -->
            <main class="col-xxl-10 col-lg-10 col-md-11 col-sm-10 px-0 mx-auto py-5" style="color: white;">
                <div class="container" style="background-color: rgb(56,56,56);">
                    <div class="row">
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_GET['error']); ?></div>
                        <?php } ?>
                        <?php if (isset($_GET['success'])) { ?>
                            <div class="alert alert-success" role="alert"><?= htmlspecialchars($_GET['success']); ?></div>
                        <?php } ?> 
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="cinema">
                                    <form method="post">
                                        <div class="screen"><h4>Screen</h4></div>
                                        <div class="seating">
                                            <?php
                                            for ($row = 1; $row <= 7; $row++) {
                                                $row_letter = chr(64 + $row);
                                                echo "<div class='row'>";
                                                echo "<div class='row-label'><strong>$row_letter</strong></div>";
                                                for ($column = 1; $column <= 12; $column++) {
                                                    $seat_id = "$row_letter$column";
                                                    
                                                    // Check if seat is reserved
                                                    $stmt = $conn->prepare("SELECT * FROM show_seat WHERE cinema_seat_id_fk = ? AND show_id_fk = ? AND status = 1");
                                                    $stmt->bind_param("si", $seat_id, $show_id); 
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    $is_reserved = ($result->num_rows > 0);
                                                    
                                                    echo "<div class='seat-container'>";
                                                    if ($is_reserved) {
                                                        echo "<input type='checkbox' name='seatcheckbox[]' value='$seat_id' id='$seat_id' class='seat' disabled/>";
                                                        echo "<label for='$seat_id' class='seat-label reserved'></label>";
                                                    } else {
                                                        echo "<input type='checkbox' name='seatcheckbox[]' value='$seat_id' id='$seat_id' class='seat'/>";
                                                        echo "<label for='$seat_id' class='seat-label'></label>";
                                                    }
                                                    echo "</div>";
                                                }
                                                echo "</div>";
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" name="show_id" value="<?php echo htmlspecialchars($show_id); ?>" />
                                        <input type="submit" name="submit_seats" value="Submit Seats" class="btn btn-dark mt-3">
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="mb-3" style="text-align: center">
                                    <h1 style="color:red; text-transform:uppercase;"><?=$movie['movie_title']?></h1>
                                </div>
                                <div class="movie-details">
                                    <div class="cover-and-details">
                                        <img src="img/<?=$movie['cover']?>" class="movie-cover" alt="Movie Cover">
                                        <div class="details">
                                            <div class="show-date">Date: <b><?=$movie_show['show_date']?></b></div>
                                            <div class="show-start">Show Start: <b><?=$movie_show['show_start']?></b></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <?php if (isset($_SESSION['selected_seats'])): ?>
                                    <div style="text-align: center">
                                        <h4> Chosen Seat/s information </h4>
                                    </div>
                                    <p>Chosen Seat/s:  <i style="color:red";><?= implode(', ', $_SESSION['selected_seats']) ?></i></p>
                                    <p>Number of Seats: <?= $_SESSION['number_of_seats'] ?></p>
                                    <p>Seat Price: <?= $_SESSION['seat_price'] ?> $</p>
                                    <p>Total Price: <b><?= $_SESSION['total_price'] ?> $ </b> </p>
                                <?php endif; ?>
                                <hr>
                                <form method="post">
                                <div class="mb-3" style="text-align: center">
                                    <input type="hidden" name="show_id" value="<?php echo htmlspecialchars($show_id); ?>" />
                                    <input type="submit" value="Reserve" name="reserve_seats" class="btn btn-danger mt-3">
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php 
} else {
    header("Location: index.php");
}
?>
