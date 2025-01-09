<?php  
session_start();
include "../db-connection.php";

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$email = test_input($_POST['email']);
	$password = test_input($_POST['password']);
	$role = test_input($_POST['role']);

	if (empty($email)) {
		header("Location: ../index.php?error=Email is Required");
		exit();
	} else if (empty($password)) {
		header("Location: ../index.php?error=Password is Required");
		exit();
	} else {
		$sql = "SELECT * FROM users WHERE email='$email'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);

			// Verify the password using password_verify
			if (password_verify($password, $row['password']) && $row['role'] == $role) {
				// Password is correct and role matches
				$_SESSION['name'] = $row['name'];
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['role'] = $row['role'];
				$_SESSION['email'] = $row['email'];

				// Redirect based on user role
				if ($row['role'] === 'admin') {
					header("Location: ../admin_home.php");
				} else {
					header("Location: ../user_home.php");
				}
				exit();
			} else {
				header("Location: ../index.php?error=Incorrect Username or Password");
				exit();
			}
		} else {
			header("Location: ../index.php?error=Incorrect Username or Password");
			exit();
		}
	}
} else {
	header("Location: ../index.php");
	exit();
}
