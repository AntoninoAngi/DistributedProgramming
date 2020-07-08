<?php
$user = 's256840';
$password = 'endityst';
$db = 's256840';
$host = 'localhost';
$port = 8889;


function DBconnection(){
	global $dbhost, $dbname, $dbuser, $dbpass;
	$connection = mysqli_connect("localhost", "s256840", "endityst", "s256840");
	if (!$connection) {
		die('Connect error ('. mysqli_connect_errno() . ') '. mysqli_connect_error());
	}
	return $connection;
}

function login($username, $psw){
	$connection=DBconnection();
	$query = "SELECT password FROM USER where username=?";
	if ($stmt = mysqli_prepare($connection, $query)) {
		mysqli_stmt_bind_param($stmt, "s", $username);
		if(!mysqli_stmt_execute($stmt)){
			return false;
		}
		mysqli_stmt_bind_result($stmt, $saved);
	    mysqli_stmt_fetch($stmt);
	    $result=password_verify($psw, $saved);
		mysqli_stmt_close($stmt);
	}else {
		return false;
	}
	mysqli_close($connection);
	return $result;
}


function logout(){
	if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        ); //to have the cookies passed only via secure ways
    }
	session_destroy();
}

function redirect($destination){
	header('Location: '.$destination);
	exit();
}

function redirectToHttps(){
	if((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") && $_SERVER['SERVER_PORT'] != 443){
    		$redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			header("HTTP/1.1 301 Moved Permanently");
    	    header("Location: $redirect_url");
    	    exit();
   }
}

function checkCookie(){
	if(!isset($_GET['cookie'])){
		setcookie('test', 1, time()+3600);
		if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!=""){
			header('Location:'.$_SERVER['PHP_SELF'].'?cookie=true&'.$_SERVER['QUERY_STRING']);
		}else{
			header('Location:'.$_SERVER['PHP_SELF'].'?cookie=true');
		}

	}else{
		if(count($_COOKIE) <= 0){
	    	header('Location: NavigationForbidden.php');
		}
	}
}


function Timetest(){
	$timeleft=time()-$_SESSION['time'];
	if($timeleft>2*60){
		logout();
		return false;
	}
	$_SESSION['time']=time();
	return true;
}


?>