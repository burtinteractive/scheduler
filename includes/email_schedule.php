<?php
	//pulls in emails and divides them up into groupsun
	if(!$update){	
		include_once("includes/db_conn.php");
		$dbc = new db_conn();
		$con = $dbc->connect();
	}else{
		$con = $dbc->connect();
		$query = "select id from request where random_num =".$_GET['view'];
		
		$res_id  =$dbc->select($query, $con);
		$request_id = mysql_result($res_id, 0);
		
		$query= "select * from email_list where email_type='send' and request_id=".$request_id;
		
		$res_emails  =$dbc->select($query, $con);
		$emails = "";
		while($row=mysql_fetch_array($res_emails)){
		
			$emails .= $row['email'].";";
		}
		
		$query= "select * from email_list where email_type='cc' and request_id=".$request_id;
		
		$res_emails_cc  =$dbc->select($query, $con);
		$emails_cc = "";
		while($row=mysql_fetch_array($res_emails_cc)){
		
			$emails_cc .= $row['email'].";";
			
		}
		
	
	}	
?>

			<div class="row">
					<div class="large-12 columns">
						<div class="header"><a class="toggler" id="toggle4">+ </a> Add emails</div>
					</div>
			</div>
			<div class="step4 row" style="display:none;">		
				<div class="small-12 large-12 columns">
					<img src="images/info.jpg" style="float:right;width:20px;" title="send emails from the list below or the To: and CC: input field">
					To:<input type="text"  id="curr_email"  value='<?php if($update && $emails != ""){ echo $emails;  } ?>'> <br/>
					CC:<input type="text"  id="cc_email"  value='<?php if($update && $emails_cc != ""){ echo $emails_cc;  } ?>'><!--<a id="add_email" style="float:right;"><button>add +</button></a>--><br/>
					<div class="" id="curr_email_list"></div>
					
				</div>	
				<!--<div class="small-12 large-12 columns">
						list of emails:<br/><textarea id="final_email_list" name="final_email_list"><?php if($update && $emails != ""){ echo $emails;  } ?></textarea>
					</div>-->
					<div class="small-12 large-12 columns">
					Populate emails from department list below<br/><br/>
					</div>
					<div class="small-12 large-12 columns">
					<?php if($update){ ?>
					<button id="update">Update</button> <button id="clear_button">Clear</button>
					
					<?php }else{ ?>
					<button id="send">Send</button> <button id="clear_button">Clear</button>
					
					<?php } ?>
					
					</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<?php 
					
					$query="select* from people order by department asc";
					$list =$dbc->makeDept($query, $con);
					echo $list;
					?>
				</div>
			
			</div>
			<script type="text/javascript">
				/*function addEmail(email){
					$("#final_email_list").val($("#final_email_list").val()+""+email+"\n");
					
				}*/
				function addEmail(email){
					$("#curr_email").val($("#curr_email").val()+""+email+"\n");
					
				}
			</script>
			