<?php

include_once("config/db.php");
require_once 'Classes/PHPExcel.php';

// 學生儲值紀錄表。
set_time_limit(0);

$i = 0;
$j = 0;
$m = 0;

$cname        = trim($_GET['cname']);
$username     = trim($_GET['username']);
$room_strings = trim($_GET['room_numbers_kw']);

// 20200108 -- 增加日期
$kw_start = $_GET['kw_start'];
$kw_end   = $_GET['kw_end'];

if($username) $sql_kw.=" and username='".$get_username."'"; // 找學生
if($room_strings) $sql_kw.=" and CardID IN (SELECT id_card FROM room WHERE room_number = '{$room_strings}') ";
if($cname) $sql_kw.=" and member_id in (SELECT id FROM member WHERE cname = '{$cname}')";
if($kw_start && $kw_end) $sql_kw.=" and LEFT(`Time`,100) >= '".$kw_start." 00:00:00' AND LEFT(`Time`,100) <= '".$kw_end." 23:59:59'";  	//時間區間 改2

if($kw_start == '' AND $kw_end == '' AND $cname == '' AND $room_strings == '') {
	header("location: MemberEZCardRecord.php?betton_color=primary&error=1"); 
	exit();
}

// 20191231 -- 改寫
// $sqlMonitor="select * from EZCard_record WHERE LEFT(Time,100) >= '".$kw_start." 00:00:00' AND LEFT(Time,100) <= '".$kw_end." 23:59:59' and username='".$username."' order by Time desc";
$sqlMonitor = "SELECT * FROM EZCard_record WHERE 1 {$sql_kw} order by Time desc";
$stmtMonitor = $PDOLink->prepare($sqlMonitor);  
$stmtMonitor->execute();
$rowMonitor = $stmtMonitor->fetch();

if($rowMonitor)
{
	$filename=date("Ymd")."學生儲值紀錄匯出.xlsx";
	$xls = new PHPExcel();													
	$xls->getProperties()->setCreator($web_title)
						 ->setLastModifiedBy($web_title)
						 ->setTitle($web_title)
						 ->setSubject($web_title)
						 ->setDescription("")
						 ->setKeywords("")
						 ->setCategory("");
	$body_all="";
	$body_line="";
	$body_head=""; 

	$body_head=array("列印日期:".date("Ymd")."    學生付款記錄 ");	
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
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
	}

	$body_head=array(" 時間 "," 房號 "," 學號 "," 卡號 ","    姓名    ","  餘額  "," 備註 ");	
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
		$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    //自動行高
		$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
		$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平置中
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
		// $objActSheet->getColumnDimension( 'A')->setWidth(30);         //30寬
		// echo $get_name.">>".$body_head[$i];
	}//列首結束

	$rs = $PDOLink->query($sqlMonitor);
	$rs->setFetchMode(PDO::FETCH_ASSOC);
	$j++;
	$no=1;
	$l=1;
	$DataTypes2 = array('Stored' => '儲值','Refund' => '退費');
	$DataTypes3 = array('Stored' => '+','Refund' => '-');
	while($row=$rs->fetch())
	{

		$CartTotal = strlen($row[CardID]);
		$sn=$row[sn];
		$add_date=date("Y-m-d H:i:s",strtotime($row[Time]));
		$room_number=get_id($row[member_id],'member','room_strings');
		$username=get_id($row[member_id],'member','username');
		$id_card=get_card(str_pad($row[CardID],10,"0",STR_PAD_LEFT),'member','id_card');
		$cname=get_id($row[member_id],'member','cname');
		$price_total=$row[PayValue];	

		$item1=$add_date;
		$item2=$room_number;
		$item3=$username;
		$item4= " ".$id_card." ";
		$item5=$cname;
		$item6=$price_total; 
		$item7=$DataTypes2[$row[Sort]];

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
		// $sqlMonitorTotalData="select * from EZCard_record WHERE LEFT(Time,100) >= '".$kw_start." 00:00:00' AND LEFT(Time,100) <= '".$kw_end." 23:59:59' and username='".$username."' order by Time desc";
		// $rsTotal=$PDOLink->query($sqlMonitor);
		// $rownumTotal=$rsTotal->fetchcolumn();
		// $AddrownumTotal=$rownumTotal+3;
		$AddrownumTotal=$j+1;
		
		
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
			$get_name=$add_col.chr($get_c).$AddrownumTotal;
			$get_col=$add_col.chr($get_c);
			$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    //自動行高
			$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
			$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平置中
			$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
			// $objActSheet->getColumnDimension( 'A')->setWidth(30);         //30寬
			// echo $get_name.">>".$body_head[$i];
		}//列尾 
		
	$xls->getActiveSheet()->setTitle('AO匯出資料');
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
	header("location: MemberEZCardRecord.php?betton_color=primary&error=1"); 
	//print "<script>alert('沒有符合的資料匯出');</script>";
	$PDOLink = NULL;
	exit();
}

$PDOLink = NULL;
?>