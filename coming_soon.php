<?php
session_start();
include "db-connection.php";
include "php/func-movie.php";

$movies = get_all_movies($conn);

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
                                <a class="nav-link active" href="coming_soon.php">Coming Soon</a>
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
            <!-- Main -->
            <main class="col-xxl-10 col-lg-10 col-md-11 col-sm-10 px-0 mx-auto py-5">
                <h3 style="color:grey; font-family: 'Brush Script MT', cursive; text-align:center;">- next week -</h3>
                
                <?php
                // Query to get movies that are coming next week
                $sql =
                "
                SELECT m.movie_id, m.movie_title, m.genre, m.cover
                FROM movie m
                JOIN movie_show ms ON m.movie_id = ms.movie_id_fk
                WHERE ms.show_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND DATE_ADD(CURDATE(), INTERVAL 14 DAY)
                GROUP BY ms.show_date";
    
                $result = $conn->query($sql);
                                
                                    
    
                if ($result->num_rows > 0) {
                   
                    echo "<div class='row'>";
                    while ($row = $result->fetch_assoc()) { ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-3">
                                <div class="card mx-auto my-2" style="max-width: 100%;">
                                    <a href="movie-details.php?id=<?= $row['movie_id'] ?>">
                                        <img src="img/<?= $row['cover'] ?>" class="card-img-top img-fluid" alt="Movie Cover">
                                        <div class="card-body">
                                            <h5 class="card-title text-center"><?= $row['movie_title'] ?></h5>
                                            <p class="card-text text-center"><?= $row['genre'] ?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                    <?php }
                    echo "</div>";
                } else {
                    echo "No movies found";
                }
                ?>
            </main>
            <!-- Footer -->
            <footer class="text-white text-center text-lg-start" style="background-color: rgb(56,56,56);">
                <div class="container p-4">
                    <div class="row mt-4">
                        <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                            <h5 class="text-uppercase mb-4">About Us</h5>
                            <p style="color: grey;">
                                At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                                voluptatum deleniti atque corrupti.
                            </p>
                            <div class="mt-4">
                                <!-- Facebook -->
                                <a type="button" class="btn btn-floating btn-lg"><i class="bi bi-facebook"></i></a>
                                <!-- Instagram -->
                                <a type="button" class="btn btn-floating btn-lg"><i class="bi bi-instagram"></i></a>
                                <!-- TikTok -->
                                <a type="button" class="btn btn-floating btn-lg"><i class="bi bi-tiktok"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                            <ul class="fa-ul" style="margin-left: 1.65em; color: grey; list-style-type: none;">
                                <li class="mb-3">
                                    <span class="fa-li"><i class="bi bi-pin-angle-fill" style="color:white;"></i></span>
                                    <span class="ms-2">Murat Toptani St, Tirana</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fa-li"><i class="bi bi-envelope-at-fill" style="color:white;"></i></span>
                                    <span class="ms-2">ebi.xhaferaj@gmail.com</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fa-li"><i class="bi bi-telephone-fill" style="color:white;"></i></span>
                                    <span class="ms-2">+ 355 234 567 88</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                            <h5 class="text-uppercase mb-4">Opening hours</h5>
                            <table class="text-center" style="color: grey">
                                <tbody class="fw-normal">
                                    <tr>
                                        <td>Mon - Thu:</td>
                                        <td>8am - 9pm</td>
                                    </tr>
                                    <tr>
                                        <td>Fri - Sat:</td>
                                        <td>8am - 1am</td>
                                    </tr>
                                    <tr>
                                        <td>Sunday:</td>
                                        <td>9am - 10pm</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgb(32,32,32); color: grey;">
                <p>© 2020 Copyright: Kino.com</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php } else {
    header("Location: index.php");
} ?>
