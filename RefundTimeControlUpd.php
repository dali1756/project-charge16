<?php 

include_once('config/db.php');

/* 宿舍退費時段PHP演算法

	255 max

	{00:00~00:00}{00:00~00:00}

	Time格式

	UI page

	長度： 
	13 or 18 一組
	26 兩組
	 
*/ 

function daysInmonth($year='',$month=''){
	if(empty($year)) $year = date('Y');
	if(empty($month)) $month = date('m');
	if (in_array($month, array(1, 3, 5, 7, 8, '01', '03', '05', '07', '08', 10, 12))) {  
            $text = '31';  		 //月大
	}elseif ($month == 2 || $month == '02'){  
		if ( ($year % 400 == 0) || ( ($year % 4 == 0) && ($year % 100 !== 0) ) ) {   //判断是否是闰年  
			$text = '29';        //闰年2月
		} else {  
			$text = '28';  		 //平年2月
		}  
	} else {  
		$text = '30';  			 //月小
	}
	
	return $text;
}

// refund_all, refund_edit
$act = $_POST[act]; 

// $c_code = "WIM($user_id,$i_username,$i_password,$i_idcard,$i_cname,$i_userclass,$i_publicCardName,$i_berth_number,$i_user_room_strings,$i_user_room_type,$newPriceDegree,$i_add_date,$i_TimeUpdated,$i_del_mark)";

if($act == "refund_new"){

	//echo "送出成功，我還沒做完";

	$StatusOpenPostOne = implode(" ", $_POST[status_open1]);
	$Year = date("Y"); 
	$OneMonth = $_POST[one_month];
	$OneDay = str_pad($_POST[one_day],2,"0",STR_PAD_LEFT);
	$one_time_a = $_POST[one_time_a]; 
	$one_time_b = $_POST[one_time_b];
	
	$StatusOpenPostTwo = implode(" ", $_POST[status_open2]);
	$two_time_c = $_POST[two_time_c];
	$two_time_d = $_POST[two_time_d];
	
	$da = date("w"); //今天星期幾？ (數字)

	$DatInmonth = daysInmonth($Year,$OneMonth);

	if($one_time_a <= $one_time_b){

		if( ($two_time_d >= $two_time_c) ){

			$Get_list_q="select id from refund_interval_setting where day='".$OneMonth.$OneDay."' ";
		    $Get_list_r = $PDOLink->prepare($Get_list_q);
		    $Get_list_r->execute(); 
		    $Get_rs = $Get_list_r->fetch();  
		    if(!$Get_rs){
		    	
				$AddVision = 1; 
				$FitTime_a = "{".$StatusOpenPostOne.$one_time_a."~".$one_time_b."}";
				$FitTime_b = "{".$StatusOpenPostTwo.$two_time_c."~".$two_time_d."}";
				$AddFileTime = $FitTime_a.$FitTime_b;

				$col="`day`,`time`,`vision`";
				$col_data="'".$OneMonth.$OneDay."','".$AddFileTime."','0'";
				$ins_q="insert into refund_interval_setting (".$col.") values (".$col_data.") ";  
				$PDOLink->exec($ins_q);  

				$refund_id=$PDOLink->lastInsertId();  
			
                $i_id = "".""."".$refund_id."".""."";  ; 
                $i_day = "".""."".$OneMonth.$OneDay."".""."";  ; 
                $i_time = ""."%"."".$AddFileTime.""."%"."";  ; 
                $i_vision = "".""."0".""."";  ; 

	   			$c_code = "WIU($i_id,$i_day,$i_time,$i_vision)"; 
	            $col2="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
	            $col_data2="'WEB','退費模式新增','".$c_code."','0','0','0','0',now()";
	            $ins_q2="insert into system_setting (".$col2.") values (".$col_data2.") ";
	            $PDOLink->exec($ins_q2); 

	            $PDOLink=null;
				header("location: RefundTimeControl.php?success=1");

		    } else {

		    	$GetDayTitle = $OneMonth."月".$OneDay."日";
			    $PDOLink=null;
				header("location: RefundTimeNew.php?error=2&GetDay=".$GetDayTitle."");

		    }


		} else {

			$PDOLink=null;
		    header("location: RefundTimeNew.php?error=1");

		}

	} else {

		$PDOLink=null;
	    header("location: RefundTimeNew.php?error=1");

	}

} elseif($act == "refund_edit") {

	$Id = $_POST[_id];	//Id 
	$Vision = $_POST[_vision];
	$Day = $_POST[_day];
	$DayCount = $_POST[_daycount];

	$StatusOpenPostOne = implode(" ", $_POST[status_open1]);
	$one_time_a = $_POST[one_time_a];
	$one_time_b = $_POST[one_time_b];
	
	$StatusOpenPostTwo = implode(" ", $_POST[status_open2]);
	$two_time_c = $_POST[two_time_c];
	$two_time_d = $_POST[two_time_d];

	$StatusOpenPostThone = implode(" ", $_POST[status_open3]);
	$two_time_e = $_POST[two_time_e];
	$two_time_f = $_POST[two_time_f];

	if( ($one_time_b >= $one_time_a) ){

		if( ($one_time_a && $one_time_b == "") || ($one_time_a == "") || ($one_time_b == "") ){

			$PDOLink=null;
			header("location: RefundTimeControlEdit.php?error=2&Id=".$Id."");
			exit();

		}

		//六、日
		if($Day > 5){ 

			$AddVision = $Vision+1;
			$FitTime_a = "{".$StatusOpenPostOne.$one_time_a."~".$one_time_b."}";
			$FitTime_b = "{".$StatusOpenPostTwo.$two_time_c."~".$two_time_d."}";
			$FitTime_c = "{".$StatusOpenPostThone.$two_time_e."~".$two_time_f."}";
			$AddFileTime = $FitTime_a.$FitTime_b.$FitTime_c;

			$upd_q1="UPDATE refund_interval_setting set 
		    vision='".$AddVision."',  
		    time='".$AddFileTime."'
			WHERE id='".$Id."'"; 
		    $stmt1 = $PDOLink->prepare($upd_q1);  
		    $stmt1->execute();

		     /* CUU refund_interval_setting, system_setting */ 
		     $i_id = "".""."".$Id."".""."";  ; 
		     $i_day = "".""."".$Day."".""."";  ; 
             $i_time = ""."%"."".$AddFileTime.""."%"."";  ; 
             $i_vision = "".""."".$AddVision."".""."";  ; 

             $c_code = "WUUvision=$i_vision, time=$i_time where id=$i_id";
   
             $col2="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
             $col_data2="'WEB','退費模式更新','".$c_code."','0','0','0','0',now()";
             $ins_q2="insert into system_setting (".$col2.") values (".$col_data2.") ";
             $PDOLink->exec($ins_q2); 
			
		     $PDOLink=null;
			 header("location: RefundTimeControl.php?success=1");
		 	 exit();

		//一～五 
		} elseif($Day <= 5) {

			// 指定
			if( ($DayCount == 2) || ($DayCount == 3) || ($DayCount == 4) ){


				$AddVision = $Vision+1;
				$FitTime_a = "{".$StatusOpenPostOne.$one_time_a."~".$one_time_b."}";
				$FitTime_b = "{".$StatusOpenPostTwo.$two_time_c."~".$two_time_d."}";
				$AddFileTime = $FitTime_a.$FitTime_b;

				$upd_q1="UPDATE refund_interval_setting set 
			    vision='".$AddVision."',  
			    time='".$AddFileTime."'
				WHERE id='".$Id."'"; 
			    $stmt1 = $PDOLink->prepare($upd_q1);  
			    $stmt1->execute();

			     /* CUU refund_interval_setting, system_setting */ 
			     $i_id = "".""."".$Id."".""."";  ; 
			     $i_day = "".""."".$Day."".""."";  ; 
	             $i_time = ""."%"."".$AddFileTime.""."%"."";  ; 
	             $i_vision = "".""."".$AddVision."".""."";  ; 

	             $c_code = "WUUvision=$i_vision, time=$i_time where id=$i_id";
	   
	             $col2="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
	             $col_data2="'WEB','退費模式更新','".$c_code."','0','0','0','0',now()";
	             $ins_q2="insert into system_setting (".$col2.") values (".$col_data2.") ";
	             $PDOLink->exec($ins_q2); 
				
				 $PDOLink=null;
				 header("location: RefundTimeControl.php?success=1");
		 	     exit();

				
		    } else {		

			 	$AddVision = $Vision+1;
				$FitTime_a = "{".$StatusOpenPostOne.$one_time_a."~".$one_time_b."}";
				$FitTime_b = "{".$StatusOpenPostTwo.$two_time_c."~".$two_time_d."}";
				$AddFileTime = $FitTime_a.$FitTime_b;

				$upd_q1="UPDATE refund_interval_setting set 
			    vision='".$AddVision."',  
			    time='".$AddFileTime."'
				WHERE id='".$Id."'"; 
			    $stmt1 = $PDOLink->prepare($upd_q1);  
			    $stmt1->execute();

			     /* CUU refund_interval_setting, system_setting */ 
			     $i_id = "".""."".$Id."".""."";  ; 
			     $i_day = "".""."".$Day."".""."";  ; 
	             $i_time = ""."%"."".$AddFileTime.""."%"."";  ; 
	             $i_vision = "".""."".$AddVision."".""."";  ; 

	             $c_code = "WUUvision=$i_vision, time=$i_time where id=$i_id";
	   
	             $col2="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
	             $col_data2="'WEB','退費模式更新','".$c_code."','0','0','0','0',now()";
	             $ins_q2="insert into system_setting (".$col2.") values (".$col_data2.") ";
	             $PDOLink->exec($ins_q2); 
				
				 $PDOLink=null;
				 header("location: RefundTimeControl.php?success=1");
		 	     exit();

	 	 	 }
		
		}
		
	} else {
		
		header("location: RefundTimeControl.php?error=1");
	}

}

?>