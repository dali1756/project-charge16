<?php 
include_once("config/db.php");
include('chk_log_in.php');
// 當前設定
$sql_setting = "SELECT * FROM schedule WHERE enable = '1' LIMIT 0, 1";
$stmt = $PDOLink->query($sql_setting);
$stmt_setting = $stmt->fetch();
// 刪除所有資料
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
$result = $PDOLink->exec($sql_str);
if($result) {
	$admin_id = $_SESSION["admin_user"]["id"];
	// 撈取更新後設定
	$sql_update = "SELECT * FROM schedule WHERE enable = '1' LIMIT 0, 1";
	$stmt_update = $PDOLink->query($sql_update);
	$update_result = $stmt_update->fetch();
	// 比較修改前後值
	$change_log = "";
	if ($stmt_setting["rate"] != $update_result["rate"]) {
		$change_log .= "費率由{$stmt_setting['rate']} 修改為 {$update_result['rate']} ";
	}
	// 寫入log_list
	$content = $admin_id. "; 管理員修改了離峰時段設定; ". $change_log;
	get_log_list($content);
	header("location: schedule_peak.php?success=1");
} else {
	header("location: schedule_peak.php?success=1");
}
?>