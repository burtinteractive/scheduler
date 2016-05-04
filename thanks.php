<?php
include("includes/session.php");

$session = new session();
$page='info';
if(isset($_GET['mes'])){
	$mes = $_GET['mes'];
}else{
	$mes ="";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9"/>
		<meta http-equiv="X-UA-Compatible" content="IE=9"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		
		<title>Testing datepickr</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="css/normalize.css"/> 
		<link rel="stylesheet" type="text/css" href="css/foundation.min.css"/> 
		<link rel="stylesheet" type="text/css" href="css/layout.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type='text/javascript' src='js/foundation.min.js'></script>

	
</head>
<body>
	<div class="row">
		<div class="small-12 large-12 columns">
			<img src="images/scheduler_logo.png">
		</div>
	</div>
	<div class="row">
		<div class="small-12 large-12 columns">
			<h3>Thank You for using ScheduleIT.</h3>
			<?php if($mes== "2"){?>
				<p>Your response has been recorded and the organizer has been notified.</p>
			<?php }else if($mes=="3"){ ?>
				<p>Your cancellation has been sent to those invited.</p>
			<?php }else if($mes=="4"){ ?>
				<p>Your meeting request has been sent.</p>
			
		
			<?php }else{ ?>
				<p>Your email has been sent and you will receive a verification email shortly.</p>
			<?php } ?>
		</div>
	</div>
	
		
</body>

</html>
	