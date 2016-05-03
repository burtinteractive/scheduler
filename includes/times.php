<script src="js/parse_time.js"></script>
<?php   
	$update = $_GET['update'];
	
	if($update =="true"){
	
		$update = true;
	}else{
		
		$update =false;
	}
		$count =0;
		echo "<div class='row'>\n\r";
		echo "<div class='large-12 columns' id='toggle_div'>\n\r";
		
		echo "</div>\n\r";
		echo "</div>\n\r";
		echo "<div class='row' id='time_table' style='width:100%;'>\n\r <div class='large-12 columns'><table id='times'>\n\r";
		
		if(!($update)){
			echo "<tr id='header'><th></th><th>Time 1</th><th>Time 2</th><th>Time 3</th></tr>";
			$tdcount=0;
		
			foreach($_POST['variables'] as $var){
				
				$temp = explode(";", $var);
				echo "<tr class='times' id='row".$count."'>\n\r";		
					
					echo "<td><a id='delete$count' onclick=\"removeRow(this);removeAll('trash_con$temp[2]');\"><img src='images/trash.png' width='20' ></a>$temp[1]</td>\n\r";			
					echo "<td><input type='text' id='td".$tdcount."' class='curr_times'  value='' onblur=\"parseTime(this)\" ></td>\n\r";	
					echo "<td><input type='text' id='td".($tdcount+1)."' class='curr_times' value='' onblur=\"parseTime(this);\" ></td>\n\r";	
					echo "<td><input type='text' id='td".($tdcount+2)."' class='curr_times' value='' onblur=\"parseTime(this);\" ></td>\n\r";	
					$tdcount=$tdcount+3;
				echo "</tr>\n\r"	;	
				$count++;	
			}
		}else{
			$time_id= $_GET['id'];
			//pull in table from database and populate it that way
			include("db_conn.php");
			$dbc = new db_conn();
			$con = $dbc->connect();
			$query = "select * from dates where request_id=".$time_id;
		
			$res_times  =$dbc->select($query, $con);
			$time_count= mysql_num_rows($res_times);
			$col_count=0;
			$row_count =0;
			$tdcount=0;
			$query_dates = "select date from dates where request_id=".$time_id;
			
			$res = mysql_query($query_dates);
			$date_array;
			
			$date_count =0;
			
			while($row = mysql_fetch_array($res)){
				
				$date_array[$date_count]= $row['date'];
				$date_count++;
				
				
				
				
			}
			
			if($time_count >0){
				while($row2=mysql_fetch_array($res_times)){
				
						$query = "select max(row_num) from times where date_id=".$time_id;
						$res_row_count = mysql_query($query);
						$row_count = mysql_result($res_row_count, 0);
						
						$query = "select * from times where date_id=".$time_id." order by row_num";
						$query2 = "select * from times where date_id=".$time_id." order by row_num";
						
		
					$res_times  =$dbc->select($query, $con);
					$res_times0  =$dbc->select($query2, $con);
					$temp_col_count=0;
					$final_col_count=0;
					$temp_row=-1;
					while($row_temp = mysql_fetch_array($res_times0)){
						
						if($temp_row != $row_temp['row_num']){
							$temp_col_count=0;
							$temp_row= $row_temp['row_num'];
							
						}
						
						$temp_col_count++;
						if($temp_col_count > $final_col_count){
							$final_col_count=$temp_col_count;
						}
						
					}
				
					if($final_col_count<3){
						$final_col_count =3;
					
					}
					
					$date= $row2["date"];
					$count=-1;
					$date_count = 0;
					$curr_col_count =0;
					$row_num= 0;
					while($row=mysql_fetch_array($res_times)){
					
						//has to be at least 3 columns 
						
						if($row_num != $row["row_num"]){
							
							$row_num = $row["row_num"];
							if(($col_count%$final_col_count)!=0){
								$temp_col_count = $col_count%$final_col_count;
								while($temp_col_count<$final_col_count){
									echo "<td><input type='text' id='td".$tdcount."' onblur=\"parseTime(this);\"  value='' ></td>";
									
									$tdcount++;
									$temp_col_count++;
								}
							}
							$col_count=0;
						}
						
												
						
						if($count ==$row_num){
					
							echo "<td><input type='text' id='td".$tdcount."' value='".$row["time"]."' onblur=\"parseTime(this);\" ></td>";
							$curr_col_count++;
							$tdcount++;
							
						}else{
							if($count==-1){
								
								echo "<tr class='times' id='row".($count+1)."'><td><a id='delete".($count+1)."' onclick=\"removeRow(this)\"><img src='images/trash.png' width='20' ></a>".$date_array[$date_count]."</td>";
								$date_count++;
							}else{
								echo "</tr><tr class='times' id='row".($count+1)."'><td><a id='delete".($count+1)."' onclick=\"removeRow(this)\"><img src='images/trash.png' width='20' ></a>".$date_array[$date_count]."</td>";
								$date_count++;
							}
							echo "<td><input type='text' id='td".$tdcount."' onblur=\"parseTime(this);\"  value='".$row["time"]."' ></td>";
							$count++;
							$tdcount++;
							

						}
						$col_count++;
						
					
					}
						if(($col_count%$final_col_count)!=0){
								$temp_col_count = $col_count%$final_col_count;
								while($temp_col_count<$final_col_count){
									echo "<td><input type='text' id='td".$tdcount."' onblur=\"parseTime(this);\"  value='' ></td>";
									
									$tdcount++;
									$temp_col_count++;
								}
							}
			
				}
				$row_count++;
			
				
				//now get the new times 
			
				$new_count=0;
				
				foreach($_POST['variables'] as $var){
				
				
					if($new_count> $count){
						$temp = explode(";", $var);
						
						echo "<tr class='times' id='row".$new_count."'>\n\r";		
					
							echo "<td><a id='delete$new_count' onclick=\"removeRow(this)\"><img src='images/trash.png' width='20' ></a>$temp[1]</td>\n\r";			
							
							//use col_count variable to finish up the columns now
							
							echo "<td><input type='text' id='td".$tdcount."' class='curr_times' onblur=\"parseTime(this);\" value=''></td>\n\r";	
							echo "<td><input type='text' id='td".($tdcount+1)."' class='curr_times' onblur=\"parseTime(this);\" value=''></td>\n\r";	
							echo "<td><input type='text' id='td".($tdcount+2)."' class='curr_times' onblur=\"parseTime(this);\" value=''></td>\n\r";	
							$tdcount=$tdcount+3;
							$tdcount_counter = 3;
							
							while($tdcount_counter< $final_col_count){
								echo $col_count." ".$tdcount_counter;
								echo "<td><input type='text' id='td".($tdcount)."' class='curr_times' onblur=\"parseTime(this);\" value=''></td>\n\r";	
								$tdcount++;
								$tdcount_counter++;
							}
							
							$tdcount_counter= 0;
						echo "</tr>\n\r"	;	
					}
					
					$new_count++;	
			}
				
				
			}	
			
			
		}
			
		echo "</table>\n\r";
		?>
		
			<button id='add'>Add&nbsp;times</button>
		
		<?php
		echo "</div></div>\n\r"; ?>
		<div class="" id="curr_time_list"></div>
					
				</div>	



<script type="text/javascript">	
	var $time=	4;
	var rowCount= <?php echo $count; ?>;
	var tdcount = <?php echo $tdcount; ?>;
	var eachcount =0;
	var tempcount =0;
	
	var removeCount = 0;

				$("#add").click(function(){
				
						
						
					$("table tr").each(function(){
						
						
						if($(this).attr('id')=="header"){
								$( "#"+$(this).attr("id")+" th:last" ).after("<th>time "+$time+"</th>");
								$time++;
						}else{	
							
							
							//if last element increment count and add method and id
							if($( "#"+$(this).attr("id")+" td:last" ).is(':last-child')){
								$( "#"+$(this).attr("id")+" td:last" ).after("<td><input type='text' class='curr_times' value='' id='td"+(tdcount++)+"'></td>");
									
									$("#td"+(tdcount-1)).on("blur",function(event){
 									 	
 									 	parseTime(this);
									});
							}
						
							
						}
						
					});
					
				})
				
				
				function removeRow(el){
					
					$(el).parent().parent().remove();
					removeCount++;
					if(removeCount == rowCount){
						//removes the whole table
						$("#time_table").remove();
						$("#add").remove();
						$("#toggle3").remove();
					}
					
					
				}
				
				$(".curr_times").focus(function(){
				
					$("#curr_time_list").empty();
					$("#curr_time_list").css("display","none");
				
				});
				
				
				
				function checkNumber(el){
					var curr_time = $(el).val();
					
					if (isNaN(curr_time)) 
  					{
  						if(curr_time.indexOf(":") >= 0){
  							return true
  						}else{
  							$(el).val("");
   							 alert("Only input numbers");
    						return false;
    					}
  					}else{
						return true;
					}
					
				}
				function checkValue(el){
				
					
					if($("#"+$(el).attr("id")).val()=="0:00"){
						$("#"+$(el).attr("id")).val("");
					}
					
				}
				
				function checkValue2(el){
					if($("#"+$(el).attr("id")).val()=="" ||$("#"+$(el).attr("id")).val()==" "){
						$("#"+$(el).attr("id")).val("0:00");
					}
				}
				function getTime(el){
					
					if(checkNumber(el)){
				
						var curr_time = $(el).val();
						
						var curr_val= $("#"+$(el).attr("id")).offset();
						curr_x = curr_val.top + 25;
						curr_y= curr_val.left;
						
						$("#curr_time_list").css({"top":curr_x,"left":curr_y,"position":"absolute","display":"block"});
						
						$.post("includes/interface.php", {time: curr_time, page: 'search_times'} , function(data){
						
							
							//after we get the data returned in an array need to make it clickable
							//clean out current div then refill with new 
							$("#curr_time_list").empty();
							if(data.indexOf("Undefined") >-1){
							
							}else{
								var $data = JSON.parse(data);
								
								if($data.length>1){
									for($i=0;$i<$data.length;$i++){
										$("#curr_time_list").append("<a id='found_time"+$i+"' >"+$data[$i]+"</a><br/>");
							
										$('#found_time'+$i).on('click', function () {
											$("#"+$(el).attr("id")).val($(this).html());
											$("#curr_time_list").empty();
											$("#curr_time_list").css("display","none");
									
										});
							
									}
								}else{
									$("#"+$(el).attr("id")).val("0:00");
									$("#curr_time_list").empty();
									$("#curr_time_list").css("display","none");
								}
							}
						
						
						});
					}
				}
				function removeAll(theid){
					
					
					
					$("#"+theid).remove();
					var parts = theid.split("trash_con");
					deleteDates(parts[1],"true");
					
					
					//remove green from calendar
					$("#"+parts[1]).removeClass("on");
					
				}
</script>
