<?php
include_once("includes/session.php");
include_once("includes/user.php");
include_once("includes/db_conn.php");
 $dbc= new db_conn();
 $con = $dbc->connect();
$session = new session();
$session->killSession();
//include_once('config.php');
//include_once('cas/CAS.php');

$user = new user('test_user');

/*********Uncomment if you have a test CAS system.***********/
//phpCAS::setDebug();
//phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
//phpCAS::setNoCasServerValidation()cd ..
;
// force CAS authentication
//phpCAS::forceAuthentication();

//$arr=$_SESSION["phpCAS"];
//returns the user id and stores in a session
//$user = new user($arr["user"]);
$_SESSION['user_id']= $user;

$page='info';
$update = false;


if(isset($_GET['view'])){

	$id_string = $_GET['view'];

}else{
	$id_string="";
}


if(isset($_GET['view'])&& $_GET['view'] != 2){

		$update =true;
		
		
}else{
		$update= false;
		
		
}
if(isset($_GET['cat'])){
	$cat = $_GET['cat'];
}

//need to check if there is a valid session variable there
// if admin if 

//if just filling out the scheudle and submitting it
//check for id. Check if they have already filled it out. If so set confirmed to 1

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9"/>
		<meta http-equiv="X-UA-Compatible" content="IE=9"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		
		<title>ScheduleIT</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="css/normalize.css"/> 
		<link rel="stylesheet" type="text/css" href="css/foundation.min.css"/> 
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="css/layout.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type='text/javascript' src='js/foundation.min.js'></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
		
		<script type="text/javascript">
		
			 $(function() {
				$( document ).tooltip();
			});
			
			
			var $test ="this is test";
			var email_flag=true;
			$( document ).ready(function() {
				var $variables= new Array();
				
				function deleteMeeting(num){
			
				alert(num);
			
				}
				$(".next").click(function(){
					
					if($(this).attr('id')=="next1"){	
						
						if(isEmail($("#email").val())){
							if($("#name").val() !=""){
								//send variable to be added to the session
						 		getVariables();
						 		
								$.post("includes/interface.php",{'variables[]':$variables, page:'<?php echo $page; ?>'} );
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
								$("#toggle_div").append("<div class='header'><a class='toggler' id='toggle3' >+ </a>Set your times</div>");
								$("#times").after("<button class='next' id='next3'>Next</button>\n\r");
									
									$('#next3').on('click', function () {
											
											$variables =[];
											$('#times tr td input').each(function(index){
							
												if($.trim($(this).val()) != "" && $.trim($(this).val())!= null  ){
													
													$variables[index] = $(this).parent().parent().attr("id")+"-"+index+";"+$(this).val();
												}else{
												
													$variables[index] = "0:00";
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
												alert("You must have at least 1 time entry.");
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
								alert("You must select at least one date.");
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
					//$all_emails = $("#final_email_list").val()+ $text.replace(/.edu/g,".edu\n");
					$all_emails = $("#curr_email").val()+ $text.replace(/.edu/g,".edu;");
					$("#curr_email").val($all_emails);
					
			
			});
			
			
			/***********************************************************************
			*Is Email
			*var: string email   vars returned:boolean
			*Checks to see if the email submitted is in correct format
			************************************************************************/
			function isEmail($email) {
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
						//var lines = $('textarea[name =final_email_list]').val().split('\n');
						
					}else{
						//then we split it on ; instead 
						
						
						var lines = $("#"+theid).val().split(';');
					}
					$('#progress').show();
				
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
				
				
					//need function to set cc and email flag.
					//check email and cc if not empty parse next
					//check the send field first
					var currCheck = checkEmail("curr_email");
					var finalListCheck = checkEmail("final_email_list");
				
					
					if(currCheck){
						var last_char = $("#curr_email").val().substring(($("#curr_email").val().length-1),$("#curr_email").val().length);
						
						if(last_char == ";"){
							
						}else{
							var temp_val = $("#curr_email").val()+";";
							
							$("#curr_email").val(temp_val);
							
						}
						//if it has values send
						processEmail("curr_email","send");	
						//if send field is full check cc field
						if(checkEmail("cc_email")){
							var last_char = $("#cc_email").val().substring(($("#cc_email").val().length-1),$("#cc_email").val().length);
							
							if(last_char == ";"){
							
							}else{
								var temp_val = $("#cc_email").val()+";";
							
								$("#cc_email").val(temp_val);
							
							}
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
												
												
												//just take letters and shave it off old value or get length and add substring to current value
												$old_value = $("#curr_email").val();
											if(last_length ==0){
												$("#curr_email").val($old_value.substring(curr_letter.length)+$(this).html());
											
											}else{
												//$("#curr_email").val($old_value+$(this).html().substring(last_length));
												$("#curr_email").val($old_value.substring(0,last_length)+$(this).html());
												
											}
											last_length=$("#curr_email").val().length;
										
											$("#curr_email_list").empty();
											$("#curr_email_list").css("display","none");
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
												
												
												//just take letters and shave it off old value or get length and add substring to current value
												$old_value = $("#cc_email").val();
											if(last_length2 ==0){
												$("#cc_email").val($old_value.substring(curr_letter.length)+$(this).html());
											}else{
												//$("#curr_email").val($old_value+$(this).html().substring(last_length));
												$("#cc_email").val($old_value.substring(0,last_length2)+$(this).html());
											}
											last_length2=$("#cc_email").val().length;
											
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
				var confirm1 = confirm("Are you sure you want to schedule this meeting?");
					
					if(confirm1){
						$('#progress').show();
						if($("#final_times").html() != ""){
							var curr_id= $("#final_time_id").val();
					
							$.post("includes/interface.php", {time_id: curr_id, page: 'final_invite'} , function(data){
							setTimeout(function(){ window.location.replace("thanks.php?mes=4");  }, 800);
					
							});
						}else{
					
							alert("must select a time on the left before sending");
							$('#progress').hide();
						}
					}
			});
		
				
			
			
		});
		</script>
		
</head>
<body>
	<?php 
		
			$user_arr =$user->getCredentials($user);
			//$user_arr= explode(":",$user_arr);
			
	?>	
	<div class="row">
		<div class="small-12 large-12 columns">
<?php //if a user and in db then display navigation menu
	//default is make schedule. 
	//previous meetings. 
		//meeting status
		if(isset($_GET['s'])){
		
		}else{
		echo "<div class='admin_bar'><a href='index.php'>Schedule</a><a href='index.php?cat=meeting'>My Meetings</a><a href='index.php?cat=help'>Help</a></div>";
}
?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 large-12 columns">
			<img src="images/scheduler_logo.png" style="padding:0 0 20px 0;">
		</div>
	</div>
<?php if(isset($_GET['view'])&& $_GET['view'] == 2){
	
		
		include("includes/fillout_form.php");



}else { 
	
	if(isset($cat)){ ?>
		
		<div class="row">
			<div class="small-12 large-12 columns">
			
				<?php  include("includes/".$cat.".php"); ?>
			</div>
		</div>
	<?php
	}else{

		$name="";
		$email="";
		$location="";
		$description="";
		$subject="";
	

		if(isset($_GET['view'])&& $_GET['view'] != 2){ ?>
			<div class="row">
				<div class="small-12 large-12 columns">
				
				<?php include("includes/admin_check.php"); ?>
				</div>
			</div>
	
<?php 
			//get all the variables we will be passing to the regular form
			$query="select * from request where random_num=".$_GET['view'];
		
			$res= mysql_query($query);
	
			$name =mysql_result($res, 0, "name");
			$email =mysql_result($res, 0, "email");
			$location =mysql_result($res, 0, "location");
			$description =mysql_result($res, 0, "description");
			$subject =mysql_result($res, 0, "subject");
			$id = mysql_result($res, 0, "id");
			$user_id = mysql_result($res, 0,"user_id");
			
			if(isset($people_id)){
			
			
			}else{
				
			
			}
		} 
		
?>
		<div  id="info">
			<div class="row">
				<div class="large-12 columns">
					<div class="header"><a class="toggler" id="toggle1">+ </a> Your information</div>
				</div>
			</div>
			<div class="step1 row">
			
				<div class="small-12 large-4 columns">
					* Name:<input type="text" id="name" class="field" value="<?php if($name !=""){echo $name;}else{ echo $user_arr[2]." ".$user_arr[3]; } ?>">
					* Email:<input type="text" id="email" class="field" value="<?php if($email !=""){echo $email; }else{ echo $user_arr[0]; }?>">
					Meeting Location:<input type="text" id="location" class="field" value="<?php if($location != ""){ echo $location;} ?>">
					Subject:<input type="text" id="subject" class="field" value="<?php if($subject != ""){ echo $subject;} ?>">
					Description:<textarea id="description" class="field"> <?php if($description!=""){echo $description; }?></textarea>

					<input type="hidden" class="field" id="user_id" value="<?php echo $user; ?>">
					<button class='next' id='next1'>Next</button>
					<!--<input type="button" value="next" class="next">-->
				</div>
				<div class="small-12 large-4 columns">
				</div>
				<div class="small-12 large-4 columns">
				</div>
			</div>
			</div>	
		<div >
	
			<?php include("scheduler.php"); ?>
		</div>
	
		<div class="step3" id="add_times">
	
		
			
		</div>
		<div class="emails" id="add_times" style="display:none;">
		
		
			<?php include("includes/email_schedule.php"); ?>
			
		</div>
<?php } 
}?>
<div id="progress"></div>
<div id="nothing"></div>
</body>

</html>
	