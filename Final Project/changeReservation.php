<?php
include 'functions/function.php';
session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	Timetest();


$data = json_decode(stripslashes($_POST['data']));

$user = $data->user;
$seat = $data->seat;

       $connection=DBconnection();
        try {
        		// Disable autocommit
        		mysqli_autocommit($connection, false);
                   $query = "UPDATE RESERVATION SET username =? WHERE seatNo =?";
                   	if(!$stmt = mysqli_prepare($connection, $query)){
                   		throw new Exception("Error in updating the record, try again");
                   	}
                   	$user = mysqli_real_escape_string($connection, $user);
                   	mysqli_stmt_bind_param($stmt, "ss",$user, $seat);
                   	if(!mysqli_stmt_execute($stmt)){
                   		throw new Exception("Error in updating the record, try again");
                   	}
                   	mysqli_stmt_store_result($stmt);
                   	if(mysqli_stmt_affected_rows($stmt)!=1){
                   		throw new Exception("Error in updating the record, try again");
                   	}
                       mysqli_stmt_free_result($stmt);
                   	mysqli_stmt_close($stmt);
                   	mysqli_commit($connection);
                    } catch (Exception $e) {
                        mysqli_rollback($connection);
                        mysqli_close($connection);
                    }
                    mysqli_close($connection);

?>