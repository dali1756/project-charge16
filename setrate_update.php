<?php 

// include('header_layout.php'); 
// include('nav.php'); 

include_once("config/db.php");
include('chk_log_in.php'); 

$sid    = $_POST['sid'];
$prepay = $_POST['prepaid'];
$rate   = $_POST['rate'];
$rate   = round($rate, 1);

// $sql = "UPDATE machine SET rate = '{$rate}', prepaid = '{$prepay}'";
$sql = "UPDATE seat SET rate = '{$rate}', prepaid = '{$prepay}' WHERE id = '{$sid}'";


$PDOLink->exec($sql);

header("location: setrate.php?success=1&sid=".$sid);

?>