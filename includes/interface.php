<?php
include_once("session.php");
include_once("email.php");
include_once("db_conn.php");
$session = new session();
$email = new email();
$page = $_POST['page'];

$db = new db_conn();

switch($page){

	case 'info':
		//do stuff here
		
	//	echo "should be variables now".$_POST['variables'];
		$session->setVariables($_POST['variables']);
		
	break;
	case 'dates':
		$session->setVariables($_POST['variables']);
	break;
	case 'times':
		$session->setVariables($_POST['variables']);
	break;
	
	case 'search':
		//sends db 
		$con =$db->connect();
		$db->search($_POST['letters'], $con);
	break;
	case 'search_times':
		//sends db 
		$con =$db->connect();
		$db->search_times($_POST['time'], $con);
	break;
	case 'delete':
		//sends db 
		$con =$db->connect();
		$db->delete($_POST['id'], $con);
	break;
	case 'cancel':
		//sends db 
		$con =$db->connect();
		$db->cancel($_POST['id'], $con);
	break;
	case 'remind':
		$con =$db->connect();
		$db->reminder($_POST['id'], $con);
		
	break;	
	case 'update':
		$con =$db->connect();
		$db->submit($_POST['list'], $con, $_POST['id_string'],$_POST['type']);
		
	break;	
	case 'user_submit':
		$con =$db->connect();
		//called when user recieves a schedule to fill out and hits submit
		$db->submit2($_POST['variables'], $con, $_POST['email'],$_POST['main_id'] ,$_POST['random_string'],$_POST['comments'] );
		
	break;	
	
	case 'final_invite':
		$con =$db->connect();
		
		$db->final_submit($_POST['time_id'], $con );
		
	break;	
}


?>