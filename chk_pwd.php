<?php include_once('config/db.php'); ?>
<?php 
session_start();

$data_type = 'member';
$username = $_POST[id];
$OldPwd = $_POST[old_pwd];
$NewPwd = $_POST[new_pwd];
$CheckNewPwd = $_POST[check_new_pwd];
$GetYouP = strlen($CheckNewPwd);

/* 重購 */
if($OldPwd == ''){
    header("location: change_passowrd.php?error=1"); 
    exit();
}

$list_q="select * from member where username='".$username."' and password=password('".$OldPwd."') ";
$list_r = $PDOLink->prepare($list_q); 
$list_r->execute();
$rs = $list_r->fetch(); 			  
$rs[id];

//存在
if($rs){

    if($NewPwd){

        if($CheckNewPwd && $GetYouP < 8){

            $upd_q="update member set   
            password=password('".$CheckNewPwd."')
            where username='".$username."'"; 
            $stmt = $PDOLink->prepare($upd_q); 
            $stmt->execute();

            /* get password */
            $userID_list_q="select * from member where username='".$username."' "; 
            $userID_list_r = $PDOLink->prepare($userID_list_q);
            $userID_list_r->execute();
            $userID_rs = $userID_list_r->fetch();       
            $i_id = $userID_rs[id];
            $i_password =  ""."%"."".$userID_rs[password].""."%"."";  ;    
            
            /* C# 資料同步更新 */
            $M_U_CodeUpdate2 = "WUMpassword=$i_password where id=$i_id";
            $M_U_col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
            $M_U_col_c="'變更密碼','Web','".$M_U_CodeUpdate2."','0','0','0','0',now()";
            $M_U_ins="INSERT INTO system_setting (".$M_U_col.") values (".$M_U_col_c.") ";
            $PDOLink->exec($M_U_ins);  

            if($data_type == 'member'){

                session_start();
                unset($_SESSION[user]);
        
            } elseif ($data_type == 'admin') {
        
                session_start();
                unset($_SESSION[admin_user]);
            
            }

            header("location: login.php"); 
            exit();

        } else {
            
            header("location: change_passowrd.php?error=1"); 
            exit();

        }

    } else {

        header("location: change_passowrd.php?error=1"); 
        exit();

    }


//帳號不存在
} else {

    header("location: change_passowrd.php?error=1"); 
    exit();

}

$PDOLink = null;



/* 判斷數字 */
// if(is_numeric($OldPwd)){
//     echo 'success';
// } else {
//     echo 'error';
// }

// exit();

    /* 密碼長度判斷 */
    // if (strlen($CheckNewPwd) >= 4 && strlen($CheckNewPwd) <= 8){
    //     echo 'success';
    // } else {
    //     header("location: change_passowrd.php?error=4&getYouPwd=$GetYouP"); 
    //     exit();
    // }

    // if(!$rs){

    // 	header("location: change_passowrd.php?error=1"); 
    
    // } elseif($NewPwd == '' && $CheckNewPwd == '') {

    // 	header("location: change_passowrd.php?error=2"); 

    // } elseif($CheckNewPwd == '') {

    //     header("location: change_passowrd.php?error=3"); 
    
    // } elseif(!is_numeric($NewPwd) AND !is_numeric($CheckNewPwd)) {

    //     header("location: change_passowrd.php?error=5"); 
    
    // } else {

    // 	$upd_q="update member set   
    // 	password=password('".$CheckNewPwd."') 
    // 	where 1 and username='".$username."'";
	// 	$stmt = $PDOLink->prepare($upd_q);
	// 	$stmt->execute();

    //     /* get password */
    //     $userID_list_q="select * from member where username='".$username."' ";
    //     $userID_list_r = $PDOLink->prepare($userID_list_q);
    //     $userID_list_r->execute();
    //     $userID_rs = $userID_list_r->fetch();       
    //     $i_id = $userID_rs[id];
    //     $i_password =  ""."%"."".$userID_rs[password].""."%"."";  ;    
        
    //     /* C# 資料同步更新 */
    //     $M_U_CodeUpdate2 = "WUMpassword=$i_password where id=$i_id";
    //     $M_U_col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    //     $M_U_col_c="'變更密碼','Web','".$M_U_CodeUpdate2."','0','0','0','0',now()";
    //     $M_U_ins="INSERT INTO system_setting (".$M_U_col.") values (".$M_U_col_c.") ";
    //     $PDOLink->exec($M_U_ins);  

    // 	header("location: change_passowrd.php?success=1"); 

    // }
?>