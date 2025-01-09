<?php

session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {

    $id = $_GET['id'];

    # Database Connection File
    include "db-connection.php";

    // Movies helper function
    include "php/func-movie.php";
    $movies = get_all_movies($conn);

    // Hall helper function
    include "php/func-hall.php";
    $halls = get_all_halls($conn);

    # Show helper function
    include "php/func-show.php";
    $show = get_show($conn, $id);

    # If ID not set -> back to Admin Home
    if ($show == 0) {
        header("Location: admin_home.php");
        exit;
    }

    $show_date = isset($_GET['show_date']) ? $_GET['show_date'] : '';
    $show_start = isset($_GET['show_start']) ? $_GET['show_start'] : '';
    $show_end = isset($_GET['show_end']) ? $_GET['show_end'] : '';
    $cinema_hall = isset($_GET['cinema_hall']) ? $_GET['cinema_hall'] : '';
    $movie = isset($_GET['movie']) ? $_GET['movie'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Show</title>
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
                            <a href="add-movie.php" class="nav-link">
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
                                Manage Members
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
            <!--EDIT SHOW-->
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="container d-flex justify-content-center align-items-center min-vh-100">
                    <form action="php/edit-show.php"
                       method="post"
                       enctype="multipart/form-data" 
                       class="shadow p-4 rounded mt-5"
                       style="width: 90%; max-width: 50rem;">

             	    <h1 class="text-center pb-5 display-4 fs-3">
             	    	Edit Show
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
	        	               Show Date
	        	               </label>
                        <input type="text" 
	        	            value="<?=$show['show_id']?>" 
	        	            hidden
                            name="id">
	        	        <input type="text" 
	        	               class="form-control"
	        	               value="<?=$show['show_date']?>" 
	        	               name="show_date">
	        	    </div>
                    
	        	    <div class="mb-3">
	        	        <label class="form-label">
	        	               Show Start
	        	               </label>
	        	        <input type="text" 
	        	               class="form-control" 
	        	               value="<?=$show['show_start']?>"
	        	               name="show_start">
	        	    </div>
                    
                    <div class="mb-3">
	        	        <label class="form-label">
	        	               Show End
	        	               </label>
	        	        <input type="text" 
	        	               class="form-control" 
	        	               value="<?=$show['show_end']?>"
	        	               name="show_end">
	        	    </div>

                    <div class="mb-3">
	        	        <label class="form-label">
	        	               Seat Price
	        	               </label>
	        	        <input type="text" 
	        	               class="form-control" 
	        	               value="<?=$show['price']?>"
	        	               name="price">
	        	    </div>
                    
                    <div class="mb-3">
	        	        <label class="form-label">
	        	               Cinema Hall
	        	               </label>
                               <select name="cinema_hall"
	        	                class="form-control">
	        	        	    <option value="0">
	        	        	    	Select Cinema Hall
	        	        	    </option>
	        	        	    <?php 
                                if ($halls == 0) {
                                	# Do nothing!
                                }else{
	        	        	    foreach ($halls as $cinema_hall) { 
	        	        	    	if ($cinema_hall['cinema_hall_id'] == $show['cinema_hall_id_fk']) { ?>
	        	        	    	<option 
	        	        	    	  selected
	        	        	    	  value="<?=$cinema_hall['cinema_hall_id']?>">
	        	        	    	  <?=$cinema_hall['cinema_hall_name']?>
	        	        	        </option>
	        	        	        <?php }else{ ?>
	        	    				<option 
	        	    					value="<?=$cinema_hall['cinema_hall_id']?>">
	        	    					<?=$cinema_hall['cinema_hall_name']?>
	        	    				</option>
	        	        	   <?php }} } ?>
	        	        </select>
	        	    </div>
                                    
                    <div class="mb-3">
	        	        <label class="form-label">
	        	               Movie
	        	               </label>
                               <select name="movie"
	        	                class="form-control">
	        	        	    <option value="0">
	        	        	    	Select Movie
	        	        	    </option>
	        	        	    <?php 
                                if ($movies == 0) {
                                	# Do nothing!
                                }else{
	        	        	    foreach ($movies as $movie) { 
	        	        	    	if ($movie['movie_id'] == $show['movie_id_fk']) { ?>
	        	        	    	<option 
	        	        	    	  selected
	        	        	    	  value="<?=$movie['movie_id']?>">
	        	        	    	  <?=$movie['movie_title']?>
	        	        	        </option>
	        	        	        <?php }else{ ?>
	        	    				<option 
	        	    					value="<?=$movie['movie_id']?>">
	        	    					<?=$movie['movie_title']?>
	        	    				</option>
	        	        	   <?php }} } ?>
	        	        </select>
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
