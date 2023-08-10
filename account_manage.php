<?php 
    ob_start();
    include('header_layout.php');
    include('nav.php');
    include('chk_log_in.php');
    include("account_status_update.php");
    // 限制權限不等於1 and 99的不可進入此頁面
	$id = $_SESSION["admin_user"]["id"];
	$sql = "SELECT a.data_type FROM admin a WHERE id = ?";
	$stmt = $PDOLink->prepare($sql);
	$stmt->bindParam(1, $id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
    // 判斷權限狀態 1 和 99 除外的不能進入頁面
	if ($result["data_type"] != 1 && $result["data_type"] != 99) {
		header("location: index.php");
		exit();
	}
    $message = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["getcreate"])) {
            $cname = $_POST["cname"];
            $id = $_POST["id"];
            $data_type = $_POST["data_type"];
            $result = getcreate($PDOLink, $cname, $id, $data_type);
            if ($result) {
                $_SESSION['message'] = '帳號已成功創建。';
                $_SESSION["message_type"] = "success";
            } else {
                $_SESSION['message'] = '帳號創建失敗或已存在相同帳號。';
                $_SESSION["message_type"] = "error";
            }
            header('location: account_manage.php');
            ob_end_flush();
            exit();
        }
        if (isset($_POST["getUpdate"], $_POST["cname"], $_POST["id"], $_POST["data_type"])) {
            if (getUpdate($PDOLink, $_POST["cname"], $_POST["id"], $_POST["data_type"], $_POST["admin_id"])) {
                $_SESSION['message'] = '編輯成功!';
                $_SESSION["message_type"] = "success";
                header('location: account_manage.php');
                ob_end_flush();
                exit();
            } else {
                $_SESSION['message'] = '編輯失敗!';
                $_SESSION["message_type"] = "error";
                header('location: account_manage.php');
                ob_end_flush();
                exit();
            }
        }
    }
    // 帳號一覽
    function getAccount($PDOLink) {
        $sql = "SELECT * FROM admin ORDER BY sn DESC";
        $stmt = $PDOLink->prepare($sql);
        $stmt->execute();
        $account = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $account;
    }
    $accounts = getAccount($PDOLink);
?>
<link href="./assets/css/account_manage.css" rel="stylesheet"></head>
<div class="container">
    <h2 class="text-center">帳號權限管理</h2>
    <?php if (isset($_SESSION["message"])){ ?>
        <div id="alert-msg" class="row justify-content-center">
            <!-- error結構 -->
            <?php if ($_SESSION["message_type"] == 'error'){ ?>
                <div class="col-lg-4 alert alert-danger" role="alert">
                    <strong><?php echo $_SESSION["message"]; unset($_SESSION["message"]); unset($_SESSION["message_type"]); ?></strong>
                </div>
            <?php } ?>
            <!-- success結構 -->
            <?php if ($_SESSION["message_type"] == 'success'){ ?>
                <div class="col-lg-4 alert alert-success" role="alert">
                    <strong><?php echo $_SESSION["message"]; unset($_SESSION["message"]); unset($_SESSION["message_type"]); ?></strong>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <!-- 帳號創建&一覽 -->
    <div class="row">
        <div class="col-12">
            <form id="add-member"  method="post" action="account_manage.php">
                <h3 class="card-header">帳號創建</h3>
                <div class="form-row">
                    <div class="col-md-3">
                        <input type="text" name="id" class="form-control mb-2" required placeholder="帳號">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="cname" class="form-control mb-2" required placeholder="姓名">
                    </div>
                    <div class="col-md-3">
                        <select name="data_type" class="form-control mb-2" required>
                            <option value="">請選擇</option>
                            <option value="1">超級管理員</option>
                            <option value="2">一般管理員</option>
                            <option value="3">客戶</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="getcreate" class="btn btn-primary mb-2">新增</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="account-list" class="col-md-12 table-responsive-md">
            <h3 class="card-header">帳號一覽</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">狀態</th>
                            <th scope="col">帳號</th>
                            <th scope="col">姓名</th>
                            <th scope="col">權限</th>
                            <th scope="col">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($accounts as $index => $account): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if ($account["status"] == "Y"):   // 用status欄位是否為Y判斷啟用or停用 ?>
                                    <i class="fas fa-user" data-toggle="tooltip" data-placement="bottom" title="啟用"></i>
                                <?php elseif ($account["status"] == "X"): ?>
                                    <i class="fas fa-user no-use" data-toggle="tooltip" data-placement="bottom" title="停用"></i>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $account["id"]; ?></td>
                            <td><?php echo $account["cname"]; ?></td>
                            <td>
                                <?php
                                    if ($account["data_type"] == 1) {
                                        echo "超級管理員";
                                    } else if ($account["data_type"] == 2) {
                                        echo "一般管理員";
                                    } else if ($account["data_type"] == 99) {
                                        echo "合創";
                                    } else {
                                        echo "客戶";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php // 點選編輯時自動帶入人員資料 ?>
                                <a href="#" class="edit-btn" data-toggle="modal" data-target="#editModal" data-id="<?php echo $account["id"]; ?>" data-cname="<?php echo $account["cname"]; ?>" data-data_type="<?php echo $account["data_type"]; ?>" data-placement="bottom" title="編輯"><i class="fas fa-pencil-alt"></i></a>
                                <a onclick = "reset('<?php echo $account['id']; ?>')" href = "#" data-toggle = "tooltip" data-placement = "bottom" title = "還原密碼"><i class = "fas fa-sync-alt"></i></a>
                                <?php if ($account["data_type"] != 1 && $account["data_type"] != 99):   // 超級管理員和合創無法把自己啟用or停用 ?>
                                    <?php if ($account["status"] == "Y"): ?>
                                        <a onclick="stay('<?php echo $account['id']; ?>')" href="#" data-toggle="tooltip" data-placement="bottom" title="停用帳號"><i class="fas fa-trash-alt"></i></a>
                                    <?php elseif ($account["status"] == "X"): ?>
                                        <a onclick="active('<?php echo $account['id']; ?>')" href="#" data-toggle="tooltip" data-placement="bottom" title="啟用帳號"><i class="fas fa-user-plus"></i></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>                        
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- 帳號編輯 -->
<div class="modal" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">編輯</h4>
                </div>
                <div class="modal-body">
                    <form id="edit-member" method="post" action="account_manage.php">
                        <div class="form-group">
                            <label for="id" class="col-form-label">帳號</label>
                            <input type="text" id="edit-id" name="id" class="form-control" readonly placeholder="帳號">
                        </div>
                        <div class="form-group">
                            <label for="cname" class="col-form-label">姓名</label>
                            <input type="text" id="edit-cname" name="cname" class="form-control" required placeholder="姓名">
                        </div>
                        <div class="form-group">
                            <label for="data_type" class="col-form-label">權限</label>
                            <select id="edit-data_type" name="data_type" class="form-control" required>
                                <option value="">請選擇</option>
                                <option value="1">超級管理員</option>
                                <option value="2">一般管理員</option>
                                <option value="3">客戶</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">返回</button>
                            <button type="submit" name="getUpdate" class="btn btn-primary" onclick="editMember(event, 1)">確認更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
<script>
    function reset(id){
        var msg = "確認提示\n您確定要還原密碼嗎?";
        if(confirm(msg))
        {
            location.replace("account_manage.php?sn="+id+"&action=pwd");
        }
    }
    function active(id){
    var msg = "確認提示\n啟用後，恢復先前操作本系統之使用權限，\n並還原預設密碼：88888 (請務必變更)\n確定要啟用嗎?";
        if(confirm(msg))
        {
            location.replace("account_manage.php?sn="+id+"&status=active");
        }
    }
    function stay(id){
        var msg = "確認提示\n帳號將停用\n您確定要停用嗎?";
            if(confirm(msg))
            {
                location.replace("account_manage.php?sn="+id+"&status=stay");
            }
    }
    function editMember(event, sn){
        var msg = "是否變更資料？";
        if(!confirm(msg)){
            event.preventDefault(); // 阻止表單提交
        }
    }
    // 編輯.取得所有.edit-btn 元素
    let editButtons = document.querySelectorAll(".edit-btn");
    // 對每個元素添加click監聽
    editButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let cname = this.getAttribute("data-cname");
            let data_type = this.getAttribute("data-data_type");

            document.querySelector("#edit-id").value = id;
            document.querySelector("#edit-cname").value = cname;
            document.querySelector("#edit-data_type").value = data_type;
        });
    });
</script>
<?php include('footer_layout.php'); ?>