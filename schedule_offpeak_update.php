<?php 
include_once("config/db.php");
include('chk_log_in.php');
// 目前設定值
$sql_setting = "SELECT s.*, sc.starttime, sc.endtime FROM seat s
				LEFT JOIN schedule sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6)";
$stmt = $PDOLink->query($sql_setting);
$stmt_setting = $stmt->fetch();
$weeks   = [1, 2, 3, 4, 5];
$act     = $_POST['act'];
$ps0     = $_POST['ps0'];
$ps6     = $_POST['ps6'];
$s_time  = $_POST['start_time'];
$e_time  = $_POST['end_time'];
$prepay  = $_POST['prepay'];
$rate    = $_POST['rate'];
$rate    = round($rate, 1);
$title   = "一般時段";
$sql_str = "UPDATE `seat` SET `rate` = '{$rate}', `prepaid` = '{$prepay}' ";
$result = $PDOLink->exec($sql_str);

if($result) {
	$admin_id = $_SESSION["admin_user"]["id"];
	// 檢查更改設定後值
	$sql_update = "SELECT s.*, sc.starttime, sc.endtime FROM seat s
				   LEFT JOIN schedule sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6)";
	$stmt_update = $PDOLink->query($sql_update);
	$stmt_result = $stmt_update->fetch();
	$change_log = "";
	// 比較修改前後差異
	if ($stmt_setting["prepaid"] != $stmt_result["prepaid"] || $stmt_setting["rate"] != $stmt_result["rate"] || $stmt_setting["starttime"] != $stmt_result["starttime"] || $stmt_setting["endtime"] != $stmt_result["endtime"]) {
		$change_log .= "開始時間{$stmt_setting['endtime']}結束時間{$stmt_setting['starttime']}預付(度){$stmt_setting['prepaid']}費率{$stmt_setting['rate']} 修改為 開始時間{$stmt_result['endtime']}結束時間{$stmt_result['starttime']}預付(度){$stmt_result['prepaid']}費率{$stmt_result['rate']}";
	}
	// 寫入log_list
	$content = $admin_id. "; 管理員修改了一般時段設定; ". $change_log;
	get_log_list($content);
	header("location: schedule_offpeak.php?success=1");
} else {
	header("location: schedule_offpeak.php?error=1");
}
?>