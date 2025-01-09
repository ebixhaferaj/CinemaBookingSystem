<?php
session_start();
include "db-connection.php";
include "php/func-movie.php";
include "php/search.php";

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])){
    $search_movie = $_GET['search'];
    $search_movie = searchMovies($search_movie, $conn);

}

$movies = get_all_movies($conn);
$carouselImages = glob('php/carousel/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

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
                        <form class="d-flex" role="search" action="user_home.php" method="GET">
                            <input class="form-control me-2" style="background-color: rgb(56,56,56); border:none;" type="search" placeholder="Search" name="search" aria-label="Search">
                            <button class="btn" type="submit"><img src="img/search.png" width="20" alt="Search"></button>
                        </form>
                    </div>
                </div>
            </nav>
            <!-- Main -->
            <main class="col-xxl-10 col-lg-10 col-md-11 col-sm-10 px-0 mx-auto py-5">
                <div id="carouselExampleAutoplaying" class="carousel slide pb-5" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $first = true;
                        foreach ($carouselImages as $image) {
                            $activeClass = $first ? 'active' : '';
                            $first = false;
                            ?>
                            <div class="carousel-item <?php echo $activeClass; ?>" data-bs-interval="10000">
                                <img src="<?php echo $image; ?>" class="d-block w-100" alt="Carousel Image">
                            </div>
                        <?php } ?>
                    </div>
    
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <?php if (empty($movies)) { ?>
                <div class="alert alert-warning text-center p-5 mt-3" role="alert">
                    <img src="img/empty.png" width="100">
                    <br>There are no movies in the database
                </div>
            <?php } else { ?>
                <div class="row justify-content-center">
                    <?php 
                    // Check if a search is submitted
                    if (isset($_GET['search'])) {
                        foreach ($search_movie as $movie) { ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-3">
                                <div class="card mx-auto" style="max-width: 100%;">
                                    <a href="movie-details.php?id=<?=$movie['movie_id']?>">
                                        <img src="img/<?=$movie['cover']?>" class="card-img-top img-fluid" alt="Movie Cover">
                                        <div class="card-body">
                                            <h5 class="card-title text-center"><?=$movie['movie_title']?></h5>
                                            <p class="card-text text-center"><?=$movie['genre']?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } 
                    } else { 
                        // Show all movies if no search
                        foreach ($movies as $movie) { ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-3">
                                <div class="card mx-auto" style="max-width: 100%;">
                                    <a href="movie-details.php?id=<?=$movie['movie_id']?>">
                                        <img src="img/<?=$movie['cover']?>" class="card-img-top img-fluid" alt="Movie Cover">
                                        <div class="card-body">
                                            <h5 class="card-title text-center"><?=$movie['movie_title']?></h5>
                                            <p class="card-text text-center"><?=$movie['genre']?></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            <?php } ?>

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
