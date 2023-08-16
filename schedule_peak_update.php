<?php 
include_once("config/db.php");
include('chk_log_in.php');
function updateSeat($PDOLink, $rate, $prepay, $charging_type) {
	$stmt = $PDOLink->prepare("UPDATE seat SET rate = :rate, prepaid = :prepaid WHERE charging_type = :charging_type");
	$stmt->bindParam(":rate", $rate);
	$stmt->bindParam(":prepaid", $prepay);
	$stmt->bindParam(":charging_type", $charging_type);
	$stmt->execute();
}
// 目前設定值test
// 汽車
$sql_setting_car = "SELECT * FROM schedule s JOIN seat se WHERE enable = '1' AND se.charging_type = 1 LIMIT 0, 1";
$stmt_car = $PDOLink->query($sql_setting_car);
$stmt_setting_car = $stmt_car->fetch();
$rate = $_POST["rate"];
$prepay = $_POST["prepay"];
// 機車
$sql_setting_mo = "SELECT * FROM schedule s JOIN seat se WHERE enable = '1' AND se.charging_type = 2 LIMIT 0, 1";
$stmt_mo = $PDOLink->query($sql_setting_mo);
$stmt_setting_mo = $stmt_mo->fetch();
$rate_mo = $_POST["rate_mo"];
$prepay_mo = $_POST["prepay_mo"];
$title   = "離峰時段";

updateSeat($PDOLink, $rate, $prepay, 1);
updateSeat($PDOLink, $rate_mo, $prepay_mo, 2);

$changestatus = false;
$originalSaturdayCheck = false;
$originalSundayCheck = false;
foreach ($settings as $setting) {
    if ($setting["weeknumber"] == 6) {
        $originalSaturdayCheck = true;
    } elseif ($setting["weeknumber"] == 7) {
        $originalSundayCheck = true;
    }
    if ($originalSaturdayCheck && $originalSundayCheck) {
        break;
    }
}
// 刪除所有資料
$sql_str = "DELETE FROM `schedule`;";
$weeks   = [1, 2, 3, 4, 5];
$act     = $_POST['act'];
$ps0     = $_POST['ps0'];   // 週日
$ps6     = $_POST['ps6'];   // 週六
$s_time  = $_POST['start_time'];
$e_time  = $_POST['end_time'];
$prepay  = $_POST["prepay"];   // 原為硬篇碼$prepay = 500;
// 汽車
$rate    = $_POST['rate'];
// 機車
$rate_mo = $_POST["rate_mo"];
$rate    = round($rate, 1);
$title   = "離峰時段";
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
            	VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '{$m}', '{$title}', '1', '{$createtime}', NULL);";
	}
	if($ps0 != '') {
		// 有勾選 時間時段同畫面 -- 20200406
		$sunday = ($v["charging_type"] == 1) ? $rate : $rate_mo;
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '7', '{$title}', '1', '{$createtime}', NULL);";
	} else {
		// 未勾選 時段為整天 -- 20200406
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '00:00', '23:59', '7', '{$title}', '1', '{$createtime}', NULL);";
	}
	if($ps6 != '') {
		$saturday = ($v["charging_type"] == 1) ? $rate : $rate_mo;
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '{$s_time}', '{$e_time}', '6', '{$title}', '1', '{$createtime}', NULL);";
	} else {
		$sql_str .= "
				INSERT INTO `schedule` (`seat_id`, `rate`, `prepaid`, `starttime`, `endtime`, `weeknumber`, `title`, `enable`, `createtime`, `updatetime`) 
				VALUES ('{$v['id']}', '{$current_rate}', '{$current_prepay}', '00:00', '23:59', '6', '{$title}', '1', '{$createtime}', NULL);";
	}
}
$result = $PDOLink->exec($sql_str);
if($result) {
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

	// 比較修改前後值
	$change_log = "";
	$ps6_check = ($ps6 != "") ? true : false;
	$ps0_check = ($ps0 != "") ? true : false;
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
	if ($ps6_check) {
		$change_log .= "已勾選週六";
	} else {
		$change_log .= "未勾選週六";
	}
	if ($ps0_check) {
		$change_log .= "已勾選週日";
	} else {
		$change_log .= "未勾選週日";
	}
	// 寫入log_list
	$content = $admin_id. "; 管理員修改了離峰時段設定; ". $change_log;
	get_log_list($content);
	header("location: schedule_peak.php?success=1");
	exit();
} else {
	header("location: schedule_peak.php?success=1");
	exit();
}
?>