<?php 

// include('header_layout.php'); 
// include('nav.php'); 

include_once("config/db.php");
include('chk_log_in.php'); 

$sid  = $_POST['sid'];
$mode = $_POST['mode'];

// $sql = "UPDATE schedule SET mode = '{$mode}' WHERE enable = 1";
// $sql = "UPDATE machine SET mode = '{$mode}'";
$sql = "UPDATE seat SET mode = '{$mode}' WHERE id = '{$sid}'";

$PDOLink->exec($sql);

header("location: setmode.php?success=1&sid=".$sid);

exit;


if($PDOLink->exec($sql)) {
	
	header("location: setmode.php?success=1");
	
} else {
	
	header("location: setmode.php?error=1");
}

?>