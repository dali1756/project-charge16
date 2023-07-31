<?php 
include_once('config/db.php'); 

$Admin = $_SESSION[admin_user][sn];	 // 管理員id
$AdminId = $_SESSION[admin_user][id];	 // 管理員id
$ipAddr = $_SERVER[REMOTE_ADDR];     // ip-addr
$act = $_GET[act]; 			 		 // 1 or 2 
$DataAll = $_POST[data_all]; 		 // 1 單一, 2 全部 
$id = $_POST[id];			 	     // id 
$status = $_POST[status];            // 狀態
$admin_id = $_POST[admin_id];        // 營繕組維修

/* 單間 1 */  
if($status == 1){
$room_number = $_POST[room_number];
$RoomNumberTypes = substr($room_number,0,1); 
$post_mode = $_POST[mode];
$price_elec_degree = $_POST[price_elec_degree];

$AdminList_q="select id from admin where id='".$Admin."'";
$AdminList_r = $PDOLink->prepare($AdminList_q); 
$AdminList_r->execute(); 
$AdminCode = $AdminList_r->fetch(); 	

$roomSystemList_q="select * from room where room_number='".$room_number."'";
$roomSystemList_r = $PDOLink->prepare($roomSystemList_q); 
$roomSystemList_r->execute();
$roomSystemCode = $roomSystemList_r->fetch(); 	
$SystemMode = $roomSystemCode[mode];   
$SystemAmonut = $roomSystemCode[amonut];   
$SystemStartPower = $roomSystemCode[Start_power]; 
$SystemEndPower = $roomSystemCode[End_power];
$SystemStartBalance = $roomSystemCode[Start_balance];
$SystemEndBalance = $roomSystemCode[End_balance]; 
$SystemPriceElecDegree = $roomSystemCode[price_elec_degree];

$mode = AOautoChangeMode($post_mode,$SystemMode,$SystemStartPower);

    $upd_q="update room set
    price_elec_degree='".$price_elec_degree."',
    mode='".$mode."'
    where 1 and id='".$id."'";
    $stmt = $PDOLink->prepare($upd_q);
    //$user_room_id=$stmt->lastInsertId();  
    $stmt->execute();
    
    /* 當有變更費率的時候 */
    if($price_elec_degree != $SystemPriceElecDegree){

        $col="`user_room_id`,`contact`,`admin_id`,`ip_addr`,`add_date`,`amonut`,`Start_power`,`End_power`,`Start_balance`,`End_balance`,`price_elec_degree`";
        $col_data=" '".$id."','單間更新','".$Admin."','".$ipAddr."',now(),'".$SystemAmonut."','".$SystemStartPower."','".$SystemEndPower."','".$SystemStartBalance."','".$SystemEndBalance."','".$price_elec_degree."'";
        $ins_q="insert into room_record (".$col.") values (".$col_data.") ";
        $PDOLink->exec($ins_q);  
    
    }

		/* C# update code post: 資料同步更新 */
		$v_id =  "".""."".$id."".""."";  ;
		$v1 =  "".""."".$price_elec_degree."".""."";  ;
		$v2 =  "".""."".$mode."".""."";  ;
		$v3 =  ""."%"."".$room_number.""."%"."";  ;
	    $c_code="WURprice_elec_degree=$v1,mode=$v2,room_number=$v3 where id=$v_id";
		$col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
		$col_data="'更新房間','Web','".$c_code."','0','0','0','0',now()";
		$ins_q="insert into system_setting (".$col.") values (".$col_data.") ";
		$PDOLink->exec($ins_q); 

		/* Web對硬體初始化 room initialization */
		$RoomNumber =  $room_number;
		$CreatedAtTime = date("Y-m-d H:i:s");

		/* 東華對硬體下命令初始化房間 */
		$roomKisokUpdateList_q="select center_id,meter_id from room where room_number='".$RoomNumber."'";
		$roomKisokUpdateList_r = $PDOLink->prepare($roomKisokUpdateList_q); 
		$roomKisokUpdateList_r->execute();
		$roomRKisokUpdates = $roomKisokUpdateList_r->fetch(); 	
		$KisokCenterID=str_pad($roomRKisokUpdates[center_id],2,"0",STR_PAD_LEFT);
		$KisokMMeterID=str_pad($roomRKisokUpdates[meter_id],2,"0",STR_PAD_LEFT);
		$InsertMemberUpdateC_code = "<^$KisokCenterID$KisokMMeterID>";

		$initializationCol="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
		$initializationCol_data="'房間更新','Web','".$InsertMemberUpdateC_code."','0','0','0','0',now()";
		$initializationIns_q="insert into system_setting (".$initializationCol.") values (".$initializationCol_data.") ";
		$PDOLink->exec($initializationIns_q);  

		/* log_list系統LOG紀錄 */
        $AddContent = " 房間號碼:$RoomNumber::收費設定:$mode::用電費率:$price_elec_degree::更新方式:$DataAll::管理員:$AdminId";
		$log_col="`content`,`data_type`,`add_date`";
		$log_col_data="'".$AddContent."','1',now() ";
		$log_ins_q="insert into log_list (".$log_col.") values (".$log_col_data.") ";
		$PDOLink->exec($log_ins_q); 

        $PDOLink = NULL;
        if($admin_id){
            header("location: RoomControlElectrician.php?success=1"); 
        } else {
            header("location: RoomControl2.php?success=1"); 
        } 

/* 全部 2 */
} elseif($status == 'update_price_elec_degree'){ 
    $price_elec_degree = $_POST[price_elec_degree_all];    

    $upd_q="update room set price_elec_degree='".$price_elec_degree."' "; 
    $stmt = $PDOLink->prepare($upd_q);
    $stmt->execute();

    /* C# update code post: */
    $v2 =  "".""."".$price_elec_degree."".""."";  ;
    $c_code="WURprice_elec_degree=$v2";
    $col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data="'全部設定費率','Web','".$c_code."','0','0','1','1',now()";
    $ins_q="insert into system_setting (".$col.") values (".$col_data.") ";
    $PDOLink->exec($ins_q); 

    /* log_list系統LOG紀錄 */
    $AddContent = " 用電費率:$price_elec_degree::更新方式:$DataAll::管理員:$AdminId ";
    $log_col="`content`,`data_type`,`add_date`";
    $log_col_data="'".$AddContent."','1',now() ";
    $log_ins_q="insert into log_list (".$log_col.") values (".$log_col_data.") ";
    $PDOLink->exec($log_ins_q); 

    /* 全部初始化 */
    $c_code_end = "<!2>";
    $col_end="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data_end="'全部設定費率','Web','".$c_code_end."','0','0','1','1',now()";
    $ins_q_end="insert into system_setting (".$col_end.") values (".$col_data_end.") ";
    $PDOLink->exec($ins_q_end);  

    $PDOLink = NULL;
    header("location: RoomControl2.php?success=2");   

} elseif($status == 'update_mode') {
    $post_mode = $_POST[mode_all];

    $upd_q="update room set mode='".$post_mode."' "; 
    $stmt = $PDOLink->prepare($upd_q);
    $stmt->execute();

    /* C# update code post: */
    $v2 =  "".""."".$post_mode."".""."";  ;
    $c_code="WURmode=$v2";
    $col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data="'全部設定模式','Web','".$c_code."','0','0','1','1',now()";
    $ins_q="insert into system_setting (".$col.") values (".$col_data.") ";
    $PDOLink->exec($ins_q); 

    /* log_list系統LOG紀錄 */
    $AddContent = " 模式設定:$post_mode::更新方式:$DataAll::管理員:$AdminId ";
    $log_col="`content`,`data_type`,`add_date`";
    $log_col_data="'".$AddContent."','1',now() ";
    $log_ins_q="insert into log_list (".$log_col.") values (".$log_col_data.") ";
    $PDOLink->exec($log_ins_q); 

    /* 全部初始化 */  
    $c_code_end = "<!2>";
    $col_end="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data_end="'全部設定模式','Web','".$c_code_end."','0','0','1','1',now()";
    $ins_q_end="insert into system_setting (".$col_end.") values (".$col_data_end.") ";
    $PDOLink->exec($ins_q_end);  

    $PDOLink = NULL;
    header("location: RoomControl2.php?success=2"); 

}
?>