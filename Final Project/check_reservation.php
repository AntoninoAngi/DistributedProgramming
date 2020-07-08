<?php
include 'functions/function.php';
session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	if (Timetest() == false){
        echo(json_encode(3));
        die();
	}

$data = json_decode(stripslashes($_POST['data']));

$seat = $data->seat;
                        $connection=DBconnection();
                        try {
                            mysqli_autocommit($connection, false);

                           $query = "SELECT BOOKED FROM RESERVATION WHERE seatNo=? FOR UPDATE";

                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, "s", $seat);
                            if(!mysqli_stmt_execute($stmt)){
                                throw new Exception("Error on interrogating the database");
                            }
                            mysqli_stmt_store_result($stmt);
                            mysqli_stmt_bind_result($stmt, $booked);
                            mysqli_stmt_fetch($stmt);
                            mysqli_stmt_free_result($stmt);
                            mysqli_stmt_close($stmt);

                            mysqli_commit($connection);
                            } catch (Exception $e) {
                                mysqli_rollback($connection);
                                mysqli_close($connection);
                            }
                            mysqli_close($connection);

if ($booked == null)
    $booked = 1;
else
    $booked = 0;

echo (json_encode ($booked));


?>