<?php 
include_once("config/db.php");
include('chk_log_in.php');
// 目前設定值檢查 test
$sql_setting_car = "SELECT s.*, sc.starttime, sc.endtime FROM seat s
                	LEFT JOIN schedule sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6) AND s.charging_type = 1";
$stmt_car = $PDOLink->query($sql_setting_car);
$stmt_setting_car = $stmt_car->fetch();

$sql_setting_mo = "SELECT s.*, sc.starttime, sc.endtime FROM seat s
                   LEFT JOIN schedule sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6) AND s.charging_type = 2";
$stmt_mo = $PDOLink->query($sql_setting_mo);
$stmt_setting_mo = $stmt_mo->fetch();

$weeks   = [1, 2, 3, 4, 5];
$act     = $_POST['act'];
$ps0     = $_POST['ps0'];
$ps6     = $_POST['ps6'];
$s_time  = $_POST['start_time'];
$e_time  = $_POST['end_time'];
$prepay  = $_POST['prepay'];
$rate    = $_POST['rate'];
// 機車
$prepay_mo  = $_POST['prepay_mo'];
$rate_mo    = $_POST['rate_mo'];
$rate    = round($rate, 1);
$title   = "一般時段";
$enable = 0;

$sql_str = "DELETE FROM `schedule`;";
$sql  = "SELECT * FROM `seat`";
$rs   = $PDOLink->query($sql);
$data = $rs->fetchAll();
foreach($data as $v) {
	$createtime = date('Y-m-d H:i:s');
	$current_rate = ($v["charging_type"] == 1) ? $rate : $rate_mo;
	$current_prepay = ($v["charging_type"] == 1) ? $prepay : $prepay_mo;
	foreach($weeks as $m) {
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
            	VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '{$m}', '{$title}', '{$enable}', '{$createtime}', NULL);";
	}
	if($ps0 != '') {
		// 有勾選 時間時段同畫面 -- 20200406
		$sunday = ($v["charging_type"] == 1) ? $rate : $rate_mo;
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '7', '{$title}', '{$enable}', '{$createtime}', NULL);";
	} else {
		// 未勾選 時段為整天 -- 20200406
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '7', '{$title}', '{$enable}', '{$createtime}', NULL);";
	}
	if($ps6 != '') {
		$saturday = ($v["charging_type"] == 1) ? $rate : $rate_mo;
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '6', '{$title}', '{$enable}', '{$createtime}', NULL);";
	} else {
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '6', '{$title}', '{$enable}', '{$createtime}', NULL);";
	}
}
$result = $PDOLink->exec($sql_str);

// 汽車更新
$result = $PDOLink->exec("UPDATE `seat` SET `rate` = '{$rate}', `prepaid` = '{$prepay}' WHERE `charging_type` = 1");
// 機車更新
$result_mo = $PDOLink->exec("UPDATE `seat` SET `rate` = '{$rate_mo}', `prepaid` = '{$prepay_mo}' WHERE `charging_type` = 2");
$change_log = "";
$changestatus = false;
// var_dump($result); 
// die();
if($result || $result_mo) {
	$admin_id = $_SESSION["admin_user"]["id"];
	// 檢查更新後的值test
	$sql_update_car = "SELECT s.*, sc.starttime, sc.endtime FROM seat s
					   LEFT JOIN schedule sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6) AND s.charging_type = 1";
	$stmt_update_car = $PDOLink->query($sql_update_car);
	$stmt_result_car = $stmt_update_car->fetch();

	$sql_update_mo = "SELECT s.*, sc.starttime, sc.endtime FROM seat s
					  LEFT JOIN schedule sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6) AND s.charging_type = 2";
	$stmt_update_mo = $PDOLink->query($sql_update_mo);
	$stmt_result_mo = $stmt_update_mo->fetch();

	$change_log = "";
	// 汽車的修改前後差異test
	if ($stmt_setting_car["prepaid"] != $stmt_result_car["prepaid"] || $stmt_setting_car["rate"] != $stmt_result_car["rate"] || 
		$stmt_setting_car["starttime"] != $stmt_result_car["starttime"] || $stmt_setting_car["endtime"] != $stmt_result_car["endtime"]) {
		$changestatus = true;
		$change_log .= "[汽車]開始時間{$stmt_setting_car['endtime']}結束時間{$stmt_setting_car['starttime']}預付(度){$stmt_setting_car['prepaid']}費率{$stmt_setting_car['rate']} 修改為 開始時間{$stmt_result_car['endtime']}結束時間{$stmt_result_car['starttime']}預付(度){$stmt_result_car['prepaid']}費率{$stmt_result_car['rate']}";
	}
	// 機車的修改前後差異test
	if ($stmt_setting_mo["prepaid"] != $stmt_result_mo["prepaid"] || $stmt_setting_mo["rate"] != $stmt_result_mo["rate"] || 
		$stmt_setting_mo["starttime"] != $stmt_result_mo["starttime"] || $stmt_setting_mo["endtime"] != $stmt_result_mo["endtime"]) {
		$changestatus = true;
		$change_log .= "[機車]開始時間{$stmt_setting_mo['endtime']}結束時間{$stmt_setting_mo['starttime']}預付(度){$stmt_setting_mo['prepaid']}費率{$stmt_setting_mo['rate']} 修改為 開始時間{$stmt_result_mo['endtime']}結束時間{$stmt_result_mo['starttime']}預付(度){$stmt_result_mo['prepaid']}費率{$stmt_result_mo['rate']}";
	}
	// 寫入log_list
	$content = $admin_id. "; 管理員修改了一般時段設定; ". $change_log;
	get_log_list($content);
	header("location: schedule_offpeak.php?success=1");
	exit();
} else {
	header("location: schedule_offpeak.php?error=1");
	exit();
}
?>