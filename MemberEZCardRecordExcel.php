<?php // include('header_layout.php'); ?>
<?php // include('nav.php'); ?>
<?php // include('chk_log_in.php'); ?>
<?php include_once("config/db.php"); ?>
<?php require_once 'Classes/PHPExcel.php';?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php

	header('Content-type:application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=".date('yymmdd').".xls");

	
	$sel_dev   = $_GET['sel_dev'];
 	$kw_start  = $_GET['kw_start'];
 	$kw_end    = $_GET['kw_end'];

	$sql_kw    = "";
	
	if($sel_dev != '') $sql_kw .= " AND i.seat_id = '{$sel_dev}' ";
	
	if($kw_start) {
		$s_date = date('Y-m-d H:i:s', strtotime($kw_start));
		$sql_kw.= " AND start_time >= '{$s_date}' ";
	}
	
	if($kw_end) {
		$e_date = date('Y-m-d H:i:s', strtotime($kw_end.'+1 day'));
		$sql_kw.= " AND start_time < '{$e_date}' ";
	}
	
	$sql = "SELECT *, (SELECT `number` FROM seat WHERE id = u.seat_id LIMIT 0, 1) as 'parking_space',
			TRUNCATE(prepaid * rate, 0) as 'prepay', 
			CEIL(onpeek_amount * onpeek_rate) as 'onpeek_fee', 
			CEIL(offpeek_amount * offpeek_rate) as 'offpeek_fee', 
			timediff(end_time, start_time) as 'timediff' FROM `usage_history` u 
			WHERE 1 {$sql_kw} ORDER BY start_time DESC";
// echo $sql; exit;
	$rs = $PDOLink->prepare($sql); 
	$rs->execute();
	$data = $rs->fetchAll();
?>

<table border='1' cellpadding='0' cellspacing='0'>
	<tr>
		<th scope="col">#</th>
		<th scope="col">交易日期</th>
		<th scope="col">車位號碼</th>
		<th scope="col">卡號</th>
		<th scope="col">充電時間</th>
		<th scope="col">一般時段度數</th>
		<th scope="col">離峰時段度數</th> 
		<th scope="col">付款金額</th>
		<th scope="col">退費金額</th>
	</tr>
<tbody>

<?php

	$j = 1;
	
	$td_str = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>";
	
	foreach($data as $v) 
	{
		
		$fee = 0;
		$ref = 0;
		
		$prepay; $onpeek_fee; $offpeek_fee;
		
		$prepay      = $v['prepay'];
		$onpeek_fee  = $v['onpeek_fee'];
		$offpeek_fee = $v['offpeek_fee'];
		
		$status = $v['status'];
		
		switch($status) {
			
			case 1:
				$fee = $onpeek_fee + $offpeek_fee;
				$ref = 0;
				break;
			case 2:
				$fee = 0;
				// $fee = $onpeek_fee + $offpeek_fee;
				$ref = $prepay - $onpeek_fee - $offpeek_fee;
				break;
			default:
				break;										
		}
		
		// $member = get_id($v['member_id'],'member','username')." / ".get_id($v['member_id'],'member','cname');
		echo sprintf($td_str, $j++, $v['start_time'], $v['parking_space'], $v['id_card'], $v['timediff'], $v['start_amount'], $v['now_amount'], $v['fee'], '');
	}
?>
  
</tbody>
</table>