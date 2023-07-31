<?php

include_once("config/db.php");
require_once 'Classes/PHPExcel.php';

set_time_limit(0);

$mc_data;

$sql_kw    = "";

$sel_dev   = $_GET['sel_dev'];
$kw_start  = $_GET['kw_start'];
$kw_end    = $_GET['kw_end'];

if($sel_dev != '') $sql_kw .= " AND seat_id = '{$sel_dev}' ";

if($kw_start) {
	$s_date = date('Y-m-d H:i:s', strtotime($kw_start));
	$sql_kw.= " AND start_time >= '{$s_date}' ";
}

if($kw_end) {
	$e_date = date('Y-m-d H:i:s', strtotime($kw_end.'+1 day'));
	$sql_kw.= " AND start_time < '{$e_date}' ";
}

// 時間範圍限當天 -- 20200506
// if((strtotime($kw_start)) != (strtotime($kw_end))) {
	
	// $kw_start = date('Y-m-d');
	// $kw_end   = date('Y-m-d');
	
	// echo "<script> location.replace('MemberEZCardRecord.php?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&sel_num={$sel_num}&kw_start={$kw_start}&kw_end={$kw_end}&get_tab={$get_tab}&error=3')</script>";
	// exit;
// }


$status_map = array('1'  => '正常使用',
					'2'  => '有退費',
					'3'  => '切免費',
					'4'  => '切停用', 
					'-1' => '放棄退費');

$sql = "SELECT *, (SELECT `number` FROM seat WHERE id = u.seat_id LIMIT 0, 1) as 'parking_space',
		TRUNCATE(prepaid * rate, 0) as 'prepay', 
		(onpeek_amount * onpeek_rate) as 'onpeek_fee', 
		(offpeek_amount * offpeek_rate) as 'offpeek_fee', 
		TIMEDIFF(end_time, start_time) as 'timediff' FROM `usage_history` u 
		WHERE 1 {$sql_kw} ORDER BY start_time DESC";
		
$rs = $PDOLink->prepare($sql); 
$rs->execute();
$data = $rs->fetchAll();

if($data)
{
	$filename=date("Ymd")."付款紀錄報表.xlsx";
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

	$body_head=array("列印日期:".date("Ymd"));	
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

	// $body_head=array(" 時間 "," 房號 "," 學號 "," 卡號 ","    姓名    ","  餘額  "," 備註 ");	
	$body_head=array(" 交易日期 "," 車位號碼 "," 卡號 "," 充電時間 "," 一般時段度數 "," 離峰時段度數 "," 付款金額 "," 退費金額 "," 狀態 ");
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
		// $xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
		if($get_col == 'B') {
			$xls->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		} else {
			$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
		}
		$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平置中
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
		// $objActSheet->getColumnDimension( 'A')->setWidth(30);         //30寬
		// echo $get_name.">>".$body_head[$i];
	}//列首結束

	// $rs = $PDOLink->query($sqlMonitor);
	// $rs->setFetchMode(PDO::FETCH_ASSOC);
	$j++;
	$no=1;
	$l=1;
	// $DataTypes2 = array('Stored' => '儲值','Refund' => '退費');
	// $DataTypes3 = array('Stored' => '+','Refund' => '-');	
	
	// while($row=$rs->fetch())
	foreach($data as $v)
	{
		
		$fee = 0;
		$ref = 0;
		
		$prepay; $onpeek_fee; $offpeek_fee;
		
		$prepay      = $v['prepay'];
		$onpeek_fee  = $v['onpeek_fee'];
		$offpeek_fee = $v['offpeek_fee'];
		
		$status = $v['status'];
		$str_sts = $status_map[$status];
		
		switch($status) {
			
			case 1:
			case -1:
				$fee = $prepay;
				$ref = 0;
				break;
			case 2:
				$fee = $onpeek_fee + $offpeek_fee;
				// $ref = $prepay - $onpeek_fee - $offpeek_fee;
				$ref = $prepay - $fee;
				break;
			case 3:
			case 4:
				$fee = 0;
				$ref = 0;
				break;
			default:
				break;										
		}

		$item1 = $v['start_time'];
		$item2 = $v['parking_space'];
		$item3 = $v['id_card'];
		$item4 = $v['timediff'];
		$item5 = $v['onpeek_amount'];
		$item6 = $v['offpeek_amount'];
		$item7 = ceil($fee);
		$item8 = floor($ref);
		$item9 = $str_sts;

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
			
			if($get_col == 'B') {
				$xls->getActiveSheet()->getColumnDimension($get_col)->setWidth(30);
			} else {
				$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
			}
			
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
	header("location: MemberEZCardRecord.php?get_tab=day&cardnum={$cardnum}&sel_build={$sel_build}&sel_level={$sel_level}&kw_start={$kw_start}&kw_end={$kw_end}&error=1");
	//print "<script>alert('沒有符合的資料匯出');</script>";
	$PDOLink = NULL;
	exit();
}

$PDOLink = NULL;
?>