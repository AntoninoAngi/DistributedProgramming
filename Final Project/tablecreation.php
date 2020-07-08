<?php
include 'functions/function.php';
session_start();

       $connection=DBconnection();
       $booked = 0;
    $i=0;
    $seats = array();
               try {
       		// Disable autocommit
       		mysqli_autocommit($connection, false);

                  $query = "SELECT seatNo FROM RESERVATION WHERE BOOKED=? FOR UPDATE";

                  	        $stmt = mysqli_prepare($connection, $query);
                  	        $stmt = mysqli_prepare($connection, $query);
                   		mysqli_stmt_bind_param($stmt, "i", $booked);
                   		if(!mysqli_stmt_execute($stmt)){
                   			throw new Exception("Error on interrogating the database");
                   		}
                   		mysqli_stmt_store_result($stmt);
                   		mysqli_stmt_bind_result($stmt, $seatNo);
                   		while (mysqli_stmt_fetch($stmt)){
                   		    $seats['oranges'][$i] = $seatNo;
                   		    $i++;
                   		}
                   		mysqli_stmt_free_result($stmt);
                   		mysqli_stmt_close($stmt);

                  	mysqli_commit($connection);
                   } catch (Exception $e) {
                       mysqli_rollback($connection);
                       mysqli_close($connection);
                   }
                   mysqli_close($connection);


                    $connection=DBconnection();
                           $booked = 1;
                        $i=0;
                                   try {
                           		// Disable autocommit
                           		mysqli_autocommit($connection, false);

                                      $query = "SELECT seatNo FROM RESERVATION WHERE BOOKED=? FOR UPDATE";

                                      	        $stmt = mysqli_prepare($connection, $query);
                                      	        $stmt = mysqli_prepare($connection, $query);
                                       		mysqli_stmt_bind_param($stmt, "i", $booked);
                                       		if(!mysqli_stmt_execute($stmt)){
                                       			throw new Exception("Error on interrogating the database");
                                       		}
                                       		mysqli_stmt_store_result($stmt);
                                       		mysqli_stmt_bind_result($stmt, $seatNo);
                                       		while (mysqli_stmt_fetch($stmt)){
                                       		    $seats['reds'][$i] = $seatNo;
                                                $i++;
                                       		}
                                       		mysqli_stmt_free_result($stmt);
                                       		mysqli_stmt_close($stmt);

                                      	mysqli_commit($connection);
                                       } catch (Exception $e) {
                                           mysqli_rollback($connection);
                                           mysqli_close($connection);
                                       }
                                       mysqli_close($connection);



                   echo(json_encode($seats));
?>
