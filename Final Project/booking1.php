<?php
include 'functions/function.php';
session_start();

	if(!isset($_SESSION['user'])){
		redirect("index.php");
	}

	if (Timetest() == false){
	    echo(json_encode(2));
        die();
	}

$data = json_decode(stripslashes($_POST['data']));

$user = $data->user;
$ok = 0;

foreach ($data->seats as $seat){

                        $connection=DBconnection();
                        try {
                            // Disable autocommit
                            mysqli_autocommit($connection, false);

                           $query = "SELECT username FROM RESERVATION WHERE seatNo=? FOR UPDATE";

                            $stmt = mysqli_prepare($connection, $query);
                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, "s", $seat);
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

                            if ($username != null && $user != $username){
                                $ok = 1;
                            }

}

echo (json_encode ($ok));


?>