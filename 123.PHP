<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');
?>
    <link href="./assets/css/account_manage.css" rel="stylesheet"></head>

    <div class="container">
        <div class="row">
            <div class="col-12 alert alert-primary" role="alert">
                <h4>【製作說明】做完可刪除</h4>
                <ol>
                    <li>帳號創建：欄位皆必填</li>
                    <li>帳號一覽欄位：#(流水號)、狀態(檢視帳號啟用/停用)、帳號  姓名  權限、操作</li>
                    <li>操作：
                        <span>
                        1.依查詢結果：帶出欄位如下表<br>
							2.<a href='#>' class='btn  text-orange' data-toggle="tooltip" data-placement="bottom" title='編輯'><i class='fas fa-pencil-alt'></i></a>
							：不論狀態，皆可編輯資料<br>
							3.<a onclick="return confirm('確認提示\n您確定要還原網頁登入密碼嗎?\n溫馨提示：預設密碼為 88888 (請務必變更)');" href='model\member_reset.php?id=<?php echo $row_id ?>&type=pwd'  class='btn text-orange' data-toggle="tooltip" data-placement="bottom" title='還原網頁登入密碼'><i class='fas fa-sync-alt'></i></a>
							：不論狀態，皆可還原網頁登入密碼<br>
							4.<a onclick="return confirm('確認提示\n將停用帳號\n您確定要停用嗎?');" class='btn text-orange'  data-toggle="tooltip" data-placement="bottom" title='停用帳號'><i class='fas fa-trash-alt'></i></a>
							：對應"狀態"，停用之帳號不可登入使用本系統<br>
							5.<a onclick="return confirm('確認提示\n啟用後，恢復先前操作本系統之使用權限，\n並還原預設密碼：88888 (請務必變更)\n確定要啟用嗎?');"  href='' class='btn text-orange'  data-toggle="tooltip" data-placement="bottom" title='啟用帳號'><i class="fas fa-user-plus"></i></a>：對應"狀態"，恢復啟用帳號後，復原先前操作本系統之使用權限，並同時還原預設密碼<br>
							<i class='fas fa-user text-gray' style='color:#24D354' data-toggle="tooltip" data-placement="bottom" title='啟用'></i>：啟用帳號之狀態<br>
							<i class='fas fa-user text-gray' data-toggle="tooltip" data-placement="bottom" title='停用'></i>：停用帳號之狀態<br>
                        </span>
                        <p></p>
                        <p></p>
                        <p></p>
                    </li>
                    <li>【Log】新增、編輯、操作還原密碼、啟用/停用帳號成功時，記得存成Log紀錄</li>
                </ol>

            </div>
        </div>
    </div>

    <!-- 畫面 -->
    <div class="container">
        <h2 class="text-center">帳號權限管理</h2>
        <!-- 提示文字 -->
        <div class="row">
            <?php if($_GET[error] == 1){ ?>
                <div class="col-lg-4 alert alert-danger text-center" role="alert">
                    <strong>【Error】沒有可匯出的資料</strong>
                </div>
            <?php } elseif ($_GET[error] == 2) { ?>
                <div class="col-lg-4 alert alert-danger text-center" role="alert">
                <strong>Error</strong>Error！！
                </div>
            <?php } elseif ($_GET[success]) { ?>
                <div  class="col-lg-4 alert alert-success text-center" role="alert">
                <strong>Success</strong>成功設置！！
                </div>
            <?php } else if($_GET['error'] == 3) { ?>
                <div class="col-lg-4 alert alert-danger text-center" role="alert">
                    <strong>時間範圍過大，請設定在1天以內</strong>
                </div>
            <?php } ?>
	    </div>
        <!-- 帳號創建&一覽 -->
        <div class="row">
            <div class="col-12">
                <form id="add-member"  method="post" action="">
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
                            <button type="submit" class="btn btn-primary mb-2">新增</button>
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
                            <tr>
                                <td>1</td>
                                <td>
                                    <i class="fas fa-user" data-toggle="tooltip" data-placement="bottom" title="啟用"></i>
                                </td>
                                <td>userA</td>
                                <td>AO_YYY</td>
                                <td>超級管理員</td>
                                <td>
                                    
                                    <a href="#" data-toggle="modal" data-target="#editModal" data-target="#editModal" data-placement="bottom" title="編輯"><i class="fas fa-pencil-alt"></i></a>
                                    <a onclick="reset(1)" href="#" data-toggle="tooltip" data-placement="bottom" title="還原密碼"><i class="fas fa-sync-alt"></i></a>
                                    <a onclick="stay(1)" href="#" data-toggle="tooltip" data-placement="bottom" title="停用帳號"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <i class="fas fa-user no-use" data-toggle="tooltip" data-placement="bottom" title="停用"></i>
                                </td>
                                <td>userB</td>
                                <td>AO_XXX</td>
                                <td>一般管理員</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#editModal" data-placement="bottom" title="編輯"><i class="fas fa-pencil-alt"></i></a>
                                    <a onclick="reset(2)" href="#" data-toggle="tooltip" data-placement="bottom" title="還原密碼"><i class="fas fa-sync-alt"></i></a>
                                    <a onclick="active(2)" href="#" data-toggle="tooltip" data-placement="bottom" title="啟用帳號"><i class="fas fa-user-plus"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <i class="fas fa-user no-use" data-toggle="tooltip" data-placement="bottom" title="停用"></i>
                                </td>
                                <td>YaleCommunity</td>
                                <td>耶魯社區管理帳號</td>
                                <td>客戶</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#editModal" data-placement="bottom" title="編輯"><i class="fas fa-pencil-alt"></i></a>
                                    <a onclick="reset(3)" href="#" data-toggle="tooltip" data-placement="bottom" title="還原密碼"><i class="fas fa-sync-alt"></i></a>
                                    <a onclick="active(3)" href="#" data-toggle="tooltip" data-placement="bottom" title="啟用帳號"><i class="fas fa-user-plus"></i></a>
                                </td>
                            </tr>
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
                        <form id="edit-member" method="post" action="">
                            <div class="form-group">
                                <label for="id" class="col-form-label">帳號</label>
                                <input type="text" name="id" class="form-control" required placeholder="帳號">
                            </div>
                            <div class="form-group">
                                <label for="cname" class="col-form-label">姓名</label>
                                <input type="text" name="cname" class="form-control" required placeholder="姓名">
                            </div>
                            <div class="form-group">
                                <label for="data_type" class="col-form-label">權限</label>
                                <select name="data_type" class="form-control" required>
                                    <option value="">請選擇</option>
                                    <option value="1">超級管理員</option>
                                    <option value="2">一般管理員</option>
                                    <option value="3">客戶</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">返回</button>
                                <button type="submit" class="btn btn-primary" onclick="editMember(1)">確認更新</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>


    <script>
        function reset(sn){
            var msg = "確認提示\n您確定要密碼嗎?";
            if(confirm(msg))
            {
                location.replace("model/admin_reset.php?sn="+sn+"&type=pwd");
            }
        }
        function active(sn){
		    var msg = "確認提示\n啟用後，恢復先前操作本系統之使用權限，\n並還原預設密碼：88888 (請務必變更)\n確定要啟用嗎?";
            if(confirm(msg))
            {
                location.replace("model/admin_reset.php?sn="+sn+"&type=active");
            }
        }
        function stay(sn){
            var msg = "確認提示\n帳號將停用\n您確定要停用嗎?";
            if(confirm(msg))
            {
                location.replace("model/admin_reset.php?sn="+sn+"&type=stay");
            }
        }
        function editMember(sn){
            var msg = "是否變更資料？";
            return confirm(msg);
        }
    </script>
<?php include('footer_layout.php'); ?>