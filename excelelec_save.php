<?php include_once("config/db.php"); ?>
<?php require_once 'Classes/PHPExcel.php';?>
<?php
set_time_limit(0);

// $kw_start = $_GET['s_date'];
// $kw_end   = $_GET['e_date'];

$e_date   = $_GET['kw_end'];
$kw_start = $_GET['kw_start'];
$kw_end   = $e_date != '' ? date('Y-m-d', strtotime($e_date.'+1 day')) : '';
$cname    = trim(''.$_GET['cname']);
$user_room_strings = trim(''.$_GET['user_room_strings']);

/* 學生ID */
// if($cname != '') {
	// $member_list_q4 = "select id from member where cname = '".$cname."' ";
	// $member_list_r4 = $PDOLink->prepare($member_list_q4); 
	// $member_list_r4->execute();
	// $member_row4 = $member_list_r4->fetch();         
	// $MemverID = $member_row4['id'];	
// }

/* 房間ID */ 
// if($user_room_strings != '') {
	// $room_list_q4 = "select id from room where room_number = '".$user_room_strings."' ";
	// $room_list_r4 = $PDOLink->prepare($room_list_q4); 
	// $room_list_r4->execute();
	// $room_row4 = $room_list_r4->fetch();                             
	// $RoomID=$room_row4['id'];
// }

// if($MemverID){
	// $sqlMonitor = "SELECT * FROM power_record WHERE LEFT(update_date,100) >= '".$kw_start." 00:00:00' AND LEFT(update_date,100) <= '".$kw_end." 23:59:59' AND price_total > 0 AND member_id= '".$MemverID."' order by update_date desc ";  
// } elseif($RoomID){
	// $sqlMonitor = "SELECT * FROM power_record WHERE LEFT(update_date,100) >= '".$kw_start." 00:00:00' AND LEFT(update_date,100) <= '".$kw_end." 23:59:59' AND price_total > 0 AND room_id = '".$RoomID."' order by update_date desc ";  
// } else {
	// header("location: MemberPowerRecord.php?betton_color=primary&error=1"); 
	// exit();
// }

// 20191231 -- 改寫
$sqlMonitor = "SELECT * FROM power_record WHERE 1 AND price_total > 0 ";

if($kw_start == '' AND $kw_end == '' AND $cname == '' AND $user_room_strings == '') {
	header("location: MemberPowerRecord.php?betton_color=primary&error=1"); 
	exit();
}
	
if($kw_start != '' and $kw_end != '') {	
	$sqlMonitor .= " AND update_date BETWEEN '{$kw_start}' AND '{$kw_end}'";
} else if($kw_start != '') {
	$sqlMonitor .= " AND update_date >= '{$kw_start}'";
} else if($kw_end != '') {
	$sqlMonitor .= " AND update_date <= '{$kw_end}'";
} 

if($cname != '') {
	$sqlMonitor .= " AND member_id in (SELECT id FROM member WHERE cname = '".$cname."') ";
}

if($user_room_strings != '') {
	$sqlMonitor .= " AND room_id in (SELECT id FROM room WHERE room_number = '".$user_room_strings."') ";
}

$sqlMonitor .= " ORDER BY update_date DESC ";

// echo $cname."<><>".$user_room_strings;
// echo $kw_start."<><>".$kw_end;
// echo $sqlMonitor; exit;

$stmtMonitor = $PDOLink->prepare($sqlMonitor);  
$stmtMonitor->execute();
$rowMonitor = $stmtMonitor->fetch();
$i=0; $j=0;
if($rowMonitor)
{
	$filename=date("Ymd")."房間用電紀錄.xlsx";
	$xls = new PHPExcel();													
	$xls->getProperties()->setCreator($web_title)->setLastModifiedBy($web_title)->setTitle($web_title)->setSubject($web_title)->setDescription("")->setKeywords("")->setCategory("");
	$body_all="";
	$body_line="";
	$body_head="";
	$body_head=array("列印日期:".date("Ymd")."    房間用電記錄 ");	// 12 欄
	$head_num=count($body_head);
	$j++; 
	for($i=0,$i2=0,$i3=0;$i<$head_num;$i++,$i2++)
	{
		$i2=($i2%26);
		$get_c=65+$i2;
		if($i>=26)
		{
			$i3=floor($i/26);
			$add_col=chr(64+$i3);
		}
		else
		{
			$add_col="";                           
		}
		$get_name=$add_col.chr($get_c)."1";  
		$get_col=$add_col.chr($get_c);
		$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    
		$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		
		$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$xls->getActiveSheet()->mergeCells('A1:I1'); 
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
	}
	/* 列首start */
	$body_head=array("  房號  ","  學號  ","  卡號  ","    姓名    ","   班級   ","  用電時間  ","  開始度數~結束度數  ","  總金額  ","  備註  ");	
	$head_num=count($body_head);
	$j++; 
	for($i=0,$i2=0,$i3=0;$i<$head_num;$i++,$i2++)
	{
		$i2=($i2%26);
		$get_c=65+$i2;
		if($i>=26)
		{
			$i3=floor($i/26);
			$add_col=chr(64+$i3);
		}
		else
		{
			$add_col="";
		}
		$get_name=$add_col.chr($get_c)."2";
		$get_col=$add_col.chr($get_c);
		$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    
		$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		
		$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
	}
	/* 列首End */

	$rs = $PDOLink->query($sqlMonitor);
	$rs->setFetchMode(PDO::FETCH_ASSOC);
	$j++; $no=1; $l=1;
	while($row=$rs->fetch())
	{

    // if(round($row[price_total],0) > 0){

		$sn=$row[id];
		$user_room_strings=get_room_id($row[room_id],'room','room_number');
		$username=" ".get_id($row[member_id],'member','username')." ";  
		$id_card=" ".get_id($row[member_id],'member','id_card')." ";   
		$cname=get_id($row[member_id],'member','cname');
		$user_class=get_id($row[member_id],'member','user_class');
		$start_date=date("Y-m-d H:i",strtotime($row[start_date])); 
		$end_date=date("Y-m-d H:i",strtotime($row[end_date])); 
		$price_total=round($row[price_total],1);
		$add_dates = date("Y-m-d",strtotime($row[start_date])); 
		$end_dates_string = array('1995-01-01 00:00' => '空調使用中');
		$update_date = date("Y-m-d H:i",strtotime($row[update_date]));

		$item1=$user_room_strings;
		$item2=$username;
		$item3=$id_card;
		$item4=$cname;
		$item5=$user_class;
		$item6=$start_date."~".$update_date;
		$item7=$row[Start_power]."~".$row[End_power];
		$item8=$price_total;
		$item9;

	//}

		for($i=0,$k=1,$i2=0,$i3=0;$i<$head_num;$i++,$i2++,$k++)
		{
			$item="item".$k;
			$i2=($i2%26);
			$get_c=65+$i2;
			if($i>=26)
			{
				$i3=floor($i/26);
				$add_col=chr(64+$i3);
			}
			else
			{
				$add_col="";
			}
			$get_name=$add_col.chr($get_c).$j;
			$get_col=$add_col.chr($get_c);
			if(in_array($body_head[$i],$txt_col))
			{
				$num_txt = new PHPExcel_RichText();
				$num_txt->createText($$item); 
			}
			else
			{
				$num_txt=$$item;
			}
			if(in_array($body_head[$i],$left_col))
				$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			else
				$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$xls->setActiveSheetIndex(0)->setCellValue($get_name,$num_txt);
				//echo $get_name.">>".$num_txt.">>";
		}
		$j++;
		$no++;
	}

		/* 列尾資料 */
		$FooterDataOne = $no+2;
	
		$body_head=array(" 經手人 ","      ","  主辦出納  ","      ","   主辦會計   ","      ","   機關長官   ","      ");	
		$head_num=count($body_head);
		$j++; 
		for($i=0,$i2=0,$i3=0;$i<$head_num;$i++,$i2++)
		{
			$i2=($i2%26);
			$get_c=65+$i2;
			if($i>=26)
			{
				$i3=floor($i/26);
				$add_col=chr(64+$i3);
			}
			else
			{
				$add_col="";
			}
			$get_name=$add_col.chr($get_c).$FooterDataOne;
			$get_col=$add_col.chr($get_c);
			$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    
			$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		
			$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
		}//列尾 

	$xls->getActiveSheet()->setTitle("匯出日期".date("Y-m-d"));
	$xls->setActiveSheetIndex(0); 
	ob_end_clean();  //Excel匯出避免亂碼bug 

	/* Excel2007匯出 */
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
	$objWriter->save('php://output');

}
else
{ 
	//print "<script>alert('沒有符合的資料匯出');</script>";
	header("location: MemberPowerRecord.php?betton_color=primary&error=1"); 
	//print "<script>alert('沒有符合的資料匯出');</script>";
	$PDOLink = NULL;
	exit();
}

$PDOLink = NULL;
?>