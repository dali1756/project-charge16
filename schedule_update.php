<?php 

// include('header_layout.php'); 
// include('nav.php'); 

include_once("config/db.php");
include('chk_log_in.php'); 

$pagesize = 10;

$act     = $_POST['act'];
$id      = $_POST['id'];
$sid     = $_POST['sid'];
$mode    = $_POST['mode'];
$rate    = $_POST['rate'];
$s_time  = $_POST['start_time'];
$e_time  = $_POST['end_time'];
$prepay  = $_POST['prepay'];
$weekday = $_POST['weekday'];


if($act == 'edit') {

	if(check_range($s_time, $e_time, $weekday, $id, $sid) ) {
		header("location: RefundTimeControl.php?sid={$id}&error=2");
		return;
	}
	
	$sql = "UPDATE schedule SET starttime = '{$s_time}', endtime = '{$e_time}', 
			prepaid = '{$prepay}', weeknumber = '{$weekday}', 
			mode = '{$mode}', rate = '{$rate}' WHERE id = '{$sid}'";
	$PDOLink->exec($sql);

	header("location: RefundTimeControl.php?sid={$id}");
}

if($act == 'add') {		
			
	if(check_range($s_time, $e_time, $weekday, $id, $sid) ) {
		header("location: RefundTimeControl.php?id={$id}&error=2");
		return;
	}
			
	$sql = "
		INSERT INTO `schedule` 
		(`seat_id`, `mode`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
		VALUES 
		('{$sid}', '{$mode}', '{$rate}', '{$prepay}', '{$s_time}', '{$e_time}', '{$weekday}', NULL, '1', now(), NULL)";		
	$PDOLink->exec($sql);
	
	header("location: RefundTimeControl.php?id={$id}");
}


function check_range($s_time, $e_time, $weekday, $id, $sid)
{
	$PDOLink = db_conn();
	
	$sql = "SELECT count(*) as 'cnt' FROM `schedule` 
			WHERE id != '{$id}' AND seat_id = '{$sid}' AND weeknumber = '{$weekday}'
			AND ((starttime BETWEEN '{$s_time}' AND '{$e_time}') OR (endtime BETWEEN '{$s_time}' AND '{$e_time}'))";
// echo $sql; exit;
	$rs  = $PDOLink->query($sql);
	$tmp = $rs->fetch();
	$ps  = $tmp['cnt'];
	
	if($ps > 0) {
		return true;
	} else {
		return false;
	}
}


// 加功能 -- 20200217 (順序不可變)
$act = $_GET['act'];
$id  = $_GET['id'];
$sid = $_GET['sid'];

if($act == 'delete') {
	
	$sql = "DELETE FROM schedule WHERE id = '{$sid}'";
	$PDOLink->exec($sql);
	
	// header("location: schedule.php?sid={$sid}&act={$act}");
	header("location: RefundTimeControl.php?sid={$id}");
}

if($act == 'enable') {
	
	$sql = "UPDATE schedule SET enable = 1 WHERE id = '{$sid}'";
	$PDOLink->exec($sql);
	
	// header("location: schedule.php?sid={$sid}&act={$act}");
	header("location: RefundTimeControl.php?sid={$id}");
}

if($act == 'disable') {
	
	$sql = "UPDATE schedule SET enable = 0 WHERE id = '{$sid}'";
	$PDOLink->exec($sql);
	
	// header("location: schedule.php?sid={$sid}&act={$act}");
	header("location: RefundTimeControl.php?sid={$id}");
}
?>