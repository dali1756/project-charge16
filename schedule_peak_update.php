<?php 

include_once("config/db.php");
include('chk_log_in.php');

$sql_str = "DELETE FROM `schedule`;";

$weeks   = [1, 2, 3, 4, 5];

$act     = $_POST['act'];

$ps0     = $_POST['ps0'];
$ps6     = $_POST['ps6'];

$s_time  = $_POST['start_time'];
$e_time  = $_POST['end_time'];

// $prepay  = $_POST['prepay'];
$prepay  = 500;

$rate    = $_POST['rate'];
$rate    = round($rate, 1);

$title   = "離峰時段";

$sql  = "SELECT * FROM `seat`";
$rs   = $PDOLink->query($sql);
$data = $rs->fetchAll();

foreach($data as $v) {
	
	$createtime = date('Y-m-d H:i:s');
	
	foreach($weeks as $m) {
		
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$rate}', '{$prepay}', '{$s_time}', '{$e_time}', '{$m}', '{$title}', '1', '{$createtime}', NULL);";
	}
	
	if($ps0 != '') {
		// 有勾選 時間時段同畫面 -- 20200406
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$rate}', '{$prepay}', '{$s_time}', '{$e_time}', '7', '{$title}', '1', '{$createtime}', NULL);";
	} else {
		// 未勾選 時段為整天 -- 20200406
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$rate}', '{$prepay}', '00:00', '23:59', '7', '{$title}', '1', '{$createtime}', NULL);";
	}

	if($ps6 != '') {
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$rate}', '{$prepay}', '{$s_time}', '{$e_time}', '6', '{$title}', '1', '{$createtime}', NULL);";
	} else {
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$rate}', '{$prepay}', '00:00', '23:59', '6', '{$title}', '1', '{$createtime}', NULL);";
	}
}


// echo $sql_str; exit;

$result = $PDOLink->exec($sql_str);

if($result) {
	header("location: schedule_peak.php?success=1");
} else {
	header("location: schedule_peak.php?success=1");
}

?>