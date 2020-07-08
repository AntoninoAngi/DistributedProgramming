<?php
	include 'functions/function.php';
	session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	Timetest();
	
	logout();
	redirect("index.php");
?>