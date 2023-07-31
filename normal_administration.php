<?php include('header_layout.php'); ?>                     
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>


<?php 

$url;

if($admin_id) { ?>
<?php $langEnglishValue = $lang->line("index.if_if"); ?>
<!-- 教官中心 教官查詢  -->
<section id="main" class="wrapper">
	<!-- <div class="rwd-box"></div><br>
	<div class="rwd-box"></div><br>  -->
	<div class="col-12"><a href="setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	<div class="inner">
		<div class="row">
			<div class="col-12">
				<div class="card">
				  <div class="member_header card-header"><!-- 教官資訊 -->
				  <?php 
				  if($_SESSION['admin_user']['id'] == 'andy') { 
 					echo '超級管理員';
				  } else {
			  	    echo $lang->line("index.instructor_info");
			  	  }
				  ?>
				  </div>
				  <ul class="list-group list-group-flush">
				    <li class="list-group-item">
				    	<!-- 教官帳號 -->
				    	<?php echo $lang->line("index.instructor_account"); ?>：<?php echo $_SESSION['admin_user']['id']; ?>
				    </li>
				    <li class="list-group-item">
				    	<?php echo $lang->line("index.instructor_name"); ?>：<?php echo $_SESSION['admin_user']['id']; ?>
				    </li>
				  </ul>
				</div>
			</div>
			
			<!-- <div class="col-12"><a href="setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div> -->
			
			<!-- 改成圖示 -->
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RefundTimeControl.php">
				<img class="d-block mb-3 mx-auto" src="images/21固定時段退費設定.png">
				<h4>固定時段退費設定</h4> </a> <p class="text-muted">固定時段退費設定</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RefundTimeNew.php">
				<img class="d-block mb-3 mx-auto" src="images/22指定時段退費設定.png">
				<h4>指定時段退費設定</h4> </a> <p class="text-muted">指定時段退費設定</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="notice.php">
				<img class="d-block mb-3 mx-auto" src="images/23公告事項.png">
				<h4>公告事項</h4> </a> <p class="text-muted">公告事項</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomControl2.php">
				<img class="d-block mb-3 mx-auto" src="images/24模式設定.png">
				<h4>收費模式設定</h4> </a> <p class="text-muted">收費模式設定</p>
			</div>
		</div>
	</div>
	
</section>
<!-- 教官中心 End -->
<?php } ?>

<?php include('footer_layout.php'); ?>      