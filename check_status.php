<?php
    include_once("config/db.php");
    header('Content-Type: application/json');
    if (!isset($_POST["id"])) {
        // 處理error
        die(json_encode(["error" => "查無用戶"]));
    } else {
        $admin_id = $_POST["id"];
        // 檢查用戶當前狀態
        $sql = "SELECT status FROM admin WHERE id = ?";
        $stmt = $PDOLink->prepare($sql);
        $stmt->execute([$admin_id]);
        $result = $stmt->fetch();
        if($result) {
            // 返回用戶狀態
            die(json_encode(["status" => $result["status"]]));
        } else {
            // 處理error
            die(json_encode(["error" => "查無用戶"]));
        }
    }
?>