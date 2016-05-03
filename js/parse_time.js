var the_time_string="";
			var current_index=0;
			var num_pattern =/[0-9]/;
			var nan_pattern =/[:-]/i;
			var letter_pattern=/[apm]/
			var pattern1= /[bcdefghijklnoqrstuvwxyz]/i
			var num_stack = new Array();
			var nan_stack= new Array();
			var time_stack = new Array();
			var letter_stack= new Array();
			var lastChar= "";
			var new_index=0;
			var dash_count=0;
			var colon_count=0;
			var number_pair=0;
			var letter_pair =0;
			var first_number=0;
			var second_number=0;
			var first_am_pm="";
			var second_am_pm="";
			//if false use pm;
			var am_pm_flag = false;
			//keeps track of which number we are on
			var valid_time_count=0;
				
				
			/**************************************************
			*
			*
			***************************************************/
			//checks to find next value and returns new index for substring
			function findNext(substring, type){
			
				
				//letter
				if(type ==1){
					//checks if string is just one character
					if(substring.length == 1){
						
						return 1;
					}else{
						//looking for next letter
						sub_parts = substring.split('');
						for(j=0;j< sub_parts.length;j++){
							//matches if a letter
							if(sub_parts[j].match(letter_pattern)){
								//letter_stack.push("m");
								return(j+1);
							}else{
								//called on single numbers check to see if pm or am is set if not defaults to pm
								am_pm_flag = false;
								return 0;
							}
		
						}
					}
					
				}else{
					//check for next non space character
					
					sub_parts = substring.split('');
						for(j=0;i< sub_parts.length;j++){
							//matches if a letter
							if(sub_parts[j].match(letter_pattern)){
								letter_stack.push("m");
								return(j+1);
							}

					
						}
				}
			
				
				
			}
			
			
			function pushNext(type,arr){
				var return_int;
				
				if(type==0){
					time_stack.push("0");
					time_stack.push("0");
				}
				else if(type=="a"){
				
					//return_int=findNext(the_time_string.substring(current_index),1);
					if(arr=="t"){
						time_stack.push("a");
						time_stack.push("m");
						letter_stack.pop();
						letter_stack.pop();
					}else{
						letter_stack.push("a");
						letter_stack.push("m");
					}
				}else{
					
					if(arr=="t"){
						time_stack.push("p");
						time_stack.push("m");
						letter_stack.pop();
						letter_stack.pop();
					}else{
						letter_stack.push("a");
						letter_stack.push("m");
					}
				}
			
			}
			/**************************************************
			*Takes in name of stack and pops value off and places in a temp var
			*Then 
			***************************************************/
			//take in stack name and sees top value
			function peek(currStack){
				
				tempvar = currStack.pop();
				currStack.push(tempvar);
				return tempvar;
				
			}
			
			
			/**************************************************
			*Resets all the variables back like they were before
			*function ran
			***************************************************/
			function militaryTime(num1, num2){
				var mil_array= new Array();
					temp_num = Number(num1+""+num2);
					var mil_flag = true;
					if(first_number ==0){
						//set first num to compare to the second number
						first_number = temp_num;		
					}
					
					if(number_pair ==2 && colon_count==1 && dash_count ==1){
						second_number =temp_num;
					}
					
					if(temp_num >12){
						temp_num = temp_num-12;
					
					
						mil_flag=false;
						if(temp_num <10){
						
							
							num1 = 0;
							num2 = temp_num;
						}else{
							temp_string = temp_num+"";
							num_parts = temp_string.split('');
							num1 =num_parts[0];
							num2 = num_parts[1];
							
						}
						
						am_pm_flag = false;
						mil_array.push(num2);
						mil_array.push(num1);
						//return mil_array;
						
					}else{
						if(temp_num>=7 && temp_num < 12){
							//makes it am
							if(first_number != 0 && second_number ==0)
							am_pm_flag = true;
						}else if(temp_num==12){
							
							am_pm_flag = false;
						}else{
							am_pm_flag = false;
						}
						mil_array.push(num2);
						mil_array.push(num1);
						//return mil_array;
					}
					
					
				if(mil_flag){
					if(time_stack.toString().indexOf("p,m")>=0){
						if(second_number != 0 && first_number> second_number && (second_number>=7 && second_number < 12)){
							am_pm_flag=false;
						}
						else if(second_number != 0 && first_number< second_number && (second_number>=7 && second_number < 12)){
							am_pm_flag=true;
					
						}else {
							am_pm_flag=false;
						}
					}else if(time_stack.toString().indexOf("a,m")>=0){
						if(second_number != 0 && first_number> second_number&& ((first_number-2) >second_number)){
							am_pm_flag=false;
						}
						else {
							am_pm_flag=true;
						}
					}
					}else{
						mil_flag=true;
						am_pm_flag=false;
				}
				return mil_array;
			}
			/**************************************************
			*pops all the stacks used to create time_string
			*takes in a n= number l=letter
			***************************************************/
			function popStacks(number, letter){

				if(number == "n"){
					if(num_stack.length==2){
						temp = num_stack.pop();	
						if((number_pair==0 || number_pair ==2) && num_stack.length==0 && second_number==0){
							 var temp_arr =militaryTime(num_stack.pop(),temp);
							time_stack.push(temp_arr.pop())
							time_stack.push(temp_arr.pop())
							
						}else{
						
							time_stack.push(num_stack.pop())
							
							time_stack.push(temp);
						
						}
					
						number_pair++;
					}else if(num_stack.length==1){
						 var temp_arr =militaryTime(0,num_stack.pop());
						time_stack.push(temp_arr.pop())
						time_stack.push(temp_arr.pop())
						number_pair++;
						if(peek(time_stack)!= ":" && colon_count< 2 && (number_pair ==1 || number_pair ==3)){
							time_stack.push(":");
							colon_count++;
						}	
						if(colon_count<=2 && (number_pair ==1 || number_pair ==3)){
							pushNext(0);
							number_pair++;
						}
						
						
							
					}else{
						if( number_pair ==1 || number_pair ==3 ){
							if(peek(time_stack)!= ":"){
								time_stack.push(":");
								colon_count++;
							}
							pushNext(0);
							number_pair++;
						
						}else if(number_pair ==2 || number_pair ==4){
							//do nothing
						
						}
							
					}
				
				}else{
				
				
				}
				
				
				if(letter =="l"){
					
					if((colon_count==0 && number_pair==1)||(colon_count==1 && number_pair==3 )){
						time_stack.push(":");
						pushNext(0);
						colon_count++;
						number_pair++;				
					}	
					if(letter_stack.length==1){	
					
						if(peek(letter_stack)=="m"){
							if(am_pm_flag){
								pushNext("a","t");
							}else{
								pushNext("p","t");
							}
							
						}else{
							temp = letter_stack.pop();	
							temp2 = letter_stack.pop();
							time_stack.push(temp2);	
							time_stack.push(temp);	
						}
						
					}else if(letter_stack.length==2){
						
						temp= letter_stack.pop();
						if(peek(letter_stack)=="a"){
							pushNext("a","t");
						}else if(peek(letter_stack)=="p"){
							pushNext("p","t");
						}else{
							if(am_pm_flag){
								pushNext("a","t");
							}else{
								pushNext("p","t");
							}
						}
						letter_stack.pop();
					}else{
						
							if(am_pm_flag && letter_pair <2){
								pushNext("a","t");
							}else if(!(am_pm_flag) && letter_pair <2){
								pushNext("p","t");
							}
					}
					
					letter_pair++;
				}else{
				
				
				
				}
				
			}
			/**************************************************
			*Resets all the variables back like they were before
			*function ran
			***************************************************/
			function resetVars(){
				num_stack.length = 0;
				nan_stack.length = 0;
				time_stack.length = 0;
				letter_stack.length = 0;
				lastChar= "";
				new_index=0;
				dash_count=0;
				colon_count=0;
				number_pair=0;
				letter_pair =0;
				valid_time_count=0;
				first_number=0;
				second_number =0;
				second_am_pm="";
				first_am_pm="";
				the_time_string="";
				var am_pm_flag = false;
				letter_stack.pop();
				letter_stack.pop();
			}
			function isLastChar(num, length){
			
				if((num+1) == length){
					return true;
				}else{
					return false;
				}
			
			}
			/**************************************************
			*
			*
			***************************************************/
			//run clean up function to remove extra space and clean up format;
			function cleanupString(temp){
				//remove duplicates of values that shouldn't be there
				temp_stack = new Array()
				sub_parts = temp.split('');
				curr = "";
				last ="";
				for(i=0;i< sub_parts.length;i++){
					//check spaces first
					
					if(letter_pair ==2){
						break;
					}
					if(number_pair == 4){
						break;
					}
					
					if(sub_parts[i]== " " && peek(temp_stack)==" "){
					
					}else if(sub_parts[i]== ":" && peek(temp_stack)==":"){
					}else if(sub_parts[i]== "-" && peek(temp_stack)=="-"){
					}else if(sub_parts[i]== "a" && peek(temp_stack)=="a"){
					}else if(sub_parts[i]== "p" && peek(temp_stack)=="p"){
					}else if(sub_parts[i]== "m" && peek(temp_stack)=="m"){
					}else{
						temp_stack.push(sub_parts[i]);
					}
				
				
				}
				temp_string="";
				for(i=0;i< temp_stack.length;i++){
					temp_string = temp_string + temp_stack[i];
				}
				
				return temp_string;
			}
			
			
			
				
			function parseTime(el){
				
				if(el.value.length ==0){
					
					//el.value = final_time;
					//resetVars();
					return ;
				}
					the_time_string=el.value;
					var timeString = el.value;
					
				if(timeString.length==1){
					if(timeString.match(num_pattern)){
							
							if(Number(el.value)>=7 && Number(el.value)<12){
								el.value= "0"+timeString+":00am";
								return "0"+timeString+":00am";
							}else{
								el.value= "0"+timeString+":00pm";
								return "0"+timeString+":00pm";
							}
							
					}else{	
						alert("you do not have any numbers in your time");
						resetVars();
						return false;
					}	
				}	
				timeString = timeString.replace(/[.]/gi,":");
				timeString=cleanupString(timeString);	
					
					
					var time_string ="";
					
					
					
					if(timeString.match(pattern1)=== null){
						timeString = $.trim(timeString);
						time_parts = timeString.split('');
						var v="";
						
						
						if(time_parts[0]==":" || time_parts[0]=="-"){
						
							alert("time cannot start with a : or a - try putting some numbers at the start.");
							resetVars();
							return false;
						}
						
						for(i=0; i<time_parts.length;i++){
							current_index=i;
							v = time_parts[i];
									 if(v.match(num_pattern)){
								//checks if digit is last part
								 if(!(isLastChar(i, timeString.length))){
									if(v>2 && time_parts[i+1].match(num_pattern )&& num_stack.length ==0 &&(number_pair == 0 || (dash_count ==1 && colon_count ==1))){
									
										alert("you do not have a valid time format try starting the time with a 0, 1 or a 2 ");
										resetVars();
										return false;
									}
								 }
								
								//if already two numbers and next one is a number send up alert
								if(num_stack.length==2){
									alert("you have some funkiness going on with your time format. Way to many numbers in a row.");
									resetVars();
									return false;
								}else{
									//not a first number pair then they must come after colon.
									if((number_pair ==1 && colon_count ==1 && num_stack.length == 0 )||(number_pair ==3 && colon_count ==2 && num_stack.length == 0 )){
									
											if(v >5){
												alert("the number " + v + "  is way to large to be a valid time value in the minute slot. We corrected it for you.");
												temp = v-5;
												v = v- temp;
											}
											
									}
									
									if((num_stack.length+1)==2 && (number_pair == 0 || (dash_count ==1 && colon_count ==1)) ){
										//just send current value and do a peek to verify digit
										// var temp_arr = new Array();
										 var temp_arr =militaryTime(num_stack.pop(), v);
											num_stack.push(temp_arr.pop());
											num_stack.push(temp_arr.pop());
									}else{
										num_stack.push(v);
									}	
									
									
									
								
								}
							 }else if(v.match(nan_pattern)){
								
									//only 2 dashes allowed. If more found throw and error.
									if(v.match(/[:]/)){
										if(colon_count < 2){
											//
											
											//popStacks("n","l");
											
											
											if(num_stack.length ==2){
											
												var temp = num_stack.pop();	
												var temp2 = num_stack.pop();	
												number_pair++;
												time_stack.push(temp2);
											
												time_stack.push(temp);
												time_stack.push(v)
												colon_count++;
											}
										
											if(num_stack.length ==1){
											
												time_stack.push("0");
												number_pair++;
												time_stack.push(num_stack.pop());
												time_stack.push(v)
												colon_count++;
											} 
											if(isLastChar(i, timeString.length)){
												
												pushNext(0);
												number_pair++;
												if(am_pm_flag){
													pushNext("a","t");
												}else{
													pushNext("p","t");
												}
												//letter_stack.pop();
												//letter_stack.pop();
												letter_pair++;
											
											}
											
										}else{
											alert("way to many colons in this time format");
											resetVars();
											return false;
										}
										
									}else if(v.match(/[-]/)){
										
										if(isLastChar(i, timeString.length)){
											break;
											
											
										}
										//will only get here once. 
										if(dash_count == 1){
											alert("You have way too many dashes in one of your time formats");
											resetVars();
											return false;
										}else{
												
												
												//only two number pair posibilities on a dash 1 or 2
												if(number_pair ==2){
													
													if(letter_stack.length==2){
														popStacks(0,"l");
														
													
													}else if(letter_pair ==1){
													
													}else if(letter_pair ==2){
														//break;
													}else{
														if(am_pm_flag){
															pushNext("a","t");
														}else{
															pushNext("a","t");
														}
														
														letter_pair++;
													}
												
												
												}else{
														popStacks("n","l");
													}
												time_stack.push("-");
												dash_count++;
												
										}	
										
									}
							
							}else if(v.match(letter_pattern)){
										if(isLastChar(i, timeString.length)){
											
												if(v=="a" || "p"){
													letter_stack.push(v);
													letter_stack.push("m");
												}else{
													//== m
													pushNext("a","l");
												}
												//letter_pair++;
												break;
										}else{
											tempint = findNext(timeString.substring((i+1)),1);
											//increment the new i placement
											if(tempint ==0){
													if(v=="a" || "p"){
													letter_stack.push(v);
													letter_stack.push("m");
												}else{
													//== m
													pushNext("a","l");
												}
												//letter_pair++;
											}else{
												if(v=="a" || "p"){
													letter_stack.push(v);
													letter_stack.push("m");
												}
												popStacks("n","l");
											}
											i= i+ tempint;
										
											
										}
							
							}
							
						}//end of for loop
						
							if(num_stack.length>0 || letter_stack.length>0){
								popStacks("n","l");
							}
							var final_time = "";
							for(k=0; k< time_stack.length;k++){
								final_time= final_time + time_stack[k];
							}
							var final_arr = final_time.split("-");
							
							temp = final_arr[0];
							temp2= final_arr[1];
							if(temp2 != null){
							if(Number(first_number) > Number(second_number) &&((temp2.indexOf("am")>=0 && temp.indexOf("am")>=0)||(temp2.indexOf("pm")>=0 && temp.indexOf("pm")>=0))){
								//alert("you may want to reorder you time.");
								
								final_time = temp2+"-"+temp;
							}else{
								final_time = temp+"-"+temp2;
							}
							}else{
								
							}
							
							
							el.value = final_time;
							resetVars();
					
						
					}else{
						alert("you have a character that has no business being in a time format");
					}
			
			}