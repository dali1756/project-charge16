<?php 
	include_once('config/db.php');
	if(!isset($_POST['id']) || !isset($_POST['pwd'])) {
		//若沒有從Login submit或帳密為空白，就導回Login.php  
		die(header("location: index.php?error=1"));
	} else {
		$admin_id  = $_POST['id'];
		$admin_pwd = $_POST['pwd'];
		// 判斷狀態是否為Y 不是就拒絕登入
		$sql = "SELECT * FROM admin WHERE  id = ? AND pwd=CONCAT('*', UPPER(SHA1(UNHEX(SHA1(?))))) AND status = 'Y' ";
		$param =  array($admin_id, $admin_pwd); 
		$stmt = $PDOLink->prepare($sql);
		$stmt->execute($param);
		$result = $stmt->fetch(); 
		if($result) { 		
			$_SESSION['admin_user']['sn']=$result['sn'];
			$_SESSION['admin_user']['id']=$result['id'];
			$_SESSION['admin_user']['cname']=$result['cname'];
			$_SESSION['admin_user']['pwd']=$result['pwd'];
			$_SESSION['admin_user']['email']=$result['email'];
			$_SESSION["admin_user"]["status"] = $result["status"];
			$_SESSION['admin_user']['data_type']=$result['data_type'];
			/* error_log php function */
			$content=$result['id']."；管理員登入";
			get_log_list($content);	

			die(header("location: member.php"));

		} else {
			die(header("location: index.php?error=1"));
		}
	}
?>