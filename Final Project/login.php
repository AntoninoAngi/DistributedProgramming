<?php
	include 'header.php';
	redirectToHttps();

	if($loggedin){
		redirect("index.php");
	}

	$errorMessage="";
	if (isset($_GET['msg'])){
		if($_GET['msg']=='error'){
			$errorMessage="Wrong username and/or password, try again";
		}else if($_GET['msg']=='timeout'){
			$errorMessage="The authentication time has expired: login to continue your seat reservation process";
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<link href="css/home.css" rel="stylesheet" type="text/css">
		</head>
        	<body>
        	<div id="main">
        		<h1>Login</h1>
        		<p class="errorMsg"><?php echo $errorMessage;?></p>
        		<div class="container"><div class ="button">
        		<form method="POST" action="loginPost.php">
        		<div><input class = "button1" type="submit" id="submit" value="Login"></div>
        		<br> <br>
        		<div><input class = "button1" type="reset" id="reset" value ="Reset"></div></div>
                <div class="table">
        			<label>Insert your username:     <input
        									id="username"
        									type="text"
        									name="username"
        									placeholder="email@address.com"
        									required></label><br><br><br>
        			<label>Insert your password:     <input
        									id="psw1"
        									type="password"
        									name="psw"
        									placeholder="*********"
        									required></label><br>
        			<p id="errorMsg"></p></div>
        		</form>
        			</div>
        	</div>
        	</body>
        </html>