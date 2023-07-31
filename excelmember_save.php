<?php 
include_once("config/db.php");
require_once 'Excel/PHPExcleReader/Classes/PHPExcel.php';
require_once('Excel/PHPExcleReader/Classes/PHPExcel/Writer/Excel5.php');

// 學生付款紀錄表
set_time_limit(0);

// $i = 0;
// $j = 0;

$sql_kw;
$cname        = trim($_GET['cname']);
$username     = trim($_GET['username']);
$room_strings = trim($_GET['room_strings']);

// 20200108 -- 增加日期
$kw_start = $_GET['kw_start'];
$kw_end   = $_GET['kw_end'];

// if($username) {
	
	// $list_q = "select username from member where 1 and username='".$username."' ";
	// $list_r = $PDOLink->prepare($list_q); 
	// $list_r->execute();
	// $row2 = $list_r->fetch();         
	// $get_username=$row2[username];
	// if($username)$sql_kw.=" and username='".$get_username."'"; // 找學生
// }
if($username) $sql_kw.=" and username='".$get_username."'"; // 找學生
if($room_strings) $sql_kw.=" and room_strings='".$room_strings."'";
if($cname) $sql_kw.=" and cname='".$cname."'";
if($kw_start && $kw_end) $sql_kw.=" and LEFT(TimeUpdated,100) >= '".$kw_start." 00:00:00' AND LEFT(TimeUpdated,100) <= '".$kw_end." 23:59:59'";  	//時間區間 改2

// $sqlMonitor = "SELECT * FROM member where publicCardName='公用卡' order by room_strings asc "; 
$sqlMonitor = "SELECT * FROM member where !del_mark='1' and publicCardName!='測試卡' {$sql_kw} order by room_strings asc ";
// $sqlMonitor = "SELECT * FROM member where !del_mark='1' order by TimeUpdated desc "; //  WHERE user_room_type = '".$_GET[user_room_type]."'

$stmtMonitor = $PDOLink->prepare($sqlMonitor);
$stmtMonitor->execute();
$rowMonitor = $stmtMonitor->fetch();

if($rowMonitor)
{
	$filename=date("Ymd")."學生付款餘額統計表.xlsx";
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
	$submail_body_head="";
	$body_head=""; 
	$submail_body_head=array("  @@  ");	

	$body_head=array("列印日期:".date("Ymd")."    餘額 ");	
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
		$get_name=$add_col.chr($get_c)."1"; //從第二列開始
		$get_col=$add_col.chr($get_c);
		$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    //自動行高
		$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
		$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平置中
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
		// $objActSheet->getColumnDimension( 'A')->setWidth(30);         			    //30寬
		// echo $get_name.">>".$body_head[$i];
	}

	$body_head=array("  最新更新時間  ","  房號  ","  學號  ","  卡號  ","    姓名    ","   班級   ","  餘額  ","  備註  ");	
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
		$get_name=$add_col.chr($get_c)."2"; //從第二列開始
		$get_col=$add_col.chr($get_c);
		$xls->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);				    //自動行高
		$xls->getActiveSheet()->getColumnDimension($get_col)->setAutoSize(true);		//欄寬
		$xls->getActiveSheet()->getStyle($get_name)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平置中
		$xls->setActiveSheetIndex(0)->setCellValue($get_name,$body_head[$i]);
		// $objActSheet->getColumnDimension( 'A')->setWidth(30);         			    //30寬
		// echo $get_name.">>".$body_head[$i];
	}//列首結束

	$rs = $PDOLink->query($sqlMonitor);
	$rs->setFetchMode(PDO::FETCH_ASSOC);
	$j++;
	$no=1;
	$l=1;
	while($row=$rs->fetch())
	{
		$sn=$row[id];
		$berth_number=$row[berth_number];
		$user_room_strings=$row[room_strings];
		$username=" ".$row[username]." ";
		$id_card=" ".$row[id_card]." "; 
		$cname=$row[cname];
		$user_class=$row[user_class];
		$price_degree=round($row[balance],0);

		$item1=$row[TimeUpdated];
		$item2=$user_room_strings;
		$item3="".$username."";
		$item4="".$id_card."";
		$item5=$cname;
		$item6=$user_class;
		$item7=$price_degree;
		$item8;

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
		$sqlMonitorTotalData="select count(*) FROM member where !del_mark='1' order by TimeUpdated desc ";
		$rsTotal=$PDOLink->query($sqlMonitorTotalData);
		$rownumTotal=$rsTotal->fetchcolumn();   
		$AddrownumTotal=$rownumTotal+3;
		
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
	print "<script>alert('沒有符合的資料匯出');</script>";
}
$PDOLink = NULL;
?>