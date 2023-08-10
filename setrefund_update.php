<?php 
include_once("config/db.php");
include('chk_log_in.php'); 
$sid    = $_POST['sid'];
// 更改前值
$sql_setting = "SELECT seat.number, seat.refundcertification FROM seat WHERE id = '{$sid}'";
$stmt_setting = $PDOLink->query($sql_setting);
$stmt = $stmt_setting->fetch();
$refund = $_POST['refund'];
$sql = "UPDATE seat SET refundcertification = '{$refund}' WHERE id = '{$sid}'";
if($PDOLink->exec($sql)) {
	$admin_id = $_SESSION["admin_user"]["id"];
	// 更改後值
	$sql_update = "SELECT seat.number, seat.refundcertification FROM seat WHERE id = '{$sid}'";
	$stmt_update = $PDOLink->query($sql_update);
	$stmt_result = $stmt_update->fetch();
	$number = $stmt["number"];   // 車號
	$change_log = "";
	if ($stmt["refundcertification"] != $stmt_result["refundcertification"]) {   // 判斷更改前後的值是否相等
		$change_log = "退費設定由". get_refund($stmt['refundcertification']). "修改為". get_refund($stmt_result['refundcertification']);
	}
	$content = "{$admin_id}; 管理員修改了車號{$number}退費設定; {$change_log}";
	get_log_list($content);
	header("location: setrefund.php?sid={$sid}&success=1");
} else {
	header("location: setrefund.php?sid={$sid}&error=1");
}
// header("location: setrefund.php?sid={$sid}&success=1");
exit;
?>