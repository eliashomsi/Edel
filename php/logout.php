<?php 
//init
session_start(); 
include  "functions.php";
?>


<?php
	// logging out by swiping the session
	unset($_SESSION['userName']);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
?>