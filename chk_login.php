<?php 
include_once 'config/db.php';

$UserFindq="select id from member where username='".$_POST[username]."' and password=password('".$_POST[password]."') ";
$UserFindr = $PDOLink->prepare($UserFindq); 
$UserFindr->execute();
$UserFindRow = $UserFindr->fetch();   

if($UserFindRow[id]){  

	$user_id = $_POST[username];
	$pwd = $_POST[password];

	/* 學生卡(含共用卡同張表) */
	$sql = "select * from member where username='".$user_id."' and password=password('".$pwd."') ";
	$sth = $PDOLink->prepare($sql);
	$sth->execute(array($Id, $password));
	$result = $sth->fetch(); 

	//if($result) {
	$_SESSION[user][sn]=$result[id];  
	$_SESSION[user][id]=$result[username];
	$_SESSION[user][pwd]=$result[password];
	$_SESSION[user][cname]=$result[cname];
	$_SESSION[user][room_id]=$result[room_strings]; 

	/* 建立管理員登入log紀錄 */
	$content=$result[cname]."；學生登入";

	/* error_log php function */
	get_log_list($content);	

	header("location: member.php"); 

	//} else {  

	//  echo "<h1 style='color:red;'>帳號密碼輸入有誤，請重新登入。</h1>";
	//  echo "<p><a href='https://www.aodigit.com/".$SchoolWebName."/login.php'>回到登入畫面</a></p>";
	// header("location: login.php?error=1");
	
	//}

} else {		
	
	header("location: login.php?error=1");
	
}

$PDOLink = NULL;
?>