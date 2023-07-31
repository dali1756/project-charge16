<?php 

	if($_GET[data_type] == 'member'){

		session_start();
		unset($_SESSION[user]);
		header("location: index.php"); 

	} elseif ($_GET[data_type] == 'admin') {

		session_start();
		unset($_SESSION[admin_user]);
		header("location: index.php"); 
	
	}

?>