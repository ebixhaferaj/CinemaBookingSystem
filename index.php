<?php
session_start(); // Start the session

// Check if the session is already set
if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    // Redirect based on the user role
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_home.php");
        exit();
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: user_home.php");
        exit();
    }
} else {
    // If no session is active, clear any session issues and redirect to the login page
    if (isset($_SESSION['email']) || isset($_SESSION['user_id'])) {
        $_SESSION = []; 
        session_destroy(); 
    }
}

// Display the login form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Kino | Your Cinema</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="d-flex flex-column" style="background-color: rgb(32,32,32); color:white;">
    <div class="row">
        <div class="col-xxl-8 col-lg-6 col-md-4 col-sm-10 px-0 mx-auto">
            <img src="img/movies_tile.jpg" class="movie-tiles" alt="Movie image">
        </div>

        <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-10 mx-auto">
            <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
                <div class="form-overlay w-100">
                    <form class="border-0 shadow p-3" action="php/check-login.php" method="post" style="max-width: 450px;"> 
                        <h1 style="color:white; text-align: center;"> LOGIN </h1>
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $_GET['error'] ?>
                            </div>
                        <?php } ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" class="form-select" name="role">
                                <option selected>user</option>
                                <option>admin</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger w-100">Sign in</button> <!-- Full width on small screens -->
                        </div>
                        <div style="text-align:center; margin-top:10px;">
                            <a class="nav-link" href="register.php"><p class="text-danger">Don't have an account? Sign up</p></a>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
