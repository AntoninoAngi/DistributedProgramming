<?php
	include 'header.php';
	$errorMessage="";
	if(isset($_GET['msg'])){
		$errorMessage=$_GET['msg'];
	}
	$length = 10;
	$width = 6;
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Homepage - Seat reservation</title>
		<meta content="text/hmtl; charset=utf-8" http-equiv="content-type"> </meta>
		<script type="text/javascript" src="javascript_code/jquery-3.4.1.min.js">
        </script>
         <script type="text/javascript" src="javascript_code/home2.js"> </script>
		<link href="css/home.css" rel="stylesheet" type="text/css">
		<script>
		$(document).ready(function(){
		createTable('<?php echo $width;?>', '<?php echo $length;?>');
		<?php
                if ($loggedin) {?>
                    logged_in('<?php echo $user; ?>');
                <?php
                }?>

		});
		</script>
    </head>
    <body>
        <h1>Seat Reservation </h1>
        <div class="container">
        <div class="buttons"><?php
                        if ($loggedin) {?>
                            <form id="bookForm" method="POST">
                            <div><input class="button1" id="bookButton" type="button" value="Buy" disabled> </input></div>
                             </br></br>
                            <div><input class="button1" id="update" type="button" value ="Update" onClick="window.location.reload();"> </input></div>
                            </form>
                            <script> event_button(); </script>
                        <?php
                        } ?>
</div>
        <div class="table" style="width: 800px;"><?php
                        if ($loggedin){
                        echo "Welcome ". htmlentities($user);}?>
        <table id="table">

        </table></div>
        </div>
        <br>
                        <?php if(!$loggedin){ ?>
                        <div id = "statistics"> </div>
                        <script> statistics(); </script>
                       <?php }?>



    </body>
</html>