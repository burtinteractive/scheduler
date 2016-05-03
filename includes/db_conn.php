<?php
class db_conn{

	private $name   ="";
	private $pass 	="password";
	private $user	="user1";	
	private $conn	="";
	private $host	="localhost";
	private $db		="yourdatabase";
 
	
	
	 function insert($query, $con){
	 
		mysql_query("$query", $con) or die(mysql_error());
		
	}
	public function clearDB($table, $con){
		mysqli_query("delete  from $table", $con) or die(mysql_error());
	}

	public function connect(){
		$conn=mysqli_connect($this->host,$this->user,$this->pass,$this->db);
		
		if (mysqli_connect_errno())
  		{
  			echo $user;
  			echo $db;
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		}
		 return $conn;
	}
	public function close($con){
			
		//mysqli_close($con);
	}
	
	public function mysqli_result($res,$row=0,$col=0){ 
    	$numrows = mysqli_num_rows($res); 
    	if ($numrows && $row <= ($numrows-1) && $row >=0){
        	mysqli_data_seek($res,$row);
        	$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        	if (isset($resrow[$col])){
           	 	return $resrow[$col];
        	}
   	 	}
    	return false;
	}
	
	public function select($query, $con){
		
		$res = mysqli_query($con,$query);
		
		return $res;
	}
	public function makeDept($query, $con){
			$list = "";
			$department="";
			$res = mysqli_query($con,"$query");
			
			$curr_dept="";
			$count=5;
			$row_count=0;
			$list.="<div class='row'>\n\r";
			while($row = mysql_fetch_array($res)){
				$dept=$row['department'];	
				$email=$row['email'];
				
					
				if($curr_dept != $dept){
					
					
					
				
					if($curr_dept !=""){
						$list .="</ul>\n\r</div>\n\r</div>\n\r</div>\n\r";
						$row_count++;
						if($row_count >= 3){
						
							$list.="</div>\n\r";
							$row_count= 0;
							$list.="<div class='row'>\n\r";
						}
					}
					$list .="<div class='large-4 columns'><div class='dept_con'>\n\r<div class='dept_banner'>\n\r <a class=\"toggler2\" id=\"toggle$count\">+ </a>\n\r$dept <a class='add_all' id='add_all$count' style='float:right;'>add all</a></div>\n\r<div class='dept_inner_con' id='dept_inner_con$count'>\n\r<ul>\n\r<li><a onclick=\"addEmail('".$email."');\">".$email."</a></li>\n\r";
					$count++;
					
					
					
				}else{	
					if($email !="" || $email != null){
						$list .="<li><a onclick=\"addEmail('".$email."');\">".$email."</a></li>\n\r";
					}else{
					}
				}
				$curr_dept = $dept;
				
			}
			$list .="</ul>\n\r</div>\n\r</div>\n\r</div>\n\r</div>\n\r";
			return $list;
	}
	
	/**************************************************************************************
	*This is called when a user submits their times for requested meeting
	*
	*
	***************************************************************************************/
	public function submit2($var,$con, $email, $main_id, $random_string,$comments){
		$headers ="From:noreply@yourdomain.com"."\r\n";          
 	    $headers .="MIME-Version: 1.0" . "\r\n";
		$headers  .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		//loop through each time slot and update database
		foreach($var as $v){
			
			$arr = explode(":", $v);
			
			$bool =0;
			if($arr[0] =="true"){
				$bool = 1;
			}else{
				$bool =0;
			}
			$query="insert into submitted_times(email, user_string, request_id, accept, time_id, comments)values('".$email."','". $random_string."', $main_id,'".$bool."', $arr[1],'".addslashes($comments)."' )";
			//echo $query."\n\r";
			mysqli_query($con, $query);
		}
			$query="update email_list set confirmed =1 where random_string='".$random_string."'";
			//echo $query."\n\r";
			mysqli_query($con, $query);
			
			$query = "select * from request where id =$main_id";
			$res = mysqli_query($con, $query);
			$subject .= mysqli_result($res,0,'subject');
			/*********************************************************************************************/
			//send email and all times selected form the user*********************************************
			
			$query="select * from email_list where random_string='".$random_string."'";
			$email_res= mysqli_query($con, $query);
			
			$user_email= mysqli_result($email_res,0,'email');
			
			$query = "select * from times where date_id=$main_id order by tid"; 
			$times = mysqli_query($con, $query); 
			
			$query = "select * from request where id=$main_id "; 
 			$request = mysqli_query($con, $query); 
 
 
 
 			$query = "select date from dates where request_id=$main_id order by did"; 
 			$dates = mysqli_query($con, $query); 
			 $query = "select * from times where date_id=$main_id order by tid"; 
 			$times = mysqli_query($con, $query); 
 			$time;
 			$count=0;
 			while($row= mysqli_fetch_array($dates)){
 				$time[$count]= $row["date"];
 				$count++;
 			}
			//also need to find out the largest number of columns we need
			$query ="select count(row_num) from times where date_id = $main_id group by row_num";
			$max_cols = mysqli_query($query); 
			$max_col=0;
			while($row= mysqli_fetch_array($max_cols)){
				$col = $row['count(row_num)'];
		
				if($col > $max_col){
					$max_col = $col;
				}
			}
			
			$query_main="select * from request where id=$main_id";
			$main_res = mysqli_query($con, $query_main);
			$main_name=mysqli_result($main_res,0,'name');	
			$main_email=mysqli_result($main_res,0,'email');	
			$main_subject=mysqli_result($main_res,0,'subject');	
			$main_location=mysqli_result($main_res,0,'location');	

			$main_description=mysqli_result($main_res,0,'description');	
			$body2="Your accepted times are below\r\n<br/>";
			$body2.="Name: ".$main_name."\r\n<br/>";
			$body2.="Email: ".$main_email."\r\n<br/>";
			$body2.="Subject: ".$main_subject."\r\n<br/>";
			$body2.="Location: ".$main_location."\r\n<br/>";
			$body2.="Description: ".$main_description."\r\n<br/>";

			$body2.="<table id=\"times\">";
			$body2.="<thead><th>dates</th>";
					
						$count=1;
						while($count <=$max_col)  {
					
						$body2.="<th>time$count</th>";
						$count++;
					}
					
				
					
				$body2.="</thead><tr>";
				$bg_color="bgcolor=\"#6b9e74\"";
				$count=1;
				$header_count=0;
				$curr_row_num=0;
				$row_num=0;
				while($row= mysqli_fetch_array($times)){
					
					/*if(($count <($max_col-1)) && ($row_num != $row["row_num"]) ){
						$body2.="<td></td></tr>";
						$count=0;
					
					}else{*/
					
						if($row_num !=0 && ($row_num != $row["row_num"])){
							$body2.="</tr>";
							$count=1;
						}
						if(($row_num != $row["row_num"])){
							if($count< $max_col){
							$temp_count = $max_col-$count;
								while($temp_count< ($max_col)){
									$body2 .="<td></td>";
									$temp_count++;
								}
							}
							$body2 .= "</tr><tr>";
							$count=1;
						}
						
						$row_num = $row["row_num"];
						if($count ==1){
							$body2.= "<td>".$time[$header_count]."</td>";
							$header_count++;
						}	
						if($row["time"]=="0:00"){
							//echo "<td>".$row["time"]."</td>";
							$body2.= "<td></td>";
						}else{
						
							$query="select accept from submitted_times where time_id=".$row["tid"];
							$res_time_id= mysqli_query($con, $query);
							$accept= mysqli_result($res_time_id, 0, 'accept');
							if($accept){
								$bg_color="bgcolor=\"#6b9e74\"";
								
							}else{
								$bg_color="bgcolor=\"#e35b61\"";
							}
							$body2.= "<td $bg_color >".$row["time"]."</td>";
						
						
							
						
						}
						$count++;
					
						if($count >$max_col){
							$body2.="</tr>";
							$count=1;
						}
					//}
					
				
				}
				$body2.="</tr></table>";
				$body2.="\r\n<br/><a href='http://172.16.0.89/adknet/scheduler?id=$main_id&view=2&s=$random_string'>view your meeting</a>";
					
			mail($user_email,"submitted times",$body2, $headers);
			/*************************************************************************************************/
			$query="select * from email_list where confirmed = 0 and request_id=$main_id";
			//need to count to make sure if we do this
			//echo $query;
			$person_emails_res =mysqli_query($query);
			$person_count =mysql_num_rows($person_emails_res);
			$body="";
			if($person_count>0){
				$body="the following people have not responded to your meeting request\r\n<br/>";
				while($row= mysql_fetch_array($person_emails_res)){
					$final_email= $row['email'];
					$query = "select * from people where email='".$final_email."'";
					//echo $query;
					$res = mysqli_query($con, $query);
					$fname = mysqli_result($res,0, 'fname');
					$lname = mysqli_result($res,0, 'lname');
					
					$body.=$fname." ".$lname."\r\n<br/>";
					//echo $body;
				}
			}
			$body."<br/>";
			//need to mail that person x has repsonded use user string to get it
			$query ="select  email from email_list where random_string ='".$random_string."'";
			
			$res = mysqli_query($con, $query);
			$email2 = mysqli_result($res, 0);
			
			
			
			$query = "select * from people where email = '".$email2."'";
			echo $query;
			$res2 = mysqli_query($query);
			//go through the loop and email 
			$count = mysql_num_rows($res2);
			echo $count;
			if($count >0){
				while($row = mysqli_fetch_array($res2)){
				
					$body.=" ".$row['fname']." ".$row['lname']." has submitted their times for your meeting request";
					if($comments =="" || $comments ==null){
						
					}else{
						$body.="\r\n<br/>comments: ".$comments;
					}
					//echo $body;
					//need to add link to meeting to body
					$query="select random_num from request where id =$main_id";
					$res= mysqli_query($con, $query);
					$random_num = mysqli_result($res, 0, 'random_num');
					
					$body.="\r\n<br/><a href='http://172.16.0.89/adknet/scheduler?id=$main_id&view=$random_num'>view your meeting</a>";
					
					mail($email,$subject,$body, $headers);
				}
			}else{
					$body.="A person not in our system but on your meeting request has submitted their times for your meeting request";
				//	echo $body;
					mail($email2,$subject,$body, $headers);
			}
			
		
		
			
	}
	
	public function final_submit($time_id,$con){
		$monthArray = array("January"=>0, "February"=>1, "March"=>2, "April"=>3, "May"=>4, "June"=>5, "July"=>6, "August"=>7,"September"=>8,"October"=>9,"November"=>10,"December"=>11);
		
		$headers ="From:noreply@yourdomain.com"."\r\n";          
 	 	$headers .="MIME-Version: 1.0" . "\r\n";
	    $headers  .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$query="select time, date_id from times where tid=$time_id";
		$res =mysqli_query($con, $query);
		$time= mysqli_result($res, 0, 'time');
		$date_id= mysqli_result($res, 0, 'date_id');
		
		$query = "select * from request where id =$date_id";
		$res = mysqli_query($con, $query);
		$subject = "scheduleIT ";
		$subject .= mysqli_result($res,0,'subject');
		
		$query="select * from dates where request_id=$date_id";
		$res =mysqli_query($con, $query);
		$date= mysqli_result($res, 0, 'date');
		$request_id= mysqli_result($res, 0, 'request_id');
		
		
		$date_parts= explode(" ",$date);
		
		$query="select * from email_list where request_id=$date_id";
		
		$res= mysqli_query($query);
		$month_number = ($monthArray[$date_parts[1]]+1);
		if($month_number <10){
			$temp_month = "0".$month_number;
		}else{
			$temp_month = $month_number;
		}	
		$date1 = $date_parts[3]."".$temp_month.str_replace(",","",$date_parts[2]);
		//time == pm subtract 2 from time if am leave as is
		if(strpos($time, 'pm') !== FALSE){
			$time_parts = explode(":",$time);
			$time1 =($time_parts[0]+12)."".str_replace(":","",$time_parts[1]);
			$time1 =str_replace("pm","",$time1);
		}else{
			$time1 =str_replace(":","",$time);
			$time1 =str_replace("pm","",$time1);
		}
		
	
		while($row=mysqli_fetch_array($res)){
				
				
				$body="A meeting has been scheduled for you $date at $time or <a href='http://www.google.com/calendar/event?action=TEMPLATE&text=ScheduleIT Meeting Request&details=ScheduleIT Meeting Request&dates=".$date1."T".$time1."00/".$date1."T".($time1+100)."00&location=Gillette+Stadium'>click here</a> to add to your calendar.";
				mail($row['email'],$subject,$body, $headers);
		
		}
		
	}
	public function delete($id, $con){
	
		$query="update request set active =0 where id=$id";
		mysqli_query($query);
	
	
	}
	public function cancel($id, $con){
		$headers ="From:noreply@yourdomain.com"."\r\n";          
 	 	$headers .="MIME-Version: 1.0" . "\r\n";
	    $headers  .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		
		$query= "select * from request where id =$id";
		$res = mysqli_query($con, $query);
		
		$location = mysqli_result($res, 0, 'location');
		$name = mysqli_result($res, 0, 'name');
		$description = mysqli_result($res, 0, 'description');
		
		$body = "A meeting has been cancelled by $name\r\n<br/>";
		$body.="location: $location \r\n<br/>";
		$body.="description: $description \r\n<br/>";
		
		$query="select * from email_list where request_id=$id";
		$res =mysqli_query($query);
		while($row=mysqli_fetch_array($res)){
				
			mail($row['email'],"ScheduleIT meeting cancelled",$body,$headers);
		
		}
	
		$query="update request set active =0 where id=$id";
		mysqli_query($con, $query);
	}
	public function reminder($id, $con){
		$headers ="From:noreply@yourdomain.com"."\r\n";          
 	 	$headers .="MIME-Version: 1.0" . "\r\n";
	    $headers  .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		echo $id;
		$query="select * from email_list where id=$id";
		$res = mysqli_query($con, $query);
		$email = mysqli_result($res, 0, 'email');
		$random_string= mysqli_result($res, 0, 'random_string');
		$request_id=mysqli_result($res, 0, 'request_id');
		
			$body="A response is still needed from you for this meeting request. Follow the link to select the best time for you\n\r<a href='http://172.16.0.89/adknet/scheduler?id=$request_id&view=2&s=$random_string'>your meeting request</a>";
			mail($email,"ScheduleIT Meeting ",$body, $headers);
	}
	public function submit($text,$con,$id_string,$type){
		
		echo $type."this is type\r\n";
		$body="";
		//if id string is set then we update old query to reflect this. then send out email as usual
		if($id_string != "" || $id_string != null){
		
				echo "id string was not null after all";
				$query= "update request set active =0 where random_num =".$id_string;
				mysqli_query($con, $query);
				echo $query."<br/>";
				$body="A previous meeting request has been updated.<br/>";
		}
	
	
		
		$headers ="From:noreply@yourdomain.com"."\r\n";          
 	 	$headers .="MIME-Version: 1.0" . "\r\n";
	    $headers  .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
		$name = $_SESSION['name'];
		$email = $_SESSION['email'];
		$location = $_SESSION['location'];
		
		$subject .= $_SESSION['subject'];
		$description = $_SESSION['description'];
		$user_id = $_SESSION['user_id'];
		
		if($subject == null || $subject == ""){
			$subject ="ScheduleIT Meeting  test email";
		}
		
		$num = rand(1, 1000000);
		date_default_timezone_set("America/New_York");
		$datetime = date_create()->format('Y-m-d H:i:s');
		$query ="insert into request (description, name, email, location, subject, random_num,user_id, date_submitted) values('".addslashes($description)."','".addslashes($name)."','".addslashes($email)."','".addslashes($location)."','".addslashes($subject)."', $num, $user_id, '$datetime')";		
		echo $query;
		if($type!= "cc"){
			mysqli_query("$query", $con) or die(mysql_error());
		}
		$res = mysqli_query("select MAX(id) from request");
		//get the id that will be the foreign key for everything
		$id = mysqli_result($res, 0);
		
		
		//parse through days and save them
		$total_day=-1;
		//foreach($_SESSION as $sess){
		while ($sess1 = current($_SESSION)) {
			$substr =  key($_SESSION);
			
			if(substr($substr,0,3) == "day"){
			
				$total_day++;
				$query ="insert into dates(request_id, date) values($id,'".$_SESSION['day'.$total_day]."')";
				if($type!= "cc"){
					mysqli_query($con, $query);	
				}
			}
			if(substr($substr,0,3) == "row"){
				
				//now explode the vale at that session value by the -
				$arr = explode("-", substr($substr,3));
			
				//now save the parts
				$query="insert into times(date_id, row_num, time) values($id,$arr[0],'".addslashes($sess1)."')";
				if($type!= "cc"){
					mysqli_query($con, $query) or die(mysql_error());
				}
				echo $query."\n\r<br/>";
			}
			next($_SESSION);
		}
	
		//parse through emails and save them
		$arr = explode("\n", $text);
		
		foreach($arr as $a){
			//echo $a." inside the loop<br/>";
			//create unique string to place in db and email
			if($a != null || $a != ""){
				$random_string =substr(md5(rand()), 0, 7);
				$query ="insert into email_list(request_id, email, confirmed, random_string,email_type) values($id, '$a', 0, '$random_string','$type')";
				echo $query."\r\n";
				
				if($type=="cc"){
					$body="You have been carbon copied on this schedule request because one of the following peoples schedules pertains to you.\n\r<br/>";
					$query_name="select * from people where email='".$a."'";
					echo $query;
					$res_name = mysqli_query($con, $query_name);
					$num_people = mysql_num_rows($res_name);
					if($num_people> 0){
					
						$lname = mysqli_result($res_name, 0,'lname');
						$fname = mysqli_result($res_name, 0,'fname');
						$body .=$fname." ".$lname."\r\n<br/>";
						$body .="\r\n Location: $location \r\n<br/> Description: $description\r\n";
					
					}else{
					
						$body.="$a (name not available)";
					}
					
					
				}else{
					$body="You have a meeting request from\r\n<br/>Name: $name \r\n<br/> Email: $email\r\n<br/> Location: $location \r\n<br/> Description: $description\r\n<br/> Follow the link to select the best time for you\n\r<a href='http://172.16.0.89/adknet/scheduler?id=$id&view=2&s=$random_string'>your meeting request</a>";
				
				}
				
				mail($a,$subject,$body, $headers);
				mysqli_query($con, $query);
			}
		}
		
		//create link to form and save it
		
		$body="You have created a meeting request with the ScheduleIT Meeting \r\n to view updates or make changes follow the link below\n\r<a href='http://172.16.0.89/adknet/scheduler?id=$id&view=$num'>your meeting request</a>";
		mail($_SESSION['email'],$subject,$body,$headers);
		echo $_SESSION['email']." should be the email here<br/>";
		mail('your@email.com',$subject,$body,$headers);
		
	}
	
	public function search_times($time,$con){
		
		
		
		$query="SELECT time_value from time_increments where time_value LIKE '$time%' order by time_value asc";
		
		
		
		$res = mysqli_query($con, "$query") or die(mysql_error());
		
		$count=0;
		if($time==""){
			$data[$count]="";
		}else{
			while($row = mysqli_fetch_array($res)){
				$data[$count] =$row['time_value'];
				$count++;
			}
		
		}
		echo json_encode($data); 
	}
	
	public function search($letters,$con){
		
		$pieces = explode(";",trim($letters));
		$size = sizeOf($pieces);
		if($size >0){
			$letters = $pieces[$size-1];
		}else{
			$letters = $letters;
		}
		
		
		$query="SELECT email from people where lname LIKE '%$letters%' OR fname LIKE '%$letters%'";
		
		//echo $query;
		
		
		$res = mysqli_query($con, "$query") or die(mysql_error());
		$num_rows = mysql_num_rows($res);
		$count=0;
		if($letters ==""){
			$data[$count]="";
		}else{
			if($num_rows> 0){
				while($row = mysqli_fetch_array($res)){
					$data[$count] =$row['email'];
					$count++;
				}
			}else{
				$data[$count]="";
			}
		
		}
		echo json_encode($data); 
	}
}
?>