<?php
    include('header_layout.php');
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
            return $stmt_update->execute(); //返回更新操作的結果
        } 
        return false; //如果沒有找到該ID，則返回false
    }
    
    // 還原密碼,一律採用預設值88888
    function getRepwd($PDOLink, $sn) {
        $default = "88888";
        $trunpassword = "*". strtoupper(sha1(sha1($default, true)));
        $sql = "UPDATE admin SET pwd = :password WHERE id = :sn";
        $stmt = $PDOLink->prepare($sql);
        $stmt->bindParam(":password", $trunpassword);
        $stmt->bindParam(":sn", $sn);
        return $stmt->execute();
    }
    if(isset($_GET["action"]) && isset($_GET["sn"])) {
        $sn = $_GET["sn"];
        if($_GET["action"] == "pwd") {
            if(getRepwd($PDOLink, $sn)) {
                echo '<script language="javascript">';
                echo 'alert("還原成功!")';
                echo '</script>';
            } else {
                echo '<script language="javascript">';
                echo 'alert("還原失敗!")';
                echo '</script>';
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
        return $stmt->execute();
    }
    if (isset($_GET["status"]) && isset($_GET["sn"])) {
        $sn = $_GET["sn"];
        $type = $_GET["status"];
        $success = false;
        $message = '';
    
        if ($type == "active") {
            // 啟用後會將密碼還原為預設值88888
            $success = updateStatus($PDOLink, $sn, "Y", "88888");
            $message = $success ? '已啟用' : '啟用失敗';
        } else if ($type == "stay") {
            // 停用時只會更新狀態顏色
            $success = updateStatus($PDOLink, $sn, "X", $account["pwd"]);
            $message = $success ? '已停用' : '停用失敗';
        }
        echo '<script language="javascript">';
        echo "alert('$message')";
        echo '</script>';
    }
    
    function stay($PDOLink, $id) {
        updateStatus($PDOLink, $id, "X", null);
    }

    function active($PDOLink, $id) {
        updateStatus($PDOLink, $id, "Y", "88888");
    }
?>
