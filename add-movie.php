<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {

    # Database Connection File
    include "db-connection.php";

    $title = isset($_GET['title']) ? $_GET['title'] : '';
    $desc = isset($_GET['desc']) ? $_GET['desc'] : '';
    $duration = isset($_GET['duration']) ? $_GET['duration'] : 0;
    $release_date = isset($_GET['release_date']) ? $_GET['release_date'] : '';
    $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
    $trailer = isset($_GET['trailer']) ? $_GET['trailer'] : '';
    $movie_cast = isset($_GET['movie_cast']) ? $_GET['movie_cast'] : '';
    $director = isset($_GET['director']) ? $_GET['director'] : '';
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
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
                            <a href="admin_home.php" class="nav-link" aria-current="page">
                                <svg class="bi me-2" width="16" height="16"></svg>    
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="add-movie.php" class="nav-link active">
                                <svg class="bi me-2" width="16" height="16"></svg>
                                + Add Movie
                            </a>
                        </li>
                        <li>
                            <a href="add-show.php" class="nav-link">
                                <svg class="bi me-2" width="16" height="16"></svg>
                                + Add Show
                            </a>
                        </li>
                        <li>
                            <a href="add-hall.php" class="nav-link">
                                <svg class="bi me-2" width="16" height="16"></svg>
                                + Add Hall
                            </a>
                        </li>
                        <li>
                            <a href="members.php" class="nav-link">
                                <svg class="bi me-2" width="16" height="16"></svg>
                                Manage Users
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="nav-link">
                                <svg class="bi me-2" width="16" height="16"></svg>
                                Log Out
                            </a>
                        </li>
                    </ul>
                    <hr>
                </div>
            </nav>
            <!--ADD MOVIE-->
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="container d-flex justify-content-center align-items-center min-vh-100">
                    <form action="php/add-movie.php"
                          method="post"
                          enctype="multipart/form-data" 
                          class="shadow p-4 rounded mt-5"
                          style="width: 90%; max-width: 50rem;">

                        <h1 class="text-center pb-5 display-4 fs-3">
                            Add New Movie
                        </h1>
                        <?php if (isset($_GET['error'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($_GET['error']); ?>
                        </div>
                        <?php } ?>
                        <?php if (isset($_GET['success'])) { ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($_GET['success']); ?>
                        </div>
                        <?php } ?>
                        <div class="mb-3">
                            <label class="form-label">Movie Title</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($title) ?>" name="movie_title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Movie Description</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($desc) ?>" name="movie_description">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($duration) ?>" name="duration">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Release Date (YYYY-MM-DD)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($release_date) ?>" name="release_date">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($genre) ?>" name="genre">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Movie Cover</label>
                            <input type="file" class="form-control" name="cover">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trailer</label>
                            <input type="text" class="form-control" name="trailer">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cast</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($movie_cast) ?>" name="movie_cast">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Director</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($director) ?>" name="director">
                        </div>

                        <button type="submit" class="btn btn-primary">Add Movie</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    header("Location: index.php");
    exit;
}
?>
