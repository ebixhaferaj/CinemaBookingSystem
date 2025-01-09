<?php
session_start();

if (isset($_SESSION['email']) && isset($_SESSION['user_id']) && $_SESSION['role']=='user') { 
    include "db-connection.php";
    include "php/func-movie.php";
    include "php/func-show.php";

    $id = $_GET['id'];
    $movie = get_movie($conn, $id);
    $shows = get_show_for_movie($conn, $id);

    
    if (!is_array($shows)) {
        $shows = [];
    }

    # If ID not set -> back to User Home
    if ($movie == 0) {
        header("Location: user_home.php");
        exit;
    } 
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
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/trailer.css">
    <link rel="stylesheet" href="css/movie-details.css">
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
                                <a class="nav-link" href="#"><i class="bi bi-person-fill-gear">My Account</i> </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php"><i class="bi bi-door-closed-fill">Log Out</i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- MAIN CONTENT-->
            <main class="col-xxl-10 col-lg-10 col-md-11 col-sm-10 px-0 mx-auto py-5" style="color: white;">
                <div class="row gx-4 gy-5">
                    <div class="col-12 col-lg-6">
                        <?php if (isset($_GET['error'])) { ?>
                          <div class="alert alert-danger" role="alert">
	        	        	  <?=htmlspecialchars($_GET['error']); ?>
	        	          </div>
	        	        <?php } ?>
	        	        <?php if (isset($_GET['success'])) { ?>
                          <div class="alert alert-success" role="alert">
	        	        	  <?=htmlspecialchars($_GET['success']); ?>
	        	          </div>
	        	        <?php } ?>
                        <!--Movie Details -->    
                        <div class="mb-3">
                            <h1 style="color:red; text-transform:uppercase;"><?=$movie['movie_title']?></h1>  
	        	        </div>
                        <div class="mb-3" style="color:grey;">
                            <p><?=$movie['genre']?></p>  
                            <p style="display: flex; align-items: center;">
                                <span>Duration: <?=$movie['duration']?> min</span>
                                <span style="margin-left: 20px;">Release Date: <?=$movie['release_date']?></span>
                            </p> 
                        </div>     
                        <hr>                  
	        	        <div class="mb-5">
                            <p><?=$movie['description']?></p>  
	        	        </div>
                        <div class="mb-3">
                            <p>Cast: <?=$movie['movie_cast']?></p>  
	        	        </div>
                        <div class="mb-3">
                            <p>Director: <?=$movie['director']?></p>  
	        	        </div>
                    </div>
                    <div class="col-12 col-lg-6 iframe-container">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" style="width: 600px; height: 315px;" src="<?=$movie['trailer']?>" allowfullscreen></iframe>
                        </div>
                    </div>
                    <!-- Shows Section -->
                    <div class="col-12">
                        <h2>SHOWS</h2>
                        <p style="color: grey;">Reserve / Buy your tickets</p>
                    <div class="container mb-5">
                        <div class="row">
                            <!-- Show Cards -->
                            <?php foreach ($shows as $movie_show) { ?>
                            <div class="col-md-4 my-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?=$movie_show['show_start']?></h5>
                                        <p class="card-text"><?=$movie_show['show_date']?></p>
                                        <a href="seat-selection.php?movie_id=<?=$id?>&show_id=<?=$movie_show['show_id']?>" class="btn btn-dark">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
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
                                <a class="btn btn-floating btn-lg"><i class="bi bi-facebook"></i></a>
                                <!-- Instagram -->
                                <a class="btn btn-floating btn-lg"><i class="bi bi-instagram"></i></a>
                                <!-- TikTok -->
                                <a class="btn btn-floating btn-lg"><i class="bi bi-tiktok"></i></a>
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
