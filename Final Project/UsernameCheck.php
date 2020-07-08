<?php
	include 'functions/function.php'; 
	include 'functions/FunctionSigningUp.php';
	
	if(!isset($_POST['username'])){
		echo 1;
		die();
	}
	echo UsernameCheckDuplicate($_POST['username']);
?>