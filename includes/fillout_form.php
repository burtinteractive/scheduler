<?php include_once("includes/db_conn.php");

 $id= $_GET['id'];
 $dbc= new db_conn();
 $con = $dbc->connect();
 
 $query="select confirmed, request_id from email_list where random_string ='".$_GET['s']."' and request_id=".$_GET['id'];

 $res = $dbc->select($query, $con); 
 $confirmed = mysql_result($res, 0,"confirmed");
 $request_id = mysql_result($res, 0,"request_id");
 
$query= "select active from request where id= $request_id";
$res_active =$dbc->select($query, $con); 
$active = mysql_result($res_active, 0,"active");
 
if($active){
 
if($confirmed ==0 || $confirmed ==1){


 $query = "select * from request where id=$id "; 
 $request = $dbc->select($query, $con); 
 
 
 
 $query = "select date from dates where request_id=$id order by did"; 
 $dates = $dbc->select($query, $con); 
 $query = "select * from times where date_id=$id order by tid"; 
 $times = $dbc->select($query, $con); 
 $time;
 $count=0;
 while($row= mysql_fetch_array($dates)){
 	$time[$count]= $row["date"];
 	$count++;
 }
//also need to find out the largest number of columns we need
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


	
	<div class="row">
		<div class="small-12 large-12 columns">
		<p>Please check the times that you can attend below</p>
		<?php while($row = mysql_fetch_array($request)){ ?>
		<p>Requested by: <?php echo $row["name"]; ?></p>
		<p>Email: <?php echo $row["email"];
			$email=$row["email"];
		 ?></p>
		<p>Meeting location: <?php echo $row["location"]; ?></p>
		<p>Subject: <?php echo $row["subject"]; ?></p>
		<p>Description: <?php echo $row["description"]; ?></p>
		<?php } ?>
			<table id="times">
				<thead>
					<th>Dates</th>
					<?php 
						$count=1;
						while($count <=$max_col)  {
					
						echo "<th>time$count</th>";
						$count++;
					}
					
					?>
					
				</thead>
				<tr>
				<?php 
				$count=1;
				$header_count=0;
				$curr_row_num=0;
				$row_num=0;
				
				while($row= mysql_fetch_array($times)){
					
						if($row_num !=0 && ($row_num != $row["row_num"])){
							
							echo "</tr>";
							$count=1;
						}
						//why $row_num and db value not be 0 at first
						
						if(($row_num != $row["row_num"])){
							if($count< $max_col){
								$temp_count = $max_col-$count;
								while($temp_count< ($max_col-1)){
									echo "<td></td>";
									$temp_count++;
								}
							}
							echo "</tr><tr>";
							$count=1;
						}
						
						$row_num = $row["row_num"];
						//echo $row_num;
						if($count ==1){
							echo "<td>".$time[$header_count]."</td>";
							$header_count++;
						}	
						if($row["time"]=="0:00"){
							//echo "<td>".$row["time"]."</td>";
							echo "<td></td>";
						}else{
							if($confirmed == 1){
								$query="select accept from submitted_times where time_id=".$row["tid"];
								$res_time_id= mysql_query($query);
								$accept= mysql_result($res_time_id, 0, 'accept');
								if($accept){
									$class="class='attending' ";
								}else{
									$class="class='not_attending'";
								}
								echo "<td $class >".$row["time"]."</td>";
						
							}else{
								echo "<td>".$row["time"]." <input type='checkbox' id='".$row["tid"]."'></td>";
							}
						}
						$count++;
					
					
					
						if($count >($max_col)){
						
							echo "</tr>";
							$count=1;
						}
				
					
				
				}?>
				</tr>
				
			</table>
			<?php if($confirmed ==0){ ?>
			<p style="clear:both;"><button id='all'><span id="all_text">Select&nbsp;all</span><span style="display:none;" id="all_text2">Deselect</span></button><br/><br/></p>
			<?php }?>
			<p>
				<textarea rows="4" cols="20" id="comments">additional comments</textarea>
			</p>
		</div>
	</div>
		<div class="row">
		<div class="small-12 large-12 columns">
		<?php if($confirmed ==0){?>
			<button id="submit">Submit</button>
			<?php } ?>
		</div>
	</div>
<script type="text/javascript">
$("#all").click(function(){
	
	$("#times tr td input").each(function() {
  		$this = $(this);
  		
  		if($("#all_text2").css("display") =="block"){
  		
  			$(this).prop("checked",false);
  			

  		}else{
  			
  			$(this).prop("checked",true);
  			

  		}
  		
  		
  		
  	});
  	if($("#all_text2").css("display")=="none"){
  		$("#all_text2").css("display","block");
  		$("#all_text").css("display","none");
  	}else{
  		$("#all_text2").css("display","none");
  		$("#all_text").css("display","block");
  	
  	}
	
});

$("#submit").click(function(){
	$variables =[];
	$("#times tr td input:checkbox").each(function(index){
							
			if($.trim($(this).val()) != "" && $.trim($(this).val())!= null ){
					$variables[index] = $(this).is(":checked")+":"+$(this).attr("id");
					
					
			}
			
			
	});
	$comments = $("#comments").val();
	$.post("includes/interface.php",{'variables[]':$variables, page:'user_submit', email:"<?php echo $email;  ?>", main_id:"<?php echo $_GET['id'];  ?>", comments:$comments, random_string:"<?php echo $_GET['s'];  ?>"},function(){
	
		setTimeout(function(){ window.location.replace("thanks.php?mes=2");  }, 800);
	} );
											
});
</script>		
<?php }else{ ?>

	<div class="row">
		<div class="small-12 large-12 columns">
		<p>Your response has already been logged.</p>
		
		</div>
	</div>
<?php }
}else{ ?>
	<div class="row">
		<div class="small-12 large-12 columns">
			<p>This meeting request has been taken down or resubmitted by the author.</p>
		</div>
	</div>
<?php }

?>
	