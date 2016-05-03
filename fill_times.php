<?php
include("includes/session.php");

include("includes/db_conn.php");

$db = new db_conn();
$con =$db->connect();

$count =1;
$count2 = 1;
while($count <= 12){
	
	while($count2 <=4){
		switch ($count2){
		case 1:	
			$time="00";
			break;
		case 2:	
			$time="15";
			break;
		case 3:	
			$time="30";
			break;
		case 4:	
			$time="45";
			break;
		
		}
		$query="insert into time_increments(time_value) values('$count:".$time."pm')";
		
		$query="insert into time_increments(time_value) values('$count:".$time."am')";
		
		$count2++;
		
	}
	
	$count2=1;
	$count++;
}	
?>
