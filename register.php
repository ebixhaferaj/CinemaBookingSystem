<?php
session_start();


if (isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_home.php");
        exit();
    } else if ($_SESSION['role'] == 'user') {
        header("Location: user_home.php");
        exit();
    }
} else {
    // If not logged in, show the form
    include "db-connection.php";

    $name = isset($_GET['name']) ? $_GET['name'] : '';
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $password = isset($_GET['password']) ? $_GET['password'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Kino | Your Cinema</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                    <form class="border-0 shadow p-3" action="php/register_logic.php" method="post" style="max-width: 450px;"> 
                        <h1 style="color:white; text-align: center;"> SIGN UP </h1>
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?=htmlspecialchars($_GET['error'])?>
                            </div>
                        <?php } ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text"  class="form-control" value="<?=htmlspecialchars($name)?>" name="name" id="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"  value="<?=htmlspecialchars($email)?>" class="form-control" name="email" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" value="<?=htmlspecialchars($password)?>" name="password" id="password">
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="role" value="user">
                            <fieldset disabled>
                                <label for="disabledSelect">Role<i class="bi bi-lock"></i></label>
                                <select id="disabledSelect" class="form-control">
                                    <option>user</option>
                                </select>
                            </fieldset>

                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger w-100">Sign up</button>
                        </div>
                        <div style="text-align:center; margin-top:10px;">
                        <a href="index.php" class="nav-link"><p class="text-danger">Already have an account? Log In</p></a>
                        </div>
                    </form>   
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php } ?>
