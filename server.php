<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', 'lexyy@2609', 'uirp');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $ucp = mysqli_real_escape_string($db, $_POST['ucp']);
  $discordid = mysqli_real_escape_string($db, $_POST['DiscordID']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($ucp)) { array_push($errors, "Ucp is required"); }
  if (empty($discordid)) { array_push($errors, "DiscordID is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM playerucp WHERE ucp='$ucp' OR DiscordID='$discordid' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['ucp'] === $ucp) {
      array_push($errors, "Ucp already exists");
    }

    if ($user['DiscordID'] === $discordid) {
      array_push($errors, "Discord ID already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO playerucp (ucp, DiscordID, password) 
  			  VALUES('$ucp', '$discordid', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['ucp'] = $ucp;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['ucp']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Ucp is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM playerucp WHERE ucp='$ucp' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['ucp'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>