<?php
include 'functions/function.php';
session_start();
checkCookie();

	if (isset($_SESSION['user'])){
		redirectToHttps();
		$user = htmlentities($_SESSION['user']);
		$loggedin = TRUE;
	}else {
		$loggedin = FALSE;
	}
    	if ($loggedin){
    		Timetest();
    		echo "<div class='sidenav'>
    		  <a href='index.php'>Home</a>
    		  <a href='logout.php'>Logout</a>
    		</div> ";
    	}else{
    		echo "<div class='sidenav'>
    		  <a href='index.php'>Home</a>
    		  <a href='login.php'>Login</a>
    		  <a href='signUp.php'>Sign up</a>
    		</div>";
    	}
?>
<noscript> <h1> Javascript is not enabled on your browser. Please, enable it to reserve a seat </h1> </noscript>