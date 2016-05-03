<?php

class session{
	
	public $variableArray = "";
	 public function __construct() {
         	session_start();
         }
  
	
	public function setVariables($arr){
			
			$count =0;
			
		//loop through session array and if contains prefix delete all in array and pop values over into new
		//array and set Session array = to new array after clearing it then append new values		
		$temp_array;
		$temp_flag = false;
		if(strstr($arr[0],"day")){
			foreach ($_SESSION as $key => $value) {
				
			 	//check to see if vaules in arr == a key already in session. 
				foreach($arr as $a){ 
				 	if(strstr(substr($a, 0, 7),$key)|| strstr($key,"day" )){
			 			//echo substr($a, 0, 7)." it is here\r\n";
			 			$temp_flag = true;
			 		}else{
			 			$temp_array[$key]= $value;
			 		}
			 	
			 	
				 }
			}
	
			//if true erase session and refill with temp and then populate like normal
			if($temp_flag){
					//echo "I guess we are here deleting everything";
					session_unset(); 
					session_destroy();
					session_start();
				foreach ($temp_array as $key => $value) {
					
					$_SESSION[$key] = $value;
			
				}
			}
		}
		for($i=0;$i < sizeof($arr);$i++){
			$temp = explode(";", $arr[$i]);
			echo $temp[0]." this is temp - ".$temp[1]."<br/>";
			if($temp[1]=="" || $temp[1] == null){
				
				$_SESSION[$temp[0]]= $temp[0];
			}else{
				
				$_SESSION[$temp[0]]= $temp[1];
			}
			
			$count++;
			if(($i+1) >= sizeof($arr)){
				$_SESSION["total_".$temp[0]] =$count;
			}
		}
		
		
	}
	
	public function killSession(){
	
		session_destroy();
	}
	public function startSession(){
	
			session_start();
	}
}
?>