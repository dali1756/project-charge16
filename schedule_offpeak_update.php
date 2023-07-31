<?php 

include_once("config/db.php");
include('chk_log_in.php');

// $sql_str = "DELETE FROM `schedule`;";

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

// $sql  = "SELECT * FROM `seat`";
// $rs   = $PDOLink->query($sql);
// $data = $rs->fetchAll();

// foreach($data as $v) {
	
	// $createtime = date('Y-m-d H:i:s');
	
	// $sql_str .= "UPDATE `seat` SET `rate` = '{$rate}',`prepaid` = '{$prepay}' WHERE `id` = {$v[]};";
// }

$sql_str = "UPDATE `seat` SET `rate` = '{$rate}', `prepaid` = '{$prepay}' ";
$result = $PDOLink->exec($sql_str);

if($result) {
	header("location: schedule_offpeak.php?success=1");
} else {
	// header("location: schedule_offpeak.php?success=1");
	header("location: schedule_offpeak.php?error=1");
}

?>