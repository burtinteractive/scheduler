	 $(function() {
				$( document ).tooltip();
			});
			
			
			var $test ="this is test";
			var email_flag=true;
			$( document ).ready(function() {
				var $variables= new Array();
				
				
				$(".next").click(function(){
					
					if($(this).attr('id')=="next1"){	
						
						if(isEmail($("#email").val())){
							if($("#name").val() !=""){
								//send variable to be added to the session
						 		getVariables();
						 		
								$.post("includes/interface.php",{'variables[]':$variables, page:'<? echo $page; ?>'} );
								setTimeout(function(){
									$(".step1").slideToggle("slow", function(){
   							 
   							
						  		});
						  		;
								}, 100);
								$(".step2").slideDown("slow", function(){
   							 
   							
						  		})
						  	}else{
						  		alert('your name cannot be blank')
						  	}
						}else{
							alert("email is not valid");
						}
					}else if($(this).attr('id')=="next2"){
						$variables =[];
						 $("#step1").hide("slow", function(){
   							 
   							
						  });
						$('.trash_con').each(function(index){
							
							$variables[index] = "day"+index+";"+$(this).text()+";"+$("#"+this.id+" a").attr("name");
							
							
						});
						if($variables.length >0){
						$.post("includes/interface.php",{'variables[]':$variables, page:'dates'} );
						
						setTimeout(function(){
								
								<?php if($update){ ?>
									$('#add_times').load("includes/times.php?update=true&id=<?php echo $_GET['id']; ?>",{'variables[]':$variables}, function (){

								<?php }else{ ?>
									$('#add_times').load("includes/times.php?update=false",{'variables[]':$variables}, function (){

								<?php } ?>		
										//once loaded we will append the next button and toggler 
								$("#toggle_div").append("<div class='header'><a class='toggler' id='toggle3' >+ </a>set your times</div>");
								$("#times").after("<button class='next' id='next3'>next</button>\n\r");
									
									$('#next3').on('click', function () {
											
											$variables =[];
											$('#times tr td input').each(function(index){
							
												if($.trim($(this).val()) != "" && $.trim($(this).val())!= null  ){
													
													$variables[index] = $(this).parent().parent().attr("id")+"-"+index+";"+$(this).val();
												}
											});
											//send time variables to become sessions
											if($variables.length > 0){
												$.post("includes/interface.php",{'variables[]':$variables, page:'times'} );
												//make email entry visible and slide content down.
												
												$(".emails").css("display","block");
												$(".step4").slideToggle("slow", function(){});
												$("#time_table").slideToggle("slow", function(){});
												
											}else{
												alert("you must have at least 1 time entry");
											}
									});
									
									$('#toggle3').on('click', function () {
											$("#"+$(this).attr("id")).parent().parent().parent().next().slideToggle("slow",function(){});
									});
									
								});
							}, 100);
							$(".step2").slideToggle("slow", function(){
   							 
   							
						  	})
							
							}else{
								alert("you must select at least one date");
							}
					}else if($(this).attr('id')=="next3"){
							
							
					
					}
				})
			
				function getVariables(){	
					
					
					$('.field').each(function(index){
				
						
						$variables[index] = 		$(this).attr('id') +";"+$(this).val();
					});
			
				}
			
			$(".toggler").click(function(){
					
					
					$("#"+$(this).attr("id")).parent().parent().parent().next().slideToggle("slow",function(){});
			
			});
			$(".toggler2").click(function(){
					
					
					$("#"+$(this).attr("id")).parent().next().slideToggle("slow",function(){});
			
			});
			
			$(".add_all").click(function(){
					var $text = $.trim($("#"+$(this).attr("id")).parent().next().text().replace(/\n/g,""))
					$all_emails = $("#final_email_list").val()+ $text.replace(/.edu/g,".edu\n");
					
					$("#final_email_list").val($all_emails);
					
			
			});
			
			
			/***********************************************************************
			*Is Email
			*var: string email   vars returned:boolean
			*Checks to see if the email submitted is in correct format
			************************************************************************/
			function isEmail($email) {
					console.log($email + " checking email here");
 				  var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    			
    			  if (filter.test($email)) {
    			  	
        				return true;
    				}
   				  else {
   				  
        				return false;
    			}
  				
			}
			/***********************************************************************
			*Check Email
			*var: string theid    vars returned:boolean
			*
			*will be a cc or direct send
			************************************************************************/
			function checkEmail(theid){
				$('#progress').show();
				if($("#"+theid).val()==""){
					$('#progress').hide();
					return false;
				}else{
				
					return true;
				}
			
			
			}
			/***********************************************************************
			*Process Email
			*vars passed: string theid, string type    vars returned:none
			*takes in the id of the object you want to access as well as if the email
			*will be a cc or direct send
			************************************************************************/
			function processEmail(theid, type){
					var email_list ="";
					if(theid =="final_email_list"){	
						var lines = $('textarea[name =final_email_list]').val().split('\n');
						
					}else{
						//then we split it on ; instead 
						var lines = $("#"+theid).val().split(';');
					}
				
				
						$.each(lines, function(){
  							
  							if(this.length >0){
  								email_flag=isEmail(this);
  							}
  							
  							if(email_flag){
  								email_list = email_list+this+"\n";
  							}else{
  								return false;
  							}
  							
						});
						if(email_flag){
							$.post("includes/interface.php", {list:email_list , page: 'update', id_string:"<?php echo $id_string;  ?>", type:type} , function(data){
						
								setTimeout(function(){ window.location.replace("thanks.php");  }, 800);
							});
						
						}else{
							alert("One of your emails is not in the correct format");
							$('#progress').hide();
						}
			
			
			}
			
			
			$("#send").click(function(){
					//$('#progress').show();
					
					//need function to set cc and email flag.
					//check email and cc if not empty parse next
					//check the send field first
					var currCheck = checkEmail("curr_email");
					var finalListCheck = checkEmail("final_email_list");
				
					
					if(currCheck){
						//if it has values send
						processEmail("curr_email","send");	
						//if send field is full check cc field
						if(checkEmail("cc_email")){
							//if it has values send
							processEmail("cc_email","cc");
						}
					
					}
					
					
					//check the email list if empty but cc and email where full it's ok.
					//send list of emails over as text then save all Sessions variables in DB
					
				  if(finalListCheck){
				  
				  	 processEmail("final_email_list","send");
					
				  }
					
				  if(!(finalListCheck) && !(currCheck)){
					 alert("The main email field must be filled or the email list both cannot be empty")
				  }
					
					
					
			});
			$("#update").click(function(){
					//$('#progress').show();
					
					//need function to set cc and email flag.
					//check email and cc if not empty parse next
					//check the send field first
					var currCheck = checkEmail("curr_email");
					var finalListCheck = checkEmail("final_email_list");
				
					
					if(currCheck){
						//if it has values send
						processEmail("curr_email","send");	
						//if send field is full check cc field
						if(checkEmail("cc_email")){
							//if it has values send
							processEmail("cc_email","cc");
						}
					
					}
					
					
					//check the email list if empty but cc and email where full it's ok.
					//send list of emails over as text then save all Sessions variables in DB
					
				  if(finalListCheck){
				  
				  	 processEmail("final_email_list","send");
					
				  }
					
				  if(!(finalListCheck) && !(currCheck)){
					 alert("The main email field must be filled or the email list both cannot be empty")
				  }
			});
		
			$("#add_email").click(function(){
					
				if($("#curr_email").val() ==""){
					alert("selection cannot be empty");
				}else{	
					//split emails up bu ;
					var the_emails=$("#curr_email").val().split(";");
					$.each(the_emails, function(i, v){
						v= v.trim();
						console.log(v + " this is email");
						//verify that email is in proper format
						if(v.length> 1){
							if(isEmail(v)){
								//if(isEmail($("#curr_email").val())){
								$all_emails = $("#final_email_list").val()+ v+"\n";
					
								$("#final_email_list").val($all_emails);
								$("#curr_email").val('');
							}else{
					
								alert("Email must be in a proper format " + v);
							}
						}	
					
					});
				}
				
				
					//now we check the cc list of emails and add it to the list
					var the_emails=$("#cc_email").val().split(";");
					$.each(the_emails, function(i, v){
						v= v.trim();
						console.log(v + " this is email");
						//verify that email is in proper format
						if(v.length> 1){
							if(isEmail(v)){
								//if(isEmail($("#curr_email").val())){
								$all_emails = $("#final_email_list").val()+ v+"\n";
					
								$("#final_email_list").val($all_emails);
								$("#cc_email").val('');
							}else{
					
								alert("Email must be in a proper format " + v);
							}
						}	
					
					});
				
					
			
			});
			
			
			
			$("#clear_button").click(function(){
					
					$("#final_email_list").val("");
					$("#curr_email").val("");
					$("#cc_email").val("");
			});
			
			var last_length=0;
			$("#curr_email").keyup(function(){
					
					if($("#curr_email").val() ==""){
						last_length=0;
						console.log("last length is reset");
					}
					
					//get a matching email and return a list
					var curr_letter = $("#curr_email").val();
					$.post("includes/interface.php", {letters: curr_letter, page: 'search'} , function(data){
						
						
						//after we get the data returned in an array need to make it clickable
						//clean out current div then refill with new 
						$("#curr_email_list").empty();
						$("#curr_email_list").css("display","block");
						if(data.indexOf("Undefined") >-1){
							
						}else{
							var $data = JSON.parse(data);
							if($data.length >0){
								for($i=0;$i<$data.length;$i++){
									$("#curr_email_list").append("<a id='found_email"+$i+"' >"+$data[$i]+";</a><br/>");
							
										$('#found_email'+$i).on('click', function () {
											$("#curr_email_list").empty();
												console.log("it is found "+ curr_letter.length);
												
												//just take letters and shave it off old value or get length and add substring to current value
												$old_value = $("#curr_email").val();
											if(last_length ==0){
												$("#curr_email").val($old_value.substring(curr_letter.length)+$(this).html());
												console.log("it equals zero here "+$(this).html().substring(curr_letter.length) );
											}else{
												//$("#curr_email").val($old_value+$(this).html().substring(last_length));
												$("#curr_email").val($old_value.substring(0,last_length)+$(this).html());
												console.log("it equals !zero here " );
											}
											last_length=$("#curr_email").val().length;
											console.log(last_length+ " the length");
											$("#curr_email_list").empty();
									
										});
							
									}
								
							}else{
								$("#curr_email_list").empty();
								$("#curr_email_list").css("display","none");
							}
						
						}
					});
					
			});
			var last_length2=0;
			$("#cc_email").keyup(function(){
				if($("#cc_email").val() ==""){
						last_length=0;
						console.log("last length is reset");
					}
					//get a matching email and return a list
					var curr_letter = $("#cc_email").val();
					$.post("includes/interface.php", {letters: curr_letter, page: 'search'} , function(data){
						
						
						//after we get the data returned in an array need to make it clickable
						//clean out current div then refill with new 
						$("#curr_email_list").empty();
						$("#curr_email_list").css("display","block");
						if(data.indexOf("Undefined") >-1){
							
						}else{
							var $data = JSON.parse(data);
							if($data.length >0){
								for($i=0;$i<$data.length;$i++){
									$("#curr_email_list").append("<a id='found_email"+$i+"' >"+$data[$i]+";</a><br/>");
							
										$('#found_email'+$i).on('click', function () {
											$("#curr_email_list").empty();
												console.log("it is found "+ curr_letter.length);
												
												//just take letters and shave it off old value or get length and add substring to current value
												$old_value = $("#cc_email").val();
											if(last_length2 ==0){
												$("#cc_email").val($old_value.substring(curr_letter.length)+$(this).html());
											}else{
												//$("#curr_email").val($old_value+$(this).html().substring(last_length));
												$("#cc_email").val($old_value.substring(0,last_length2)+$(this).html());
											}
											last_length2=$("#cc_email").val().length;
											console.log(last_length2+ " the length");
											$("#curr_email_list").empty();
									
										});
							
									}
								
							}else{
								$("#curr_email_list").empty();
								$("#curr_email_list").css("display","none");
							}
						
						}
					});
					
			});
			
			$("#final_time_button").click(function(){
			
				
					if($("#final_times").html() != ""){
						var curr_id= $("#final_time_id").val();
					
						$.post("includes/interface.php", {time_id: curr_id, page: 'final_invite'} , function(data){
					
					
						});
					}else{
					
						alert("must select a time on the left before sending");
					}
			});
		
				
			
			
		});