<?php

include_once("config/db.php");
require_once 'Classes/PHPExcel.php';

set_time_limit(0);

$rs_data3;

$sql_kw    = "";

$sel_year_all  = $_GET['sel_year_all'];

if($sel_year_all != '') {
	$sql_kw = " AND YEAR(date_time) = '{$sel_year_all}' ";
}										

$sql = "SELECT YEAR(date_time) as 'year', MONTH(date_time) as 'month', 
		SUM(PayValue) as 'amount', postive 
		FROM `icer_pay` WHERE 1 {$sql_kw} GROUP BY YEAR(date_time), 
		MONTH(date_time), postive ORDER BY date_time";
$rs  = $PDOLink->Query($sql);
$rs_tmp = $rs->fetchAll();

foreach($rs_tmp as $v) {
	
	$yy  = $v['year'];
	$mm  = $v['month'];
	$pos = $v['postive'];
	$amt = $v['amount'];
	
	if($pos == '1') {
		$rs_data3[$yy][$mm]['amt'] += $amt;
	}
	
	if($pos == '0') {
		$rs_data3[$yy][$mm]['ref'] += $amt;
	}
}

if(isset($rs_data3))
{
	$filename=date("Ymd")."年度查詢報表.xlsx";
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
	$body_head=array(" 月份 ", " 總儲總金額 ", " 退費總金額 ", " 小計 ", " 備註 ");	
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
	// $DataTypes2 = array('Stored' => '儲值','Refund' => '退費');
	// $DataTypes3 = array('Stored' => '+','Refund' => '-');
	// while($row=$rs->fetch())
	foreach($rs_data3 as $y => $row)
	{
		foreach($row as $m => $v) 
		{
			$amt = $v['amt'] == '' ? 0 : $v['amt'];
			$ref = $v['ref'] == '' ? 0 : $v['ref'];
			$sum = $amt - $ref;
			
			$item1 = str_pad($y,2,"0",STR_PAD_LEFT).'-'.str_pad($m,2,"0",STR_PAD_LEFT);
			$item2 = $amt;
			$item3 = $ref;
			$item4 = $sum;
			// $item6 = $refd; 
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
				$xls->getActiveSheet()->getColumnDimension($get_col)->setWidth(11);
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
	header("location: report.php?get_tab=year&sel_year_all={$sel_year_all}&error=1"); 
	//print "<script>alert('沒有符合的資料匯出');</script>";
	$PDOLink = NULL;
	exit();
}

$PDOLink = NULL;
?>