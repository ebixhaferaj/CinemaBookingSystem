<?php

session_start();
include "db-connection.php";
include "php/get-user-data.php";

$user = get_all_users($conn);
$admins = get_all_admins($conn);

$name = isset($_GET['name']) ? $_GET['name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

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
                <h4 class="text-center p-3">Users</h4>
                <?php if (empty($user)) { ?>
                    <div class="alert alert-warning text-center p-5 mt-3" role="alert">
                        <img src="img/empty.png" width="100">
                        <br>There are no users in the database
                    </div>
                <?php } else { ?>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user as $users) { ?>
                                    <tr>
                                        <td><?=$users['user_id']?></td>
                                        <td><?=$users['name']?></td>
                                        <td><?=$users['email']?></td>
                                        <td>
                                            <a href="php/delete-member.php?id=<?=$users['user_id']?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <h4 class="text-center p-3">Admins</h4>
                <?php if (empty($admins)) { ?>
                    <div class="alert alert-warning text-center p-5 mt-3" role="alert">
                        <img src="img/empty.png" width="100">
                        <br>There are no users in the database
                    </div>
                <?php } else { ?>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $users) { ?>
                                    <tr>
                                        <td><?=$users['user_id']?></td>
                                        <td><?=$users['name']?></td>
                                        <td><?=$users['email']?></td>
                                        <td>
                                            <a href="php/delete-member.php?id=<?=$users['user_id']?>" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <div class="container" style="justify-content:center;">
                <form class="border-0 shadow p-3" action="php/register_logic.php" method="post" style="max-width: 450px;"> 
                        <h1 style="color:white; text-align: center;"> SIGN UP </h1>
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?=htmlspecialchars($_GET['error'])?>
                            </div>
                        <?php } ?>
                        <h4>Add new Admin</h4>
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
                            <input type="hidden" name="role" value="admin">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Create</button>
                        </div>

                </form>   
                </div>
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
