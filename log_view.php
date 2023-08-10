<?php 
	ob_start();
	include('config/db.php');
	include('chk_log_in.php');
	include('header_layout_report.php');
	include('nav.php');
	$id = $_SESSION["admin_user"]["id"];
	$sql_admin = "SELECT a.data_type FROM admin a WHERE id = ?";
	$stmt = $PDOLink->prepare($sql_admin);
	$stmt->bindParam(1, $id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	// 判斷權限狀態不是99的不可進入此頁面
	if ($result["data_type"] != 99) {
		header("location: index.php");
		exit();
	}
    $page = 15;   // 每頁顯示多少筆資料
	$currentpage = isset($_GET["page"]) ? (int)$_GET["page"] : 1;   // 當前頁碼
	$off = ($currentpage - 1) * $page;   // 開始顯示資料的起始點
	$sql_total = "SELECT COUNT(*) FROM log_list";
	$total = $PDOLink->query($sql_total);
	$result_total = $total->fetchColumn();
	$pages = ceil($result_total / $page);   // 總頁數
	$sql = "SELECT li.content, li.add_date, li.id FROM log_list li ORDER BY add_date DESC LIMIT :offset, :page";
	$stmt = $PDOLink->prepare($sql);
	$stmt->bindParam(":offset", $off, PDO::PARAM_INT);
	$stmt->bindParam(":page", $page, PDO::PARAM_INT);
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$show_pages = 10;                                   // 最初顯示的頁數和之後每次點擊的頁數
	if ($currentpage % $show_pages == 0) {
		$start_page = $currentpage - $show_pages + 1;   // 頁碼開始
		$end_page = $currentpage;                       // 頁碼結束
	} else {
		$start_page = ((int)($currentpage / $show_pages) * $show_pages) + 1;
		$end_page = $start_page + $show_pages - 1;
	}
	if ($end_page > $pages) {
		$end_page = $pages;
	}
	$pagesize  = 15;
	$opt_start = 2020;
	$kw_start  = $_GET['kw_start'];
	$kw_end    = $_GET['kw_end'];
	// 給初值 -- 20200330
 	if($kw_start == "") { $kw_start = date('Y-m-d'); }
	if($kw_end   == "") { $kw_end   = date('Y-m-d'); }
	if($sel_year_start  == "") { $sel_year_start  = date('Y'); }
	if($sel_year_end    == "") { $sel_year_end    = date('Y'); }
	if($sel_month_start == "") { $sel_month_start = date('m'); }
	if($sel_month_end   == "") { $sel_month_end   = date('m'); }
	if($sel_year_all == "") { $sel_year_all = date('Y'); }
	$sql_kw    = "";
	$get_tab   = $_GET['get_tab'];
	if($get_tab == '') {
		$get_tab = 'day';
	}
?>
<section id="main" class="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 mb-0 ">Log記錄</h2>
          </div>
          <div class="col-12 back-btn">
            <a href="member.php">
              <i class="fas fa-chevron-circle-left fa-3x"></i>
              <label class="previous"></label>
            </a>
          </div>
          <!--標籤 INDEX用-->
        <div class="container-fluid">
          <div class="inner">
            <div class="row">
            <!--付款明細查詢-->
            <div class="container-fluid table-mar">
    	      <div class="row ">
	            <div class="container-fluid">
                    <div class="row ">
                      <div class="col-lg-12 mb-4">
                        <div class="card shadow mb-4">
                          <div class="card-body">
                            <div class="tab-content tabcontent-border">
							  <div class="tab-pane show" id="day" role="tabpanel">
								<form id='mform1' class="form-center search-mar" action="log_view.php?get_tab=day" method="get">
									<input type='hidden' name='get_tab' value='day'>
                                  <div class="row">
                                </div>                        
                                </form>
                              <div class="row  btn-mar">
                                <table class="table container-fluid ">
                                  <thead class="thead-dark ">
                                    <tr>
                                      <th scope="col">#</th>
                                      <!-- <th scope="col">使用者cname</th>
									  <th scope="col">帳號id</th> -->
                                      <th scope="col">訊息</th>
                                      <th scope="col">時間</th> 
                                    </tr>
                                  </thead>
                                  <tbody class="text-black">
                                    <?php
										$count = ($currentpage - 1) * $page + 1;
                                        foreach ($result as $row) {
                                            echo "<tr>";
                                            echo "<td>{$count}</td>";
                                            echo "<td>{$row['content']}</td>";
                                            echo "<td>{$row['add_date']}</td>";
                                            echo "</tr>";
											$count = $count + 1;
                                        }
                                    ?>	
                                  </tbody>
                                </table>
                              </div>
                              <?php // 日期跳頁 上下頁 ?>
                              <div class="row ">
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
									  <?php
										// 回首頁
										if ($currentpage > 1) {
											echo "<a href = 'log_view.php?page=1'>首頁</a> ";
										}
										// 上一頁
										if ($currentpage > 1) {
											echo "<a href = 'log_view.php?page=" . ($currentpage - 1) . "'>上一頁</a> ";
										}
										// 中間的頁數
										for ($i = $start_page; $i <= $end_page; $i++) {
											if ($i == $currentpage) {
												echo $i . " ";
											} else {
												echo "<a href = 'log_view.php?page=" . $i . "'>" . $i . "</a> ";
											}
										}
										// 下一頁
										if ($currentpage < $pages) {
											echo "<a href = 'log_view.php?page=" . ($currentpage + 1) . "'>下一頁</a> ";
										}
										// 末頁
										if ($currentpage < $pages) {
											echo "<a href = 'log_view.php?page=" . $pages . "'>末頁</a>";
										}
									  ?>
                                  </div>
                                </div>
                              </div>
                              </div>
                              <div class="tab-pane pad" id="month" role="tabpanel">
								<form id='mform2' class="form-center search-mar" action="log_view.php?get_tab=month" method="get">
									<input type='hidden' name='get_tab' value='month'>
                                  <div class="row">
                                    <span class="h5 span-mar mb-0 font-weight-bold col-lg-12">開始月份</span>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                      <select name="sel_year_start" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
                                      </select>
                                    </div>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_month_start" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
                                      </select>
                                    </div>
                                    <span class="h5 span-mar mb-0 font-weight-bold col-lg-12">結束月份</span>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                      <select name="sel_year_end" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
                                      </select>
                                    </div>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_month_end" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="text-center btn-mar ">
                                    <button type="submit" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                 
                                </form>
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a" onclick='export2excel2()'>
                                    <i class="fas fa-download fa-sm "></i>匯出 Download
                                  </button>
                                </div>
                                <div class="row  btn-mar">
                                  <table class="table container-fluid ">
                                    <thead class="thead-dark ">
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">日期</th>
                                        <th scope="col">付款金額</th>
                                        <th scope="col">退費金額</th> 
                                        <th scope="col">小計</th>
                                        <th scope="col">備註</th>
                                      </tr>
                                    </thead>
                                    <tbody class="text-black">
                                    </tbody>
                                  </table>
                                </div>
                              <div class="row ">
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
                                  </div>
                                </div>
                              </div>
                              </div>
                              <div class="tab-pane pad" id="year" role="tabpanel">
								<form id='mform3' class="form-center search-mar" action="log_view.php?get_tab=year" method="get">
									<input type='hidden' name='get_tab' value='year'>
                                  <div class="row">
                                    <div class="form-group  col-lg-12">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                    <select name="sel_year_all" class="form-control  form-control2 ">
                                      <option value="">請選擇</option>
                                    </select>
                                    </div>
                                  </div>
                                  <div class="text-center btn-mar ">
                                    <button type="submit" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                 
                                </form>
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a" onclick='export2excel3()'>
                                    <i class="fas fa-download fa-sm "></i>匯出 Download
                                  </button>
                                </div>
                                <div class="row  btn-mar">
                                  <table class="table container-fluid ">
                                    <thead class="thead-dark ">
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">月份</th>
                                        <th scope="col">付款金額</th>
                                        <th scope="col">退費金額</th> 
                                        <th scope="col">小計</th>
                                        <th scope="col">備註</th>
                                      </tr>
                                    </thead>
                                    <tbody class="text-black">
                                     <tr>
                                    </tbody>
                                  </table>  
                                </div>
                              <div class="row ">
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
                                  </div>
                                </div>
                              </div>
                              </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
    	        </div>
            </div>
          </div>
        </div>
      </div>
        </div>
    </div>
</section>
<!--20200323 新增-->
		<!-- Bootstrap core JavaScript-->
		<script src="assets/js/jquery1.min.js"></script>
		<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- Core plugin JavaScript-->
		<script src="assets/js/jquery.easing.min.js"></script>
		<!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
  <!-- Page level plugins -->
  <script src="assets/js/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/js/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
	var get_tab = '<?php echo $get_tab ?>';
	switch(get_tab) {
		case 'day':
			$('#day').addClass('active');
			$('#month').removeClass('active');
			$('#year').removeClass('active');
			$('#tab_day').click();
			break;
		case 'month':
			$('#tab_month').click();
			break;
		case 'year':
			$('#tab_year').click();
			break;
		default:
			$('#tab_day').click();
			break;
	}
});
function export2excel1() {
	$('#mform1').prop('action', 'excelmember_report_save1.php');
	$('#mform1').prop('method', 'get');
	$('#mform1').submit();
	$('#mform1').prop('action', 'report.php');
	return false;
}
function export2excel2() {
	$('#mform2').prop('action', 'excelmember_report_save2.php');
	$('#mform2').prop('method', 'get');
	$('#mform2').submit();
	$('#mform2').prop('action', 'log_view.php');
	return false;
}
function export2excel3() {
	$('#mform3').prop('action', 'excelmember_report_save3.php');
	$('#mform3').prop('method', 'get');
	$('#mform3').submit();
	$('#mform3').prop('action', 'log_view.php');
	return false;
}
// 檢查用戶權限
setInterval(function() {
        fetch("/check_status.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ id: "<?php echo $_SESSION["admin_user"]["id"]; ?>" })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "X") {
                window.location.href = "index.php";
            }
        });
    }, 1000);
</script>
  <?php include('footer_layout.php'); ?>