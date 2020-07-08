<?php
include 'functions/function.php';
session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	Timetest();

$data = json_decode(stripslashes($_POST['data']));

$user = $data->user;

$booked = 0;
       $connection=DBconnection();

        try {
		mysqli_autocommit($connection, false);

           $query = "DELETE FROM RESERVATION WHERE username=? AND BOOKED =?";
           	if(!$stmt = mysqli_prepare($connection, $query)){
           	    var_dump("qui1");
           		throw new Exception("Error in deleting the record, try again");
           	}
           	$user = mysqli_real_escape_string($connection, $user);
           	mysqli_stmt_bind_param($stmt, "si", $user, $booked);
           	if(!mysqli_stmt_execute($stmt)){
           	    var_dump("qui2");
           		throw new Exception("Error in deleting the record, try again");
           	}
           	mysqli_stmt_store_result($stmt);
               mysqli_stmt_free_result($stmt);
           	mysqli_stmt_close($stmt);
           	mysqli_commit($connection);
            } catch (Exception $e) {
                mysqli_rollback($connection);
                mysqli_close($connection);
            }
            mysqli_close($connection);

?>