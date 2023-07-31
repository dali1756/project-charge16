<?php

include_once("config/db.php");
require_once 'Classes/PHPExcel.php';

set_time_limit(0);

$rs_data2;

$sql_kw    = "";

$sel_year_start  = $_GET['sel_year_start'];
$sel_year_end    = $_GET['sel_year_end'];
$sel_month_start = $_GET['sel_month_start'];
$sel_month_end   = $_GET['sel_month_end'];

if($sel_year_start != '' & $sel_month_start != '') {
	
	$qry_date = date('Y-m-d H:i:s', strtotime("{$sel_year_start}-{$sel_month_start}-01 00:00:00"));
	
	$sql_kw .= " AND date_time >= '{$qry_date}' ";
	
} else {
	if($sel_year_start != '') {
		$sql_kw .= " AND YEAR(date_time) >= {$sel_year_start} ";
	} 
	
	if($sel_month_start != '') {
		$sql_kw .= " AND MONTH(date_time) >= {$sel_month_start} ";
	}												
}
	
if($sel_year_end != '' & $sel_month_end != '') {
	
	$qry_date = date('Y-m-d H:i:s', strtotime("{$sel_year_end}-{$sel_month_end}-01 00:00:00 +1 month"));
	
	$sql_kw .= " AND date_time < '{$qry_date}' ";
	
} else {
	if($sel_year_end != '') {
		$sql_kw .= " AND YEAR(date_time) <= {$sel_year_end} ";
	} 
	
	if($sel_month_end != '') {
		$sql_kw .= " AND MONTH(date_time) <= {$sel_month_end} ";
	}												
}

$sql = "SELECT YEAR(date_time) as 'year', MONTH(date_time) as 'month', 
		DAY(date_time) as 'day', SUM(PayValue) as 'amount', postive 
		FROM `icer_pay` WHERE 1 {$sql_kw} GROUP BY YEAR(date_time), 
		MONTH(date_time), DAY(date_time), postive ORDER BY date_time ";
		// "LIMIT " . ($page-1) * $pagesize .", ".$pagesize;
$rs  = $PDOLink->Query($sql);
$rs_tmp = $rs->fetchAll();

foreach($rs_tmp as $v) {
	
	$yy  = $v['year'];
	$mm  = $v['month'];
	$dd  = $v['day'];
	$pos = $v['postive'];
	$amt = $v['amount'];
	
	if($pos == '1') {
		$rs_data2[$yy][$mm][$dd]['amt'] += $amt;
	}
	
	if($pos == '0') {
		$rs_data2[$yy][$mm][$dd]['ref'] += $amt;
	}
}

if(isset($rs_data2))
{
	$filename=date("Ymd")."月份查詢報表.xlsx";
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
	// $body_head=array(" 日期 "," 位置 "," 設備 "," 交易卡號 "," 交易金額 "," 退費 ");	
	$body_head=array(" 日期 ", " 總儲金額 ", " 退費金額 ", " 小計 ", " 備註 ");	
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

	// $rs = $PDOLink->query($sqlMonitor);
	// $rs->setFetchMode(PDO::FETCH_ASSOC);
	$j++;
	$no=1;
	$l=1;
	$DataTypes2 = array('Stored' => '儲值','Refund' => '退費');
	$DataTypes3 = array('Stored' => '+','Refund' => '-');
	// while($row=$rs->fetch())
	foreach($rs_data2 as $y => $outer)
	{
		foreach($outer as $m => $inner) 
		{
			foreach($inner as $d => $row) 
			{
				
				$amt = $row['amt'] == '' ? 0 : $row['amt'];
				$ref = $row['ref'] == '' ? 0 : $row['ref'];
				$sum = $amt - $ref;

				$item1 = $y.'-'.str_pad($m,2,"0",STR_PAD_LEFT).'-'.str_pad($d,2,"0",STR_PAD_LEFT);
				$item2 = $amt;
				$item3 = $ref;
				$item4 = $sum;
				// $item5 = $ref;
				// $item6 = $sum; 
				// $item7 = $row['SavedValue'];

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
		}
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
			// $xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
			if($get_col == 'D') {
				$xls->getActiveSheet()->getColumnDimension($get_col)->setWidth(80);
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
	header("location: report.php?get_tab=month&sel_year_start={$sel_year_start}&sel_month_start={$sel_month_start}&sel_year_end={$sel_year_end}&sel_month_end={$sel_month_end}&error=1");
	//print "<script>alert('沒有符合的資料匯出');</script>";
	$PDOLink = NULL;
	exit();
}

$PDOLink = NULL;
?>