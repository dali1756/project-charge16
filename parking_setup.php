<?php 
include('header_layout.php');
include('nav.php');
include('chk_log_in.php');
	// 從登入後開始看,id抓session來的id,result結果是陣列所以要用["要抓的欄位"]
	$id = $_SESSION["admin_user"]["id"];
	$sql = "SELECT a.data_type FROM admin a WHERE id = ?";
	$stmt = $PDOLink->prepare($sql);
	$stmt->bindParam(1, $id);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result["data_type"] == 3) {
		header("location: index.php");
		exit();
	}
$langEnglishValue = $lang->line("index.if_if");
if($admin_id) { ?>
<!-- 教官中心 教官查詢  -->
<section id="main" class="wrapper">
	<h2 style="margin-top: -30px;" align="center">設定</h2>
	<div class="col-12"><a href="member.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></a></div>
	<div class="inner">
		<div class="row">
			<div class="col-12">&nbsp;</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="parking_schedule.php">
				<img class="d-block mb-3 mx-auto" src="images/06時段設定.png">
				<h4>時段設定</h4> </a> <p class="text-muted">時段設定</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomList3.php">
				<img class="d-block mb-3 mx-auto" src="images/05模式設定.png">
				<h4>模式設定</h4> </a> <p class="text-muted">模式設定</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomList2.php">
				<img class="d-block mb-3 mx-auto" src="images/08退費設定.png">
				<h4>退費設定</h4> </a> <p class="text-muted">退費設定</p>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<?php include('footer_layout.php'); ?>      