<?php

session_start();
include "db-connection.php";
include "php/func-movie.php";
include "php/func-show.php";
include "php/func-hall.php";
include "php/search.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search_movie = $_GET['search'];
    $movies = searchMovies($search_movie, conn: $conn);
} else {
    $movies = get_all_movies($conn);
}

$carouselImages = glob('php/carousel/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

$shows = get_all_shows($conn);
$halls = get_all_halls($conn);

if (isset($_SESSION['email']) && isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') { 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/sidebar-toggle.css">
</head>
<body class="d-flex flex-column">
    <div class="container-fluid">
        <div class="row">
            <!-- Toggle Button for Small Devices -->
            <button class="btn btn-primary toggle-btn d-md-none" id="toggleSidebar">
                ☰
            </button>
            <script src="js/sidebar-toggle.js"></script>
            <!-- SIDE BAR -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <a href="./admin_home.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                        <img src="img/admin.jpg" alt="" width="50" height="50" class="rounded-circle me-2">
                        <span class="fs-4">ADMIN</span>
                    </a>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="admin_home.php" class="nav-link active" aria-current="page">
                            <svg class="bi me-2" width="16" height="16"></svg>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add-movie.php" class="nav-link">
                            <svg class="bi me-2" width="16" height="16"></svg>
                                + Add Movie
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add-show.php" class="nav-link">
                            <svg class="bi me-2" width="16" height="16"></svg>
                                + Add Show
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add-hall.php" class="nav-link">
                            <svg class="bi me-2" width="16" height="16"></svg>
                                + Add Hall
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="members.php" class="nav-link">
                            <svg class="bi me-2" width="16" height="16"></svg>
                                Manage Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                            <svg class="bi me-2" width="16" height="16"></svg>
                                Log Out
                            </a>
                        </li>
                    </ul>
                    <hr>
                </div>
            </nav>
            
            <!-- MAIN CONTENT -->
            <main class="col-lg-10 col-md-8 col-sm-12">
                <h4 class="text-center p-3">All Movies</h4>
                <form action="admin_home.php" method="GET" style="width: 100%; max-width: 30rem" role="search">
                    <div class="input-group my-4">
                        <input type="text" class="form-control" placeholder="Search Movie..." name="search" aria-describedby="basic-addon2">
                        <button class="input-group-text btn btn-primary" id="basic-addon2">
                            <img src="img/search.png" width="20" alt="Search">
                        </button>
                    </div>
                </form>

                <?php if (empty($movies)) { ?>
                    <div class="alert alert-warning text-center p-5 mt-3" role="alert">
                        <img src="img/empty.png" width="100">
                        <br>There are no movies in the database
                    </div>
                <?php } else { ?>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Movie Title</th>
                                    <th>Description</th>
                                    <th>Duration</th>
                                    <th>Release Date</th>
                                    <th>Genre</th>
                                    <th>Trailer</th>
                                    <th>Cast</th>
                                    <th>Director</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movies as $movie) { ?>
                                    <tr>
                                        <td><?=$movie['movie_id']?></td>
                                        <td>
                                            <img width="100" src="img/<?=$movie['cover']?>">
                                            <?=$movie['movie_title']?>
                                        </td>
                                        <td><?=$movie['description']?></td>
                                        <td><?=$movie['duration']?></td>
                                        <td><?=$movie['release_date']?></td>
                                        <td><?=$movie['genre']?></td>
                                        <td>
                                            <iframe width="200" height="150" src="<?=$movie['trailer']?>"></iframe>
                                        </td>
                                        <td><?=$movie['movie_cast']?></td>
                                        <td><?=$movie['director']?></td>
                                        <td>
                                            <a href="edit-movie.php?id=<?=$movie['movie_id']?>" class="btn btn-warning">Edit</a>
                                            <a href="php/delete-movie.php?id=<?=$movie['movie_id']?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                                    
                <h4 class="text-center p-3">Manage Carousel</h4>
                <hr>
                    <p>Reccomended image: 800x400px</p>
                    <form action="php/admin-carousel-upload.php" method="POST" enctype="multipart/form-data">
                        <label for="carousel_image">Upload Carousel Image:</label>
                        <input class="btn" type="file" name="carousel_image" id="carousel_image" required>
                        <button class="btn btn-primary" type="submit" name="submit_carousel_img">Upload Image</button>
                    </form>
                    <table class="table table-bordered shadow">               
                    <form action="php/admin-carousel-delete.php" method="POST">
                        <tr>
                            <?php foreach ($carouselImages as $image) { ?>
                            <td>
                                <img src="<?php echo $image; ?>" width="100" height="100">
                                <button type="submit" class="btn btn-danger" name="delete_image" value="<?php echo $image; ?>">Delete</button>
                            </td>
                            <?php } ?>
                        </tr>
                    </form>
                    </table>
                    <hr>



                <!-- SHOWS -->
                <?php if ($shows == 0) { ?>
                    <div class="alert alert-warning text-center p-5" role="alert">
                        <img src="img/empty.png" width="100">
                        <br>There are no shows in the database
                    </div>
                <?php } else { ?>
                    <h4 class="text-center p-3">All Shows</h4>
                    <hr>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Show Date</th>
                                    <th>Show Start</th>
                                    <th>Show End</th>
                                    <th>Seat Price</th>
                                    <th>Cinema Hall</th>
                                    <th>Movie Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($shows as $movie_show) { ?>
                                    <tr>
                                        <td><?=$movie_show['show_id']?></td>
                                        <td><?=$movie_show['show_date']?></td>
                                        <td><?=$movie_show['show_start']?></td>
                                        <td><?=$movie_show['show_end']?></td>
                                        <td><?=$movie_show['price']?></td>
                                        <td>
                                            <?php if ($halls == 0) {
                                                echo "Undefined";
                                            } else {
                                                foreach ($halls as $cinema_hall) {
                                                    if ($cinema_hall['cinema_hall_id'] == $movie_show['cinema_hall_id_fk']) {
                                                        echo $cinema_hall['cinema_hall_name'];
                                                    }
                                                }
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($movies == 0) {
                                                echo 'Undefined';
                                            } else {
                                                foreach ($movies as $movie) {
                                                    if ($movie['movie_id'] == $movie_show['movie_id_fk']) {
                                                        echo $movie['movie_title'];
                                                    }
                                                }
                                            } ?>
                                        </td>
                                        <td>
                                            <a href="edit-show.php?id=<?=$movie_show['show_id']?>" class="btn btn-warning">Edit</a>
                                            <a href="php/delete-show.php?id=<?=$movie_show['show_id']?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <!-- Cinema Halls -->
                <?php if ($halls == 0) { ?>
                    <div class="alert alert-warning text-center p-5" role="alert">
                        <img src="img/empty.png" width="100">
                        <br>There are no halls in the database
                    </div>
                <?php } else { ?>
                    <h4 class="text-center p-3">All Cinema Halls</h4>
                    <hr>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Total Seats</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($halls as $cinema_hall) { ?>
                                    <tr>
                                        <td><?=$cinema_hall['cinema_hall_id']?></td>
                                        <td><?=$cinema_hall['cinema_hall_name']?></td>
                                        <td><?=$cinema_hall['total_seats']?></td>
                                        <td>
                                            <a href="edit-hall.php?id=<?=$cinema_hall['cinema_hall_id']?>" class="btn btn-warning">Edit</a>
                                            <a href="php/delete-hall.php?id=<?=$cinema_hall['cinema_hall_id']?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </main>
        </div>
    </div>

</body>
</html>

<?php
} else {
    header("Location: index.php");
    exit();
}
?>
