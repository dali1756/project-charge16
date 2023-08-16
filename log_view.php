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
// 權限狀態不是99的不可進入此頁面
if ($result["data_type"] != 99) {
	header("location: index.php");
	exit();
}
$page = 15; // 每頁顯示多少筆資料
$currentpage = isset($_GET["page"]) ? (int) $_GET["page"] : 1; // 當前頁碼
$off = ($currentpage - 1) * $page; // 開始顯示資料的起始點
$sql_total = "SELECT COUNT(*) FROM log_list";
$total = $PDOLink->query($sql_total);
$result_total = $total->fetchColumn();
$pages = ceil($result_total / $page); // 總頁數
$sql = "SELECT li.content, li.add_date, li.id FROM log_list li ORDER BY add_date DESC LIMIT :offset, :page";
$stmt = $PDOLink->prepare($sql);
$stmt->bindParam(":offset", $off, PDO::PARAM_INT);
$stmt->bindParam(":page", $page, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$show_pages = 10; // 最初顯示的頁數和之後每次點擊的頁數
if ($currentpage % $show_pages == 0) {
	$start_page = $currentpage - $show_pages + 1; // 頁碼開始
	$end_page = $currentpage; // 頁碼結束
} else {
	$start_page = ((int) ($currentpage / $show_pages) * $show_pages) + 1;
	$end_page = $start_page + $show_pages - 1;
}
if ($end_page > $pages) {
	$end_page = $pages;
}
$pagesize = 15;
$opt_start = 2020;
$kw_start = $_GET['kw_start'];
$kw_end = $_GET['kw_end'];
// 給初值 -- 20200330
if ($kw_start == "") {
	$kw_start = date('Y-m-d');
}
if ($kw_end == "") {
	$kw_end = date('Y-m-d');
}
if ($sel_year_start == "") {
	$sel_year_start = date('Y');
}
if ($sel_year_end == "") {
	$sel_year_end = date('Y');
}
if ($sel_month_start == "") {
	$sel_month_start = date('m');
}
if ($sel_month_end == "") {
	$sel_month_end = date('m');
}
if ($sel_year_all == "") {
	$sel_year_all = date('Y');
}
$sql_kw = "";
$get_tab = $_GET['get_tab'];
if ($get_tab == '') {
	$get_tab = 'day';
}
?>
<section id="main" class="wrapper">
	<!-- Content Wrapper -->
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
			<div class="container-fluid">
				<div class="mb-4">
					<h2 class="h3 mb-0 ">Log記錄</h2>
				</div>
				<div class="col-12 back-btn">
					<a href="member.php">
						<i class="fas fa-chevron-circle-left fa-3x"></i>
						<label class="previous"></label>
					</a>
				</div>
				<!--標籤 INDEX用-->
				
					<div class="inner">
						<div class=" col-12">
							<!--付款明細查詢-->

							<div class="row">
								<table class="table ">
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

						</div>

						

						<div class="col-12 text-right ">
							<?php // 日期跳頁 上下頁 ?>


							<div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
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
				<br><br><br>
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


	// 檢查用戶權限
	setInterval(function () {
		fetch("/check_status.php", {
			method: "POST",
			headers: { "Content-Type": "application/json" },
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