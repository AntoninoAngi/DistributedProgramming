<?php
	include 'functions/function.php';
	session_start();

	if(isset($_SESSION['user'])){
		redirect("index.php");
	}

	$username=htmlentities($_POST['username']);
	$psw=htmlentities($_POST['psw']);

	if(login($username, $psw)){
		$_SESSION=array();
		$_SESSION['user']=$username;
		$_SESSION['time']=time();
    	redirect("index.php");
	}

	redirect("login.php?msg=error");
?>