<?php
include 'functions/function.php';
session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	Timetest();

$data = json_decode(stripslashes($_POST['data']));

$user = $data->user;
                    $connection=DBconnection();
                    try {
                        // Disable autocommit
                        mysqli_autocommit($connection, false);
                        $query = "UPDATE RESERVATION SET BOOKED = 1 WHERE username =?";
                        if(!$stmt = mysqli_prepare($connection, $query)){
                            throw new Exception("Error in updating the record, try again");
                        }
                        mysqli_stmt_bind_param($stmt, "s", $user);
                        if(!mysqli_stmt_execute($stmt)){
                            throw new Exception("Error in updating the record, try again");
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