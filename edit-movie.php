<?php

session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {

    $id = $_GET['id'];

    # Database Connection File
    include "db-connection.php";

    # Movie helper function
    include "php/func-movie.php";
    $movie = get_movie($conn, $id);

    # If ID not set -> back to Admin Home
    if ($movie == 0) {
        header("Location: admin_home.php");
        exit;
    }

    if (isset($_GET['title'])) {
    	$title = $_GET['title'];
    }else $title = '';

    if (isset($_GET['desc'])) {
    	$desc = $_GET['desc'];
    }else $desc = '';

    if (isset($_GET['duration'])) {
    	$duration = $_GET['duration'];
    }else $duration = 0;

    if (isset($_GET['release_date'])) {
    	$release_date = $_GET['release_date'];
    }else $release_date = '';

    if (isset($_GET['genre'])) {
    	$genre = $_GET['genre'];
    }else $genre = '';

    if (isset($_GET['trailer'])) {
    	$trailer = $_GET['trailer'];
    }else $trailer = '';

    if (isset($_GET['movie_cast'])) {
    	$movie_cast = $_GET['movie_cast'];
    }else $movie_cast = '';

    if (isset($_GET['director'])) {
    	$director = $_GET['director'];
    }else $director = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
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
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="add-movie.php" class="nav-link">
                                + Add Movie
                            </a>
                        </li>
                        <li>
                            <a href="add-show.php" class="nav-link">
                                + Add Show
                            </a>
                        </li>
                        <li>
                            <a href="add-hall.php" class="nav-link">
                                + Add Hall
                            </a>
                        </li>
                        <li>
                            <a href="members.php" class="nav-link">
                                Manage Members
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="nav-link">
                                Log Out
                            </a>
                        </li>
                    </ul>
                    <hr>
                </div>
            </nav>
            <!--EDIT MOVIE-->
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="container d-flex justify-content-center align-items-center min-vh-100">
                    <form action="php/edit-movie.php"
                           method="post"
                           enctype="multipart/form-data" 
                           class="shadow p-4 rounded mt-5"
                           style="width: 90%; max-width: 50rem;">
    
             	        <h1 class="text-center pb-5 display-4 fs-3">
             	        	Edit Movie
             	        </h1>
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
             	        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Movie Title
	        	                   </label>
                            <input type="text" 
	        	                value="<?=$movie['movie_id']?>" 
	        	                hidden
                                name="id">
	        	            <input type="text" 
	        	                   class="form-control"
	        	                   value="<?=$movie['movie_title']?>" 
	        	                   name="movie_title">
	        	        </div>
                        
	        	        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Description
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['description']?>"
	        	                   name="movie_description">
	        	        </div>
                        
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Duration
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['duration']?>"
	        	                   name="duration">
	        	        </div>
                        
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Release Date
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['release_date']?>"
	        	                   name="release_date">
	        	        </div>
                        
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Genre
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['genre']?>"
	        	                   name="genre">
	        	        </div>
                        
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Movie Cover
	        	                   </label>
	        	            <input type="file" 
	        	                   class="form-control" 
	        	                   name="cover">
                        </div>
                        <div class="mb-3">
                            <input type="text"
                                hidden
                                value="<?=$movie['movie_id']?>"
                                name="current_cover">
                        
                            <a href="./img/<?=$movie['cover']?>"
                                class="link-dark">Current Cover</a>
	        	        </div>
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Trailer
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['trailer']?>"
	        	                   name="trailer">
	        	        </div>
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Cast
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['movie_cast']?>"
	        	                   name="movie_cast">
	        	        </div>
                        <div class="mb-3">
	        	            <label class="form-label">
	        	                   Director
	        	                   </label>
	        	            <input type="text" 
	        	                   class="form-control" 
	        	                   value="<?=$movie['director']?>"
	        	                   name="director">
	        	        </div>
	                    <button type="submit" 
	                            class="btn btn-primary">
	                            Update</button>
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
