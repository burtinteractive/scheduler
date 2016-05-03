<?php
include_once("db_conn.php");

class user{
private $db;
private $conn;	
private $id= "";

	 public function __construct($userName) {
         	 $this->db= new db_conn();
         	$this->conn = $this->db->connect();
         	$this->verifyUser($userName);
         	
         }
  
	
   public function getCredentials($userId){
   
   			
   			
   			$query ="select user_name from user where id=$userId";
			
			
			$res = $this->db->select($query, $this->conn);
			$user_name= $this->db->mysqli_result($res,0, 'user_name');
   			
   			$query ="select * from people where email LIKE '%$user_name%'";
			
			
			$res = $this->db->select($query, $this->conn);
			
			$email= $this->db->mysqli_result($res, 0,'email');
   			$lname= $this->db->mysqli_result($res, 0,'lname');
   			$fname = $this->db->mysqli_result($res, 0,'fname');
   			return $email.":".$user_name.":".$fname.":".$lname;
   
   }
	
	public function verifyUser($userName){
			
			$query ="select * from people where email LIKE '%$userName%'";
			
			
			$res = $this->db->select($query, $this->conn);
			$email_id="";
			$user_name="";
			
			while($row = mysqli_fetch_array($res)){
				$email_id= $row['id'];
				
				
				$arr = explode("@",$row["email"]);
				if($userName ==$arr[0]){
					//check if user exist
					$query="select * from user where user_name='$userName'";
					$res2 = $this->db->select($query, $this->conn);
					
					$num= mysqli_num_rows($res2);
				
					if($num<= 0){
						
						//insert into user table with id;
						$query="insert into user(user_name, email_id) values('$userName',$email_id)";
						echo $query."<br/>";
						//not sure if seting variables will interfere with parsing session variables
						mysqli_query($this->conn, $query);
					}
					
				
				}
			
			}
			
			
			
			//check if ever logged in before. If not make entry into user table;
			
			
		
			//set session variables for users
			//
			
			//will return true if found.
			$query="select id from user where user_name ='$userName'";
			
			$res = mysqli_query($this->conn,$query);
			$this->id= $this->db->mysqli_result($res, 0,'id');
			
			$this->db->close($this->conn);
			return $this->id+"";
			
	}
	public function __toString(){
	
	
		return(string) $this->id;
		
	
	}
	
}
?>