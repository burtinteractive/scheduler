<h4>Click on the meeting name to view it.</h4><br/>
<br/><button id="hide_button"style="width:200px;" title="Unhide deleted meetings?">Unhide</button><br/><br/><br/><br/>
<script type="text/javascript">

				var $variables= new Array();
				
				function deleteMeeting(num){
			
					$.post("includes/interface.php",{'id':num, page:'delete'} );
					setTimeout(function(){ window.location.reload();  }, 800);
				}
			$("#hide_button").click(function(){
			
					$("#hidden_div").css("display","block");
			
			});
</script>
<?php

$db2= new db_conn();
$con2 = $db2->connect();
$query="select * from request where user_id =".$_SESSION['user_id']." and active=1;";
$res =$db2->select($query,$con2);?>
<h4>Current Active Meetings</h4>
<?php
//on delete set active to 0 
while($row = mysql_fetch_array($res)){
	
	echo $row['date_submitted']."  <a href='index.php?id=".$row['id']."&view=".$row['random_num']."'>description:  ".$row['description']."</a><a href='#' onclick=\"deleteMeeting('".$row['id']."')\" style='float:right;'>delete meeting</a><br/>";

}
$query="select * from request where user_id =".$_SESSION['user_id']." and active=0;";
$res =$db2->select($query,$con2);
echo "<div id='hidden_div' style='display:none;' class='old_links'>";
?><h4>Deleted Meetings</h4><?php
while($row = mysql_fetch_array($res)){
	
	echo $row['date_submitted']."  <a class='old_links' href='index.php?id=".$row['id']."&view=".$row['random_num']."' >description:  ".$row['description']."</a><br/>";//<a href='#' onclick=\"activateMeeting('".$row['id']."')\" style='float:right;'>reactivate</a><br/>";

}	
	echo "</div>";
?>