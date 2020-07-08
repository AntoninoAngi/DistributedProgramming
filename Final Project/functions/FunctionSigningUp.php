<?php

function CheckPassword($psw1, $psw2){
	return strlen($psw1)>=2 && strlen($psw1)<=255 && CheckPasswordContent($psw1) && $psw1==$psw2;
}

function CheckPasswordContent($psw){
	$pattern='/.*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*/';
	return preg_match($pattern, $psw);
}

function CheckEmailValid($username){
	return filter_var($username, FILTER_VALIDATE_EMAIL) && strlen($username)<=255 && htmlentities($username)==$username;
}

function UsernameCheckDuplicate($username){
	$connection=DBconnection();
	$res=null;
	$query = "SELECT * FROM USER where username=?";
	if ($stmt = mysqli_prepare($connection, $query)) {
		mysqli_stmt_bind_param($stmt, "s", $username);
		if(!mysqli_stmt_execute($stmt)){
			return $res;
		}
		mysqli_stmt_store_result($stmt);
		$res=mysqli_stmt_num_rows($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
	}
	else {
		return $res;
	}
	mysqli_close($connection);
	return $res;

}

function insertNewUser($username, $psw){
	$connection=DBconnection();
	$query="INSERT USER(username, password) VALUES (?,?)";
	if($stmt = mysqli_prepare($connection, $query)){
		if(!$hash=password_hash($psw, PASSWORD_DEFAULT)){
			mysqli_close($connection);
			return false;
		}
		mysqli_stmt_bind_param($stmt, "ss", $username, $hash);
		if(!mysqli_stmt_execute($stmt)){
			mysqli_close($connection);
			return false;
		}
		mysqli_stmt_store_result($stmt);
		$res=mysqli_stmt_affected_rows($stmt)==1;
		mysqli_stmt_free_result($stmt);
		mysqli_close($connection);
		return $res;
	}else{
		mysqli_close($connection);
		return false;
	}
}

?>