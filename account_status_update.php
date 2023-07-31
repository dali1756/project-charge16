<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    ob_start();
    include('header_layout.php');
    // 創建帳號
    function getcreate($PDOLink, $cname, $id, $data_type) {
        $cname = $_POST["cname"];
        $id = $_POST["id"];
        $pwd = "88888";
        $data_type = $_POST["data_type"];
        $status = "Y";
        $temple_id = 1;
        $add_date = date("Y-m-d H:i:s");
        $sql_check = "SELECT * FROM admin WHERE id = ?";
        $stmt = $PDOLink->prepare($sql_check);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            $sql = "INSERT INTO admin (cname, id, pwd, status, data_type, temple_id, add_date) VALUES (?, ?, CONCAT('*', UPPER(SHA1(UNHEX(SHA1(?))))), ?, ?, ?, ?)";
            $stmt = $PDOLink->prepare($sql);
            $stmt->bindParam(1, $cname);
            $stmt->bindParam(2, $id);
            $stmt->bindParam(3, $pwd);
            $stmt->bindParam(4, $status);
            $stmt->bindParam(5, $data_type);
            $stmt->bindParam(6, $temple_id);
            $stmt->bindParam(7, $add_date);
            $result = $stmt->execute();
            if ($result) {
                $admin_id = $_SESSION["admin_user"]["id"];
                $content = $admin_id. "; 管理員創建 {$id} 帳號";
                get_log_list($content);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // 編輯
    function getUpdate($PDOLink, $cname, $id, $data_type) {
        $sql_check = "SELECT * FROM admin WHERE id = ?";
        $stmt_check = $PDOLink->prepare($sql_check);
        $stmt_check->bindParam(1, $id);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $sql_update = "UPDATE admin SET cname = ?, data_type = ? WHERE id = ?";
            $stmt_update = $PDOLink->prepare($sql_update);
            $stmt_update->bindParam(1, $cname);
            $stmt_update->bindParam(2, $data_type);
            $stmt_update->bindParam(3, $id);
            $result_edit = $stmt_update->execute(); //返回更新操作的結果
            if ($result_edit) {
                $admin_id = $_SESSION["admin_user"]["id"];
                if ($cname != $result["cname"]) {
                    $content = $admin_id . "; 管理員對帳號 {$id} 編輯姓名";
                    get_log_list($content);
                }
                if ($data_type != $result["data_type"]) {
                    $content = $admin_id . "; 管理員對帳號 {$id} 編輯權限";
                    get_log_list($content);
                }
            }
            return $result_edit;
        } 
        return false; //如果沒有找到該id 返回false
    }
    // 還原密碼,一律採用預設值88888
    function getRepwd($PDOLink, $sn) {
        $default = "88888";
        $trunpassword = "*". strtoupper(sha1(sha1($default, true)));
        $sql = "UPDATE admin SET pwd = :password WHERE id = :sn";
        $stmt = $PDOLink->prepare($sql);
        $stmt->bindParam(":password", $trunpassword);
        $stmt->bindParam(":sn", $sn);
        // return $stmt->execute();
        $result = $stmt->execute();
        if ($result) {
            $admin_id = $_SESSION["admin_user"]["id"];
            $content = $admin_id. "; 管理員對帳號 {$sn} 還原密碼";
            get_log_list($content);
        }
        return $result;
    }
    if(isset($_GET["action"]) && isset($_GET["sn"])) {
        $sn = $_GET["sn"];
        if($_GET["action"] == "pwd") {
            if(getRepwd($PDOLink, $sn, $admin_id)) {
                $_SESSION["message"] = "還原成功，溫馨提示：預設密碼為 88888 (請務必變更)";
                $_SESSION["message_type"] = "success";
                header('Location: account_manage.php');
                ob_end_flush();
                exit();
            } else {
                $_SESSION["message"] = "還原失敗";
                $_SESSION["message_type"] = "error";
                header('Location: account_manage.php');
                ob_end_flush();
                exit();
            }
        }
    }
    // 啟用 / 停用
    function updateStatus($PDOLink, $sn, $status, $defaultPwd) {
        $sql = "UPDATE admin SET status = ?, pwd = CONCAT('*', UPPER(SHA1(UNHEX(SHA1(?))))) WHERE id = ?";
        $stmt = $PDOLink->prepare($sql);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $defaultPwd);
        $stmt->bindParam(3, $sn);
        $result = $stmt->execute();
        if ($result) {
            $admin_id = $_SESSION['admin_user']['id'];
            $status_str = $status == "Y" ? "啟用" : "停用";
            $content = $admin_id. "; 管理員對帳號 {$sn} 進行{$status_str}";
            get_log_list($content);
        }
        return $result;
    }
    if (isset($_GET["status"]) && isset($_GET["sn"])) {
        $sn = $_GET["sn"];
        $type = $_GET["status"];
        $success = false;
        $message = '';
        if ($type == "active") {
            // 啟用後會將密碼還原為預設值88888
            $success = updateStatus($PDOLink, $sn, "Y", "88888", $admin_id);
            $message = $success ? "已啟用，啟用後恢復先前操作本系統之使用權限，並還原預設密碼：88888 (請務必變更)" : "啟用失敗";
        } else if ($type == "stay") {
            // 停用時只會更新狀態顏色
            $success = updateStatus($PDOLink, $sn, "X", $account["pwd"], $admin_id);
            $message = $success ? "已停用" : "停用失敗";
        }
        $_SESSION["message"] = $message;
        $_SESSION["message_type"] = "success";
        header('Location: account_manage.php');
        ob_end_flush();
        exit();
    }
    // 停用
    function stay($PDOLink, $id) {
        updateStatus($PDOLink, $id, "X", null);
    }
    // 啟用
    function active($PDOLink, $id) {
        updateStatus($PDOLink, $id, "Y", "88888");
    }
?>