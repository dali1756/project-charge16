<?php 
include_once("config/db.php");
include('chk_log_in.php'); 
$sid  = $_POST['sid'];
// 撈取更改前值
$sql_setting = "SELECT seat.number, seat.mode FROM seat WHERE id = '{$sid}'";
$stmt_setting = $PDOLink->query($sql_setting);
$stmt = $stmt_setting->fetch();
$mode = $_POST['mode'];
$sql = "UPDATE seat SET mode = '{$mode}' WHERE id = '{$sid}'";
if($PDOLink->exec($sql)) {
	$admin_id = $_SESSION["admin_user"]["id"];
	// 撈取更改後值
	$sql_update = "SELECT seat.number, seat.mode FROM seat WHERE id = '{$sid}'";
	$stmt_update = $PDOLink->query($sql_update);
	$stmt_result = $stmt_update->fetch();
	$status = $stmt["mode"];     // 狀態
	$number = $stmt["number"];   // 車號
	$change_log = "";
	if ($stmt["mode"] != $stmt_result["mode"]) {   // 判斷更改前後的mode是否相等
		$change_log = "模式設定由". get_mode($stmt["mode"]). "修改為". get_mode($stmt_result["mode"]);
	}
	$content = "{$admin_id}; 管理員修改了車號{$number}模式設定; {$change_log}";
	get_log_list($content);
	header("location: setmode.php?sid={$sid}&success=1");
} else {
	header("location: setmode.php?sid={$sid}&error=1");
}
// header("location: setmode.php?success=1&sid=".$sid);
exit;
?>