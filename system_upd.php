<?php include_once('config/db.php'); ?>
<?php 

$id = $_POST[id];
$price_max = $_POST[price_max];
$price_start_date = $_POST[price_start_date];
$price_end_date = $_POST[price_end_date];
$contact = $_POST[contact];

// 20200103 -- 改寫
if($contact != '') {
	
	$U_S_upd_q = "UPDATE system_info SET contact='".$contact."' WHERE 1 AND sn='1' ";
 	$U_S_stmt  = $PDOLink->prepare($U_S_upd_q);  
 	$U_S_stmt->execute();
	
	/* log_list系統LOG紀錄 */
	$AddContent = " 活動公告:$contact ";
    $log_col="`content`,`data_type`,`add_date`";
    $log_col_data="'".$AddContent."','1',now() ";
    $log_ins_q="insert into log_list (".$log_col.") values (".$log_col_data.") ";
    $PDOLink->exec($log_ins_q); 

	$PDOLink = NULL;

	header("location: notice.php?success=1"); exit;
}


if($id){
 
	/* C# update code post: */ 
	$v_id =  "".""."".$id."".""."";  ;
	$v1 =  ""."%"."".$price_start_date.""."%"."";  ;
	$v2 =  ""."%"."".$price_end_date.""."%"."";  ;
	$v3 =  ""."%"."".$contact.""."%"."";  ;
	$v4 =  ""."%"."".$price_max.""."%"."";  ;
	$U_S_title =  ""."%"."合創大學"."%"."";  ; 
	// $U_S_upd_q="update system_info set price_start_date='".$price_start_date."',price_end_date='".$price_end_date."',price_max='".$price_max."',contact='".$contact."' where 1 and sn='1' ";
	$U_S_upd_q="update system_info set price_start_date='".$price_start_date."',price_end_date='".$price_end_date."',price_max='".$price_max."' where 1 and sn='1' ";
 	$U_S_stmt = $PDOLink->prepare($U_S_upd_q);  
 	$U_S_stmt->execute();
 	$U_S_CodeUpdate1 = "WUIprice_start_date=$v1,price_end_date=$v2,contact=$v3, price_max=$v4 where sn=$v_id";  
	$U_S_col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
	$U_S_col_c="'Web','更新系統','".$U_S_CodeUpdate1."','0','0','0','0',now()";
	$U_S_ins="INSERT INTO system_setting (".$U_S_col.") values (".$U_S_col_c.") ";
	$PDOLink->exec($U_S_ins);  	

	/* log_list系統LOG紀錄 */
	$AddContent = " 儲值最高上限:$price_max ";
    $log_col="`content`,`data_type`,`add_date`";
    $log_col_data="'".$AddContent."','1',now() ";
    $log_ins_q="insert into log_list (".$log_col.") values (".$log_col_data.") ";
    $PDOLink->exec($log_ins_q); 

	$PDOLink = NULL;

	header("location: system.php?success=1"); 

} else {

	header("location: system.php?error=1"); 

}

?>