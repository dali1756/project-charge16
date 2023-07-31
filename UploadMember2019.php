<?php
	include_once("config/db.php");  
	require_once('Excel/PHPExcleReader/Classes/PHPExcel.php');
	require_once('Excel/PHPExcleReader/Classes/PHPExcel/IOFactory.php');

	$room_max     = 6; 
	$room_num_arr = array();

	$Import_TmpFile = $_FILES['link1']['tmp_name'];
	$Import_NameFile = $_FILES['link1']['name'];  
	$file = $Import_TmpFile;
	
    try {
        
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        

    } catch(Exception $e) {   
        
        die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());

    }

    /* Excel匯入欄位順序：A房號, B學號, C班級, D姓名, E內卡卡號, F預設金額 */
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);   // $key => $col
	
	// 改寫 -- 20200122
	foreach($sheetData as $col) {
		
		// 避免存空值,略過第一欄
		if($col[A] && mb_strlen($col[A],"Big5") == strlen($col[A])) {

			// 房號
			$Room = trim($col[A]); 
			$room_num_arr[$Room] += 1;
		}
	}
	
	foreach($room_num_arr as $k => $v) 
	{	
		$RoomTotalSQL = "SELECT count(*) FROM member WHERE room_strings = '{$k}' ";
		$RoomTotalRs  = $PDOLink->query($RoomTotalSQL);
		$RoomTotalRowNum = $RoomTotalRs->fetchcolumn();  
		$AddRoomTotalRowNum = $RoomTotalRowNum + $v;
		
		if($AddRoomTotalRowNum <= $room_max) {
			
			// 

		} else {

			header("location: admin_users.php?success=3");
			
			return;
		}
	}
	
    foreach($sheetData as $col){

      //避免存空值,略過第一欄
      if($col[A] && mb_strlen($col[A],"Big5") == strlen($col[A])){

          /* 房號 */
          $Room = trim($col[A]);
          $RoomNumberTypes = substr($Room,0,1);
       
          /* 學號 */   
          $Username = trim($col[B]);       

          /* 密碼 */ 
          $SubstrPwd = "88888"; //substr($Username,-5);      

          /* 班級 */
          $UserClass = trim($col[C]);          

          /* 姓名 */
          $Name = trim($col[D]);
                                        
          /* 內卡卡號 */
          $IdCard = trim($col[E]);  
          $Card = str_pad(trim($col[E]),10,"0",STR_PAD_LEFT);

          /* 預設金額 */
          $DefaultPrice = 0; //$col[F];

          /* 其他欄位 */
          $i_date = date("Y-m-d H:i:s");
          $BerthNumber = 0;       

          /* 特殊狀況 */
          $PublicIdCardValue =  $Name; //mb_substr($Cname,4,7,'utf8');  
          
          if($PublicIdCardValue != '公用卡'){  
            $publicCardName= '學生';  
          } else {
            $publicCardName = '公用卡'; 
            $Name = $Room.$col[D]; 
          } 
    
          if($PublicIdCardValue == '公用卡'){ 

                $PublicIfIdCard = 'NDHU';
                $PublicCardRoomKeywordTotalSQL="select username from member where username LIKE '%".$PublicIfIdCard."%' order by username desc "; 
                $PublicCardRoomKeyword_r = $PDOLink->prepare($PublicCardRoomKeywordTotalSQL); 
                $PublicCardRoomKeyword_r->execute();
                $PublicCardRoomKeyword_row = $PublicCardRoomKeyword_r->fetch();  
                $NewPublicUsername = $PublicCardRoomKeyword_row[username];  
                
                 if($NewPublicUsername){    
                
                    $NewPublicUsernameRoom = substr($NewPublicUsername,0,4);   // PU -> DHYS 
                    $NewPublicUsernameNumber = substr($NewPublicUsername,4,7); // 000001 
                    $NewPublicUsernameNumberAdd = (int)$NewPublicUsernameNumber+1;
                    $NewPublicUsernameNumbers = str_pad($NewPublicUsernameNumberAdd,6,"0",STR_PAD_LEFT);
                    $Username = $NewPublicUsernameRoom.$NewPublicUsernameNumbers; 
                    $SubstrPwd = substr($Username,-5); 
                    //$HexIdCard = str_pad(base_convert($id_card,16,10),10,"0",STR_PAD_LEFT);   // 16進位轉10進位
                    //$HexIdCard = $id_card;                                                  // 16進位轉10進位
                
                 } else {
                 
                    $NewPublicUsernameRoom = 'NDHU';   
                    $NewPublicUsernameNumber = '000001';  
                    $Username = $NewPublicUsernameRoom.$NewPublicUsernameNumber; 
                    $SubstrPwd = substr($Username,-5); 
                
                 }  

          }

          if($IdCard){

              /* 比對學號+卡號(唯一值) */
              $user_q="select id from member where username='".$Username."' or id_card='".$Card."' ";
              $user_r = $PDOLink->prepare($user_q);  
              $user_r->execute();
              $userRow = $user_r->fetch();   
              $UserID = $userRow[id]; 

          } else {

              /* 比對學號(唯一值) */
              $user_q="select id from member where username='".$Username."' ";
              $user_r = $PDOLink->prepare($user_q);  
              $user_r->execute();
              $userRow = $user_r->fetch();  
              $UserID = $userRow[id];

          }

          /* Update */
          if($UserID){

              // 比對房間人數是否小於6才可以Insert
              // $RoomTotalSQL="select count(*) from member where room_strings = '".$Room."' ";
              // $RoomTotalRs=$PDOLink->query($RoomTotalSQL);
              // $RoomTotalRowNum=$RoomTotalRs->fetchcolumn();     
              // $AddRoomTotalRowNum = $RoomTotalRowNum+1;              
              // if($AddRoomTotalRowNum < 7){

                $upd_q="update member set
                del_mark='0',
                cname='".$Name."',
                id_card='".$Card."',
                room_strings='".$Room."',
                berth_number='".$BerthNumber."',
                room_type='".$RoomNumberTypes."',
                user_class='".$UserClass."'
                where id='".$UserID."'";
                $stmt = $PDOLink->prepare($upd_q);   
                $stmt->execute();

                /* 學生更新 */
                MemberMachineIF2($RoomNumberTypes,$Room,$i_date,$UserID,$Name,$Card,$UserClass,$Room,$BerthNumber);

                /* LogList 處理 */  
                // $content="學號:".$Username."；更新成功";
                // $col="`content`,`data_type`,`add_date`";
                // $col_data="'".$content."','1',now() ";
                // $ins_q="insert into log_list (".$col.") values (".$col_data.") ";
                // $PDOLink->exec($ins_q);

              // }

          /* Insert */    
          } else {
 
              // 比對房間人數是否小於6才可以Insert
              // $RoomTotalSQL="select count(*) from member where room_strings = '".$Room."' ";
              // $RoomTotalRs=$PDOLink->query($RoomTotalSQL);
              // $RoomTotalRowNum=$RoomTotalRs->fetchcolumn();  
              // $AddRoomTotalRowNum = $RoomTotalRowNum+1;                 
              // if($AddRoomTotalRowNum < 7){

                    $col="`username`,`password`,`id_card`,`cname`,`user_class`,`publicCardName`,`berth_number`,`room_strings`,`room_type`,`balance`,`add_date`,`TimeUpdated`,`del_mark`";
                    $col_data="'".$Username."',password('".$SubstrPwd."'),'".$Card."','".$Name."','".$UserClass."','".$publicCardName."','".$BerthNumber."','".$Room."','".$RoomNumberTypes."','".$DefaultPrice."',now(),now(),'0'";
                    $ins_q="insert into member (".$col.") values (".$col_data.") ";  
                    $PDOLink->exec($ins_q);

                    $GetUserInsertId=$PDOLink->lastInsertId();   

                    /* Machine kiosk Member insert  */  
                    MachineMemberInsert2($GetUserInsertId,$i_date,$Username,$SubstrPwd,$Card,$Name,$UserClass,$publicCardName,$BerthNumber,$Room,$RoomNumberTypes,$DefaultPrice);
            
                    /* 單間初始化房間 */
                    // RoomTypeMachineIF($RoomNumberTypes,$Room,$i_date);

                    /* LogList */  
                    // $content="學號:".$Username."；新增成功";
                    // $col="`content`,`data_type`,`add_date`";
                    // $col_data="'".$content."','1',now() ";
                    // $ins_q="insert into log_list (".$col.") values (".$col_data.") ";
                    // $PDOLink->exec($ins_q);

              // }

          }

      }
     
   } 

    /* 全部初始化 */
    $c_code_end = "<!1>";
    $col_end="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data_end="'全部初始化','Web','".$c_code_end."','0','0','0','0',now()";
    $ins_q_end="insert into system_setting (".$col_end.") values (".$col_data_end.") ";
    $PDOLink->exec($ins_q_end);    

   // exit();
   // $MysqliConn->close();  
   $PDOLink=null;
   
   header("location: admin_users.php?success=1");
?>