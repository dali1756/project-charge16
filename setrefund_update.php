<?php 

// include('header_layout.php'); 
// include('nav.php'); 

include_once("config/db.php");
include('chk_log_in.php'); 

$sid    = $_POST['sid'];
$refund = $_POST['refund'];

$sql = "UPDATE seat SET refundcertification = '{$refund}' WHERE id = '{$sid}'";

$PDOLink->exec($sql);

header("location: setrefund.php?sid={$sid}&success=1");

exit;


if($PDOLink->exec($sql)) {
	header("location: setrefund.php?sid={$sid}&success=1");
} else {
	header("location: setrefund.php?sid={$sid}&error=1");
}

?>