<?php //signUp.php
	include 'header.php';

	redirectToHttps();
	if($loggedin){
		redirect("index.php");
	}

	if (isset($_GET['msg']) && $_GET['msg']=='error'){
		$errorMessage="Error during signing up, try again";
	}else{
		$errorMessage="";
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sign Up</title>
		<link href="css/home.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="javascript_code/jquery-3.4.1.min.js">
		</script>
		<script type="text/javascript" src="javascript_code/signUp.js">
		</script>
	</head>
	<body>
	<div id="main">
		<h1>Sign up to book a seat</h1>
		<p class="errorMsg"><?php echo $errorMessage;?></p>
		<div class="container"><div class="button">
		<form method="POST" action="signUpPost.php">
		<div><input class="button1" type="submit" id="submit" value="Sign up" disabled></div>
		<br><br>
        <div><input class="button1" type="reset" id="reset" value="Reset"></div></div>
        <div class="table">
			<label>Insert a username (a valid email address)<br><br><input
									id="username"
									type="email"
									name="username"
									placeholder="email@address.com"
									required></label><br>
			<p id="errorMsg3" class="errorMsg"></p>
			<label>Insert a password (at least 2 characters, a lowercase character and an uppercase character or a digit)<br><br><input
									id="psw1"
									type="password"
									name="psw1"
									placeholder="**********"
									required></label><br>
			<p id="errorMsg" class="errorMsg"></p>
			<label>Repeat the password<br><br><input
									id="psw2"
									type="password"
									name="psw2"
									placeholder="**********"
									required></label><br>
			<p id="errorMsg2" class="errorMsg"></p></div>
            </form>
            </div>
            </div>
	</body>
</html>