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

$user = $data->user;

$connection=DBconnection();
                    try {
                            // Disable autocommit
                            mysqli_autocommit($connection, false);

                           $query = "SELECT username FROM RESERVATION WHERE seatNo=? FOR UPDATE";

                            $stmt = mysqli_prepare($connection, $query);
                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, "s", $data->seat);
                            if(!mysqli_stmt_execute($stmt)){
                                throw new Exception("Error on interrogating the database");
                            }
                            mysqli_stmt_store_result($stmt);
                            mysqli_stmt_bind_result($stmt, $username);
                            mysqli_stmt_fetch($stmt);
                            mysqli_stmt_free_result($stmt);
                            mysqli_stmt_close($stmt);

                            mysqli_commit($connection);
                            } catch (Exception $e) {
                                mysqli_rollback($connection);
                                mysqli_close($connection);
                            }
                            mysqli_close($connection);


if ($username == null){
        $connection=DBconnection();

       try {
           mysqli_autocommit($connection, false);
           $booked = 0;
           $query = "INSERT INTO RESERVATION(seatNo, username, BOOKED) VALUES (?, ?, ?)";
            if(!$stmt = mysqli_prepare($connection, $query)){
                throw new Exception("Error in the booking process, try again");
            }
            $user = mysqli_real_escape_string($connection, $user);
            mysqli_stmt_bind_param($stmt, "ssi", $data->seat, $user, $booked);
            if(!mysqli_stmt_execute($stmt)){
                throw new Exception("Error in the booking process, try again");
            }
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_affected_rows($stmt)!=1){
                throw new Exception("Error in inserting the new reservation, try again");
            }
            mysqli_stmt_free_result($stmt);
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
       } catch (Exception $e) {
            mysqli_rollback($connection);
            mysqli_close($connection);
       	}
       	mysqli_close($connection);
}else{
    $connection=DBconnection();
                        try {
                            // Disable autocommit
                            mysqli_autocommit($connection, false);
                            $query = "UPDATE RESERVATION SET username = ? WHERE seatNo =?";
                            if(!$stmt = mysqli_prepare($connection, $query)){
                                throw new Exception("Error in updating the record, try again");
                            }
                            mysqli_stmt_bind_param($stmt, "ss", $user, $data->seat);
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
}
?>