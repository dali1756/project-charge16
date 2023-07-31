<?php include_once('config/db.php'); ?>
<?php 

$data_type = 'admin';
$sn = $_POST[sn]; 
$id = $_POST[id];

$OldPwd = $_POST[o_pwd];
$NewPwd = $_POST[new_pwd];
$CheckNewPwd = $_POST[new_pwd_check];

	if($OldPwd == ''){
		header("location: admin_edit.php?error=1&id=$sn"); 
		exit();
	}

	//變更密碼用
	$list_q="select * from admin where id=? and pwd=CONCAT('*', UPPER(SHA1(UNHEX(SHA1(?)))))  ";
	$list_r = $PDOLink->prepare($list_q); 
    $list_r->execute(array($id,$OldPwd));
    $rs = $list_r->fetch(); 			  
    $rs[id];

	//存在
    if($rs)
	{
 		if(trim($NewPwd) === trim($CheckNewPwd) && !empty($NewPwd) && !empty($CheckNewPwd))
		{ 
			$upd_q="update admin set   pwd=CONCAT('*', UPPER(SHA1(UNHEX(SHA1(?))))) where 1 and id=? ";
			$stmt = $PDOLink->prepare($upd_q);
			$updated = $stmt->execute(array($CheckNewPwd,$id)); 
 
			if($updated)
			{
				$content = $_SESSION['admin_user']['id']."；密碼修改";
				get_log_list($content);	
				
				session_start();
				unset($_SESSION[admin_user]); 
				die(header("location: index.php")); 
			} 
		} else { 
			die(header("location: admin_edit.php?error=1&id=$sn"));  
		} 

	//帳號不存在
	} else {

		header("location: admin_edit.php?error=1&id=$sn"); 
		exit();

	}

$PDOLink = null;

?>