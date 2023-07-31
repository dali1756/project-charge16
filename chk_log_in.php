<?php 
	  session_start();
	  if(isset($_SESSION['user']['id'])){
		$list_q = "select * from member where 1 and username='".$_SESSION['user']['id']."' ";
		$list_r = $PDOLink->prepare($list_q); 
	    $list_r->execute();
	    $rs = $list_r->fetch(); 			 
		$user_sn = $rs['id'];
	  }
	  if(isset($_SESSION['admin_user']['id'])){
		$list_q2 = "select * from admin where 1 and id='".$_SESSION['admin_user']['id']."' ";
		$list_r2 = $PDOLink->prepare($list_q2); 
	    $list_r2->execute();
	    $rs2 = $list_r2->fetch(); 			 
		$admin_id = $rs2['id'];
	  }
	if(!$user_sn && !$admin_id) {
		header("Location: index.php");		
		echo "<script>location.href = 'index.php'</script>";
		// exit();
	}
	// 檢查用戶是否已經登入,如果被停用就無法進行任何操作
	if (isset($_SESSION["admin_user"])) {
		$id = $_SESSION["admin_user"]["id"];
		// 查詢用戶的狀態
		$stmt = $PDOLink->prepare("SELECT status FROM admin WHERE id = ?");
		$stmt->bindParam(1, $id);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		// 如果用戶被停用重新導向至首頁
		if ($result["status"] == "X") {
			$_SESSION["text"] = "帳號已被停用!";   // 
			unset($_SESSION["admin_user"]);
			// session_unset();     // 清除所有session
			// session_destroy();   // 銷毀session
			header("location: index.php");
			exit();
		}
	}
?>
