<?php 

 $view= $_GET['view'];
 $dbc= new db_conn();
 $con = $dbc->connect();
 
 $query="select * from request where random_num = $view";

 $res = $dbc->select($query, $con); 
 $num = mysql_num_rows($res);
 $active = mysql_result($res, 0, 'active');

 if($num>0){
 	 $id = mysql_result($res, 0, "id");
 $description = mysql_result($res, 0, "description");
  $location = mysql_result($res, 0, "location");
 	//get the number of people in the email list from current request id
 $query= "select count(id) from email_list where request_id = $id";
 $res_count = mysql_query($query);
 $all_people_count = mysql_result($res_count, 0);
 $query= "select * from email_list where request_id = $id";
 $res = mysql_query($query);
 $random_string_array;
 $string_count= 0;
 while($row = mysql_fetch_array($res)){
 	//echo $row["random_string"]." random string here<br/>";
 	$random_string_array[$string_count]= $row["random_string"];
 	$string_count++;
 }	
 
  
	
	 $query = "select * from times where date_id=$id order by tid"; 
	
	 $times = $dbc->select($query, $con); 
	
	
	$query ="select count(row_num) from times where date_id = $id group by row_num";

	$max_cols = $dbc->select($query, $con); 
	$max_col=0;
	while($row= mysql_fetch_array($max_cols)){
		$col = $row['count(row_num)'];
		
		if($col > $max_col){
			$max_col = $col;
		}
	}		

?>
	<div style="float:left;width:60%;">
	<?php if($active ==1){ 
		//see if there are any people that have entered in their data
		$query = "select * from submitted_times where request_id = $id";
		$res=mysql_query($query);
		$number_entries = mysql_num_rows($res);
		if($number_entries >0){
	?>
			<h3>Click one of the green highlighted dates to choose the meeting time.</h3>
	
<?php		}else{ ?>
			<h3>No one has responded yet.</h3>
		<?php }
	}else{
		echo "<h3>This meeting has been cancelled</h3>";
	
	}
	$query= "select el.id as id1, el.request_id, el.email as email1, el.random_string, el.confirmed, el.email_type, st.id, st.email as email2, st.user_string, st.time_id, st.accept, st.request_id from email_list as el left join submitted_times as st on el.random_string = st.user_string  where el.request_id = $id and el.email_type!='cc' and st.user_string is null ";
	$res2 = $dbc->select($query, $con); 
	while($row = mysql_fetch_array($res2)){ 
	
		//grab name of person on email list
		
		$email = $row['email1'];
		$query= "select * from people where email ='".$email."'";
		
		
		
		$res_person = mysql_query($query);
		
		$res_person_count = mysql_num_rows($res_person);
		if($res_person_count >0){
		
			$fname = mysql_result($res_person, 0, 'fname');
			$lname = mysql_result($res_person, 0, 'lname');
		}else{
			$fname=$email;
			$lname="N/A";
		}
		
	?>
				
				<table >
				<thead>
						<th><?php echo $fname;?></th><th><?php echo $lname;?> &nbsp;&nbsp;&nbsp;&nbsp;has not responded</th><th colspan="<?php echo ($max_col-1);  ?>">  <button id='rm<?php echo $row['id1']; ?>' onclick="remind(this);">Remind</button></th>
				</thead>
				
				</table>
				
	<?php }




	
	
	
	$query= "select distinct (id), request_id, email, random_string, confirmed from email_list  where request_id = $id  ";
	
	$res2 = $dbc->select($query, $con); 
	while($row = mysql_fetch_array($res2)){ 
	
		$query = "select count(user_string) from submitted_times where user_string = '".$row['random_string']."'";
		
		$submitted_time_count_res = $dbc->select($query, $con); 
		//grab name of person on email list
		$submitted_time_count = mysql_result($submitted_time_count_res,0);
		
		if($submitted_time_count>0){
		
			$email = $row['email'];
			$query= "select * from people where email ='".$email."'";
			
			$res_person = mysql_query($query);
			$res_person_count = mysql_num_rows($res_person);
			if($res_person_count >0){
				$fname = mysql_result($res_person, 0, 'fname');
				$lname = mysql_result($res_person, 0, 'lname');
			}else{
				$fname=$email;
				$lname="N/A";
			}
	?>	
				
					<table >
					<thead>
							<th><?php echo $fname;?></th><th><?php echo $lname;?></th><th colspan="<?php echo ($max_col-1);  ?>"></th>
					</thead>
						
				
					<tr>
				
						<th>Dates</th>
						<?php 
							$count=1;
							while($count <=$max_col)  {
					
							echo "<th>Time$count</th>";
							$count++;
						}
					
						?>
					
					</tr>
				
					<?php 
					$count=0;
					$header_count=0;
					$curr_row_num=0;
					$query = "select * from dates where request_id=$id order by did"; 
	
 	 				$dates = $dbc->select($query, $con); 
					while($row3= mysql_fetch_array($dates)){
						echo "<tr>\r\n";
				
						
							echo "<td>".$row3["date"]."</td>";
						
					
						$query ="select * from times where date_id =".$id." and row_num= $count";
						
						$res_rows = $dbc->select($query, $con); 
					
						while($row2=mysql_fetch_array($res_rows)){
								//grab accept dates but also by user so use string
								$query ="select accept from submitted_times where time_id=".$row2['tid']." and user_string='".$row["random_string"]."'";
							
								$res_accept= $dbc->select($query, $con); 
								$count_accept = mysql_num_rows($res_accept);
								if($count_accept>0){
									$accept = mysql_result($res_accept, 0);
									if($accept){
										$class="class='attending' onclick=\"final_time(this)\"";
									}else{
										$class="class='not_attending'";
									}
							
								}else{
									$class="";
								}
							
								if($row2['time']=="0:00"){
									echo "<td ></td>\r\n";
								}else{
									echo "<td $class id='".$row2['tid']."'>".$row2['time']."</td>\r\n";
								}
						
						}
					
						$count++;
						echo "</tr>\r\n";
					}?>
				
				
				
			</table> <?php
			} //end of if statement
			
	 }
	}else{
	
		echo "<p>Sorry, it appears that this is not a valid meeting request.</p>";
	}
?>
	<?php if($active ==1 ){ ?>
	<button id='cancel' title="Click to cancel your meeting.">Cancel</button>
		<?php if($number_entries >0){ ?>
			<button id="final_time_button" title="Schedule the final meeting time." >Schedule</button>
				
	<?php		}
	
		}?>
	</div>
	<div style="float:right;width:300px;">
		<div id="final_times">		
				
				
					
				
			
		</div>
		<?php if($number_entries >0){ ?>
			<button id="clear_final_text"  style="float:right;position:absolute;bottom:0;display:none;right:0;">Clear</button>
		<?php }?>
		<?php if($active ==1 ){ ?>
		
		<?php } ?>
	</div>
<script type="text/javascript">

	function remind(el){
		var confirm1 = confirm("Are you sure you want to send a reminder?");
		if(confirm1){
			$theid=el.id.substring(2);
			$.post("includes/interface.php",{'id':$theid, page:'remind'} );
		}
	}
	
	$("#cancel").click(function(){
		
		var confirm1 = confirm("Are you sure you want to cancel the meeting?");
		if(confirm1){
		
		$theid=<?php echo $_GET['id']; ?>;
		
		$.post("includes/interface.php",{'id':$theid, page:'cancel'} );
		setTimeout(function(){ window.location.replace("thanks.php?mes=3");  }, 800);
		}
		
		
	
	
	});
	

	$("#final_times").empty();
	function final_time(el){
	
		$("#clear_final_text").css("display","block");
		$("#final_times").text($("#"+el.id).parent().find('td').first().text() +" " + el.innerHTML)
		
		$("#final_times").html("<h3>"+$("#"+el.id).parent().find('td').first().text() +" " + el.innerHTML+"</h3><input type='hidden' id='final_time_id' value='"+el.id+"'>")
	}
	
	$("#clear_final_text").click(function(){
	
		$("#final_times").text("");
		$("#final_times").empty();
		$("#clear_final_text").css("display","none");
	});
</script>