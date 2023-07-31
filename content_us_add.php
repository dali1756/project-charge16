<?php 
include_once("config/db.php");
include("phpmailer/PHPMailerAutoload.php"); 			

	//if(preg_match("/^09[0-9]{8}$/", $phone)){

			//$UserId = $_POST[user_id];
			$myallsport = implode ("，", $_POST[host_type]);
			$host_other = $_POST[host_other];

			$myallsport2 = implode("，", $_POST[room_type]);
			$room_other = $_POST[room_other];

			$data_type = $_POST[data_type];
			$NowTime = date("Y-m-d H:i:s");

			$room_number = $_POST[room_number];
			$username_number = $_POST[username_number];
			$title = $_POST[title];
			$phone = $_POST[phone];
			$email = $_POST[email];


if($myallsport or $host_other or $myallsport2 or $room_other or $data_type){

			//if($email){	

				/*  Hotmail */  
				$mail= new PHPMailer(); 											//建立新物件    
				$mail->IsSMTP(); 													//設定使用SMTP方式寄信   
				mb_internal_encoding('UTF-8');
				$mail->SMTPAuth = true; 											//設定SMTP需要驗證   
				$mail->Host = "smtp.gmail.com"; 					    			//設定SMTP主機, smtp.gmail.com , mail.aotech.com.tw, smtp.mailgun.org, smtp-mail.outlook.com   
				$mail->Port = 587; 													//設定SMTP埠位，預設為25埠   
				//$mail->CharSet = "big5"; 											//設定郵件編碼    
				$mail->Username = "ao.barrysu88@gmail.com"; 			    		//設定驗證帳號, barry@aotech.com.tw, andy952737@gmail.com
				$mail->Password = "wfuhmgflcmjctjmp"; 									//設定驗證密碼, gmail: fbevvcqrssvnhzte
				$mail->From = "ao.barrysu88@gmail.com"; 				    		//設定寄件者信箱      
				$mail->FromName = "barry"; 								    		//設定寄件者姓名   
				$mail->Subject = "【東華智慧管理系統】學校客服"; 					  //設定郵件標題   
				$mail->Body = "<hr>日期：".$NowTime."<br>宿舍房號：".$room_number."<br>儲值主機操作：".$myallsport."<br>其他說明：".$host_other."<br>房內卡機使用：".$myallsport2."<br>其他說明：".$room_other."<br>"; 				    									   //設定郵件內容 
				$mail->IsHTML(true); 									   			//設定郵件內容為HTML   	 		
				
				$mail->AddAddress("a120216363@gmail.com","浩軒"); 		    
				$mail->AddAddress("ao.patty887@gmail.com","Patty"); 	 	    
				$mail->AddAddress("vivi@aotech.com.tw","佳怡");       		    
				$mail->AddAddress("barry@aotech.com.tw","Barry");

				if(!$mail->Send()) {   
					
					header("location: content_us.php?error=2"); 
					exit();

				}

			//}

			try {
			     $col="`title`,`username_number`,`room_number`,`phone`,`contact`,`email`,`status`,`host_type`,`host_other`,`room_type`,`room_other`,`user_id`,`data_type`,`add_date`,`del_mark`";
			     $col_data="'".$title."','".$username_number."','".$room_number."','".$phone."','--','".$email."','Y','".$myallsport."','".$host_other."','".$myallsport2."','".$room_other."','1','1','".$NowTime."','0'";
			     $ins_q="insert into content_us (".$col.") values (".$col_data.") ";
			     $PDOLink->exec($ins_q); 
			    
			    header("location: content_us.php?success=1");
		    }

			catch(PDOException $e) {
			    echo $ins_q . "<br>" . $e->getMessage();
			}


} else { 

	header("location: content_us.php?error=3"); 
	exit();

}

$PDOLink=null;
?>