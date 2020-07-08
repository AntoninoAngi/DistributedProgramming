<?php
include 'functions/function.php';
session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	if (Timetest() == false){
	    echo(json_encode(0));
	    die();
	}

$data = json_decode(stripslashes($_POST['data']));

$seat = $data->seat;

       $connection=DBconnection();
$booked = 0;
        try {
		mysqli_autocommit($connection, false);

           $query = "DELETE FROM RESERVATION WHERE seatNo=? AND BOOKED =?";
           	if(!$stmt = mysqli_prepare($connection, $query)){
           		throw new Exception("Error in deleting the record, try again");
           	}
           	mysqli_stmt_bind_param($stmt, "si", $seat, $booked);
           	if(!mysqli_stmt_execute($stmt)){
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