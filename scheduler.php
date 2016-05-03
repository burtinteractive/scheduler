	<?php
	/************************************
	* Take in the date for the scheduler and make sure it
	*is not an old date
	*
	*************************************/
	$date_parts;
	function verifyDate($the_date){
	
		date_default_timezone_set("America/New_York");
		$year= date("Y");
		$day = date("j");
	
		$month=  date("m");
	
			//compare month and year and day to see if it is past
			$date_parts = explode(" ",str_replace(",","",$the_date));
			if($year >= $date_parts[3]){
				if($month >= $GLOBALS["month_key"][$date_parts[1]]){
				
					if( trim($date_parts[2])>= $day){
						return true;
					}else{
						return false;
					}
				
				}else{
					return false;
				}
				
			}else{
				return false;
			}
		
	}
	
	
	  if($update){
		$query="select * from dates where request_id = $id";
	
		$res =mysql_query($query);
		$selected_days;
		$oldDates;
		$month_key=array("January"=>1, "February"=>2, "March"=>3, "April"=>4, "May"=>5, "June"=>6, "July"=>7, "August"=>8,"September"=>9,"October"=>10,"November"=>11,"December"=>12);
					
		$date_count=0;
		while($row= mysql_fetch_array($res)){ 
							
			$oldDates[$date_count]=trim(str_replace("\r\n","",$row['date']));
				
			$date_parts = explode(" ",str_replace(",","",$row['date']));
			$temp_date=$date_parts[2]."day".$month_key[$date_parts[1]];
			$selected_days[$date_count] = $temp_date;
			$date_count++;
								
		}
	
	}
	?>
	
	<div class="row">
					<div class="large-12 columns">
						<div class="header"><a class="toggler" id="toggle2">+ </a> Pick your dates</div>
					</div>
			</div>
			<div class="step2 row" style="display:none;">		
					<div class="large-4 columns" id="calcon" >
					
					
					</div>
					<input type="hidden" id="hidden">
					<div class="large-4 columns " id="dates">
						<?php	
					
						if($update){	
							
							$date_count=0;
							foreach($selected_days as $day){
							
								if(verifyDate($oldDates[$date_count])){
									echo "<div id=\"trash_con".$day."\" class=\"trash_con\">\r\n";
									echo "<a class=\"trash\" name=\"".$day."\" href=\"#\">\r\n";
									echo "<img class=\"trash_image\" width=\"30\" src=\"images/trash.png\">\r\n";
									echo "</a>\r\n";
									echo $oldDates[$date_count];
									echo "</div>\r\n";
									echo "<script>";
									echo "$('.trash').on('click', function () {";
      	 					
      	 							echo "$(this).parent().remove();";
      	 					
      	 							echo "$(\"#\"+$(this).attr(\"name\")).removeClass(\"on\");";
      	 							
      	 							echo "deleteDates($(this).attr(\"name\"),\"false\");";
      	 							echo "});";
      	 							echo "</script>";
      	 				
      	 				
									
								}else{
									echo "<script>alert(\"$oldDates[$date_count]  is past the current date and cannot be used\");</script>";
								}
								$date_count++;
							}
						}	
						?>
					</div>
					<div class="large-4">
					
					</div>
			
			</div>
		<script type="text/javascript" src="js/datepickr.js"></script>
		<script type="text/javascript">
			var oldDates = new Array();
			//holds the id of current dates selected. id format is day number then month number
			var dateArray2 = new Array();
			
			
			
			//holds the key to all the parameters to the date object we sort this array and then the 
			//date objects are printed in sorted order to delete refrence to object in array just delete refrence in this array
			var dateArray4= new Array();
			
			
			var dateOb2 ={};
			var idArray = new Array();
			var $monthArray = ["January", "February", "March", "April", "May", "June", "July", "August","September","October","November","December"];
			var $month="";
			var $year ="";
			var $dateCount =0;
			var long_dateArray = new Array();
			var $parts ;
			var tempSpace;
			
			<?php	
				$date_count=0;
				if($update){	
				//fill dateArray2	
			
		
						foreach($selected_days as $day){ 
						
						?>
								dateArray2[<?php echo $date_count; ?>]="<?php echo $day; ?>";
								
								temp_date1 = "<?php echo $day; ?>";
								 $parts = temp_date1.split("day");
								 tempSpace= $parts[1]+"."+$parts[0];
								
								 dateOb2[tempSpace]="<?php echo $day; ?>"+":<?php echo $oldDates[$date_count]; ?>";
								 dateArray4.push(tempSpace)
						<?php	
							$date_count++;
						}
						
				}
				
			?>
			$dateCount= <?php echo $date_count; ?>;
			<?php $date_count =0; ?>
			new datepickr('calcon hidden', {
				'fullCurrentMonth': false,
				'dateFormat': 'l, F j, Y',
				'old_dates': oldDates
			}).open();
			
			var $num=0;
			
			
			function showDate(el){
				if(checkDates($(el).attr('id'))){
				
					//if date check out need to sort array then repop dates container
					
					setTimeout(function(){
						$dateCount++;

						dateArray2[$num] = $(el).attr('id');
						
					
					
						$parts = $(el).attr('id').split("day");
						tempSpace= $parts[1]+"."+$parts[0];
						
						dateOb2[tempSpace]=$(el).attr('id')+":"+$("#hidden").val();
						dateArray4.push(tempSpace);
						
						sortDates();
						//make for loop here empty dates out and redo it
						$("#dates").empty();
						for( $da2 in dateArray4){
						
							var tempString = dateOb2[ dateArray4[$da2]];
							
							var final_parts = tempString.split(":");
							$('#dates').append("<div id='trash_con"+final_parts[0]+"' class='trash_con'><a href='#' class='trash' name='"+final_parts[0]+"'><img src='images/trash.png' width='30' class='trash_image' ></a>"+final_parts[1]+"</div>");
      	 					$('.trash').on('click', function () {
      	 						//removes trash button
      	 						$(this).parent().remove();
      	 						//removes class
      	 						$("#"+$(this).attr("name")).removeClass("on");
      	 						//remove from date array
      	 						deleteDates($(this).attr("name"),"false");
      	 					
      	 				
      	 					});
      	 					$("#add_times").empty();
							
      	 				}
      	 		
      	 			}, 100);
      	 		 	$num++;
      	 		 }			
			}
			function highlight(el){
				
				if($(el).hasClass('on')){
					$(el).removeClass('on');
					$("#trash_con"+$(el).attr('id')).remove();
					deleteDates($(el).attr('id'),"false");
				}else{
					$(el).addClass('on');
				}
				
				
			}
			
			
			function sortDates(){
			
	
				dateArray4.sort(function(a,b){return a - b});
				for(var $d2 in dateArray4 ){
					
					
				
					//var tempOb = dateArray4[$d2];
				
					//console.log(tempOb.ldate+" yeah data here "+ tempOb.sdate );
					
					
				}
				
				
			}
			
			
			function deleteDates(dateString, check){
 				var $date_parts = dateString.split("day");
 				var $variables= new Array();
 				for(var $d2 in dateArray4 ){
 					
 					if(($date_parts[1]+"."+$date_parts[0]) ==dateArray4[$d2]){
 						dateArray4.splice($d2,1);
 					}
 				}
 				$count =0;
					while($count < dateArray2.length){
						if(dateArray2[$count]==dateString){
							dateArray2[$count]= null;
							$('#'+idArray[$count]).remove();
							$dateCount--;
								
								if($dateCount ==0){
									//date == 0 so now if time is open delete time and close it up
									
									if($("#time_table").css("display")=="block"){
										//hide div
										$("#time_table").slideToggle("slow", function(){});
										//delete actual times from table
										//removes whole table
										$("#time_table").remove();
									}
								}
							
							break;
						}
						$count++;
					}
					
				
					if(check == "true"){
					
						$('.trash_con').each(function(index){
							
							$variables[index] = "day"+index+";"+$(this).text()+";"+$("#"+this.id+" a").attr("name");
							
							
						});
						if($variables.length >0){
							$.post("includes/interface.php",{'variables[]':$variables, page:'dates'} );
						}
					
					} else{
					
					}
					
 			}
 			
 			
 			
 			
 			
 			
 			/******************************************
 			*checks to see if the date has already been added the list
 			*will prevent duplicate dates from being added.
 			*
 			*******************************************/
			function checkDates(dateString){
				
					$count =0;
					while($count < dateArray2.length){
						
						if(dateArray2[$count]==dateString){
							//dateArray2[$count]= null;
							$('#'+idArray[$count]).remove();
							return false
						}
						$count++;
					}
				
				return true;
			}
			
			
			<?php		
						if($update){	
							foreach($selected_days as $day){
							
								echo "$(\"#".$day."\").addClass(\"on\");";
							}
						}
						?>
						
				
		$(".nextMonth").click(function(){
 			setTimeout(function(){
 				
 				//traverse month and dateArray2 to see if a date has been selected already. If so turn it on
 				$(".main_cal tr td").each(function() {
   					curr_day_id = $(this).find('span').attr('id');
   					
   					$.each(dateArray2,function(i, val){
   							
   							
   							if(dateArray2[i] == curr_day_id && dateArray2[i] !=""){
   								
   								$("#"+curr_day_id).attr('class','on');
   							}
   					
   					});
   					
   					
   				 });

 				}, 500);
 			
 			});
 			
 			$(".prevMonth").click(function(){
 				setTimeout(function(){
 				//traverse month and dateArray2 to see if a date has been selected already. If so turn it on
 				$(".main_cal tr td").each(function() {
   					curr_day_id = $(this).find('span').attr('id');
   					
   					$.each(dateArray2,function(i, val){
   							
   							
   							if(dateArray2[i] == curr_day_id){
   								
   								$("#"+curr_day_id).attr('class','on');
   							}
   					
   					});
   					
   					
   				 });
 				}, 500);
 			});
						
		</script>
