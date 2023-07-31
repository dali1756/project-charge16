<?php 

// include('header_layout.php'); 
// include('nav.php'); 

include_once("config/db.php");
include('chk_log_in.php'); 

$prepaid = $_POST['prepaid'];

// $sql = "UPDATE schedule SET prepaid = '{$prepaid}' WHERE enable = 1";
$sql = "UPDATE machine SET prepaid = '{$prepaid}'";

$PDOLink->exec($sql);

header("location: prepaid.php?success=1");

exit;

if($PDOLink->exec($sql)) {
	header("location: prepaid.php?success=1");
} else {
	header("location: prepaid.php?error=1");
}

?>