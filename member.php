<?php
include('header_layout.php');
include('nav.php');
include('chk_log_in.php');
// 99 合創 1 超級管理員 2 一般管理員 3 客戶
$sql = "SELECT a.data_type, a.id FROM admin a";
$stmt = $PDOLink->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$admin99_arr = array();
$admin1_arr = array();
$admin2_arr = array();
$admin3_arr = array();
foreach ($result as $result) {
	if ($result["data_type"] == 3) {
		$admin3_arr[] = $result["id"];
	} else if ($result["data_type"] == 2) {
		$admin2_arr[] = $result["id"];
	} else if ($result["data_type"] == 1) {
		$admin1_arr[] = $result["id"];
	} else {
		$admin99_arr[] = $result["id"];
	}
}
?>

<section id="main" class="wrapper">
	<h2>管理中心</h2>
	<div class="col-12">&nbsp;</div>
	<div class="inner">
		<div class="col-12">
			<div class="row member">
				<?php // 客戶 ?>
				<?php if (in_array($_SESSION['admin_user']['id'], $admin3_arr)) { ?>
					<div  class="col-4 text-center">
						<a class="my_product" href="parking_check.php">
							<img class="d-block mb-3 mx-auto" src="images/01查詢.png">
							<h4>查詢</h4>
						</a>
					</div>
					<div class="col-4 text-center">
						<a class="my_product" href="admin_edit.php">
							<img class="d-block mb-3 mx-auto" src="images/10管理員密碼變更.png">
							<h4>管理員密碼變更</h4>
						</a>
					</div>
					<div class="col-4 text-center">
						<a class="my_product" href="report.php">
							<img class="d-block mb-3 mx-auto" src="images/11報表匯出.png">
							<h4>報表匯出</h4>
						</a>
					</div>
					<?php // 一般管理員 ?>
				<?php } else if (in_array($_SESSION["admin_user"]["id"], $admin2_arr)) { ?>
						<div class="col-3 text-center">
							<a class="my_product" href="parking_check.php">
								<img class="d-block mb-3 mx-auto" src="images/01查詢.png">
								<h4>查詢</h4>
							</a>
						</div>
						<div class="col-3 text-center">
							<a class="my_product" href="parking_setup.php">
								<img class="d-block mb-3 mx-auto" src="images/04設定.png">
								<h4>設定</h4>
							</a>
						</div>
						<div class="col-3 text-center">
							<a class="my_product" href="admin_edit.php">
								<img class="d-block mb-3 mx-auto" src="images/10管理員密碼變更.png">
								<h4>管理員密碼變更</h4>
							</a>
						</div>
						<div class="col-3 text-center">
							<a class="my_product" href="report.php">
								<img class="d-block mb-3 mx-auto" src="images/11報表匯出.png">
								<h4>報表匯出</h4>
							</a>
						</div>
					<?php // 超級管理員 ?>
				<?php } else if (in_array($_SESSION["admin_user"]["id"], $admin1_arr)) { ?>
							<div class="col-2 text-center">
								<a class="my_product" href="parking_check.php">
									<img class="d-block mb-3 mx-auto" src="images/01查詢.png">
									<h4>查詢</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="parking_setup.php">
									<img class="d-block mb-3 mx-auto" src="images/04設定.png">
									<h4>設定</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="admin_edit.php">
									<img class="d-block mb-3 mx-auto" src="images/10管理員密碼變更.png">
									<h4>管理員密碼變更</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="report.php">
									<img class="d-block mb-3 mx-auto" src="images/11報表匯出.png">
									<h4>報表匯出</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="account_manage.php">
									<img class="d-block mb-3 mx-auto" src="images/帳號權限管理.png">
									<h4>帳號權限管理</h4>
								</a>
							</div>
					<?php // 合創 ?>
				<?php } else { ?>
							<div class="col-2 text-center" >
								<a class="my_product" href="parking_check.php">
									<img class="d-block mb-3 mx-auto" src="images/01查詢.png">
									<h4>查詢</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="parking_setup.php">
									<img class="d-block mb-3 mx-auto" src="images/04設定.png">
									<h4>設定</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="admin_edit.php">
									<img class="d-block mb-3 mx-auto" src="images/10管理員密碼變更.png">
									<h4>管理員密碼變更</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="report.php">
									<img class="d-block mb-3 mx-auto" src="images/11報表匯出.png">
									<h4>報表匯出</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="account_manage.php">
									<img class="d-block mb-3 mx-auto" src="images/帳號權限管理.png">
									<h4>帳號權限管理</h4>
								</a>
							</div>
							<div class="col-2 text-center">
								<a class="my_product" href="log_view.php">
									<img class="d-block mb-3 mx-auto" src="images/12log記錄.png">
									<h4>Log 紀錄</h4>
								</a>
							</div>
				<?php } ?>
			</div>
		</div>
		<br><br><br>
</section>
<?php include('footer_layout.php'); ?>