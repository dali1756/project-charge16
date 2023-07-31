<?php

include_once("config/db.php");
require_once 'Classes/PHPExcel.php';

set_time_limit(0);

$mc_data;

$rs_data1;

$sql_kw    = "";

$kw_start  = $_GET['kw_start'];
$kw_end    = $_GET['kw_end'];
// $sel_build = $_GET['sel_build'];
// $sel_level = $_GET['sel_level'];
// $sel_dev   = $_GET['sel_dev'];

// $cardnum   = trim($_GET['cardnum']);

// $sql = "SELECT mac, title, wash_machine, clothes_dryer FROM `wash_machine`";
// $rs = $PDOLink->prepare($sql);
// $rs->execute();
// $rs_data = $rs->fetchAll();

// foreach($rs_data as $v) {
	// $mc_data[$v['mac']] = $v;
// }

// 查詢條件 -- 20200205
// $b_opt; $l_opt; $p_opt;

// $def_opt  = "請選擇";
// $wash_str = "洗衣機";
// $s_option = "<option value='%s' %s>%s</option>";

// $building = array($def_opt, "沁月莊", "沁月一莊", "沁月二莊", 
							// "行雲莊", "行雲一莊", "行雲二莊",
							// "迎曦莊", "迎曦一莊", "迎曦二莊");
				   
// $level    = array($def_opt, "一樓", "二樓", "三樓", "四樓");

// $proc     = array($def_opt, $wash_str, "烘衣機");

// $compare = ($sel_dev == $wash_str) ? "<=" : ">";

// if($sel_build != $def_opt) $sql_kw .= " AND mac IN (SELECT mac FROM wash_machine WHERE title LIKE '%{$sel_build}%') ";
// if($sel_level != $def_opt) $sql_kw .= " AND mac IN (SELECT mac FROM wash_machine WHERE title LIKE '%{$sel_level}%') ";
// if($sel_dev   != $def_opt) $sql_kw .= " AND i.WashId {$compare} (SELECT wash_machine FROM wash_machine w WHERE w.mac = i.mac) ";

// if($cardnum != '') { $sql_kw .= " AND cardnum = '{$cardnum}' "; }

// if($kw_start && $kw_end)   $sql_kw .= " AND LEFT(date_time,5009) >= '".$kw_start." 00:00:00' AND LEFT(date_time,5009) <= '".$kw_end." 23:59:59' ";

if($kw_start) {
	$s_date = date('Y-m-d H:i:s', strtotime($kw_start));
	$sql_kw.= " AND date_time >= '{$s_date}' ";
}

if($kw_end) {
	$e_date = date('Y-m-d H:i:s', strtotime($kw_end.'+1 day'));
	$sql_kw.= " AND date_time < '{$e_date}' ";
}


$sql = "SELECT cardnum, postive, PayValue, date_time as 'day' 
		FROM `icer_pay` i WHERE 1 {$sql_kw} ORDER BY date_time DESC";
// $stmtMonitor = $PDOLink->prepare($sql);  
$rs = $PDOLink->Query($sql);
$rs_tmp = $rs->fetchAll();

foreach($rs_tmp as $v) {
	
	$day = $v['day'];												
	$pos = $v['postive'];
	$amt = $v['PayValue'];

	$rs_data1[$day]['cardnum'] = $v['cardnum'];
	
	if($pos == '1') {
		$rs_data1[$day]['amt'] += $amt;
	}
	
	if($pos == '0') {
		$rs_data1[$day]['ref'] += $amt;
	}
}

if(isset($rs_data1))
{
	$filename=date("Ymd")."日期查詢報表.xlsx";
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
	$body_head=array(" 日期 ", "卡號", " 付款金額 ", " 退費金額 ", " 小計 ", " 備註");	
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
	foreach($rs_data1 as $d => $row)
	{

		// $CartTotal = strlen($row[CardID]);
		// $sn=$row[sn];
		// $add_date=date("Y-m-d H:i:s",strtotime($row[Time]));
		// $room_number=get_id($row[member_id],'member','room_strings');
		// $username=get_id($row[member_id],'member','username');
		// $id_card=get_card(str_pad($row[CardID],10,"0",STR_PAD_LEFT),'member','id_card');
		// $cname=get_id($row[member_id],'member','cname');
		// $price_total=$row[PayValue];	
		
		// $mac   = $row['mac'];
		// $title = $mc_data[$mac]['title'];
		
		// $washs = $mc_data[$mac]['wash_machine'];
		// $w_id  = $row['WashId'];
		// $dev   = $w_id > $washs ? "烘衣機" : "洗衣機";
		$amt = $row['amt'] == '' ? 0 : $row['amt'];
		$ref = $row['ref'] == '' ? 0 : $row['ref'];
		$sum = $amt - $ref;

		$item1 = $d;
		$item2 = $row['cardnum'];
		$item3 = $amt;
		$item4 = $ref;
		$item5 = $sum;
		// $item5 = $row['BeforeValue'];
		// $item6 = $row['PayValue']; 
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
	header("location: report.php?error=1"); 
	//print "<script>alert('沒有符合的資料匯出');</script>";
	$PDOLink = NULL;
	exit();
}

$PDOLink = NULL;
?>