<?php
	include 'functions/function.php';
	include 'functions/FunctionSigningUp.php';
	session_start();

	if(isset($_SESSION['user'])){
		redirect("index.php");
	}

	$username=htmlentities($_POST['username']);
	$psw1=htmlentities($_POST['psw1']);
	$psw2=htmlentities($_POST['psw2']);

	if(UsernameCheckDuplicate($username)==0 && CheckPassword($psw1, $psw2) && CheckEmailValid($username)) {
		if(insertNewUser($username, $psw1)){
    		$_SESSION=array();
			$_SESSION['user']=$username;
			$_SESSION['time']=time();
    		redirect("index.php");
    	}

	}

	redirect("signUp.php?msg=error");
?>