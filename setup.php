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
	<div class="col-12"><a href="member.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
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
			
			<!-- <div class="col-12"><a href="member.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div> -->
			
			<!-- 改成圖示 -->
			<!-- 20200117 -- 亞東無公用卡 -->
			<!-- <div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="PublicMemberStudent.php?betton_color=primary">
				<img class="d-block mb-3 mx-auto" src="images/13公用卡管理.png">
				<h4>公用卡管理</h4> </a> <p class="text-muted">公用卡管理</p>
			</div> -->
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="member_history.php?betton_color=primary">
				<img class="d-block mb-3 mx-auto" src="images/14非住宿生管理.png">
				<h4>非住宿生管理</h4> </a> <p class="text-muted">非住宿生管理</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="data_manage.php">
				<img class="d-block mb-3 mx-auto" src="images/15學生資料管理.png">
				<h4>學生資料管理</h4> </a> <p class="text-muted">學生資料管理</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="normal_administration.php">
				<img class="d-block mb-3 mx-auto" src="images/20一般系統管理.png">
				<h4>一般系統管理</h4> </a> <p class="text-muted">一般系統管理</p>
			</div>
			<!-- <div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="admin_edit.php">
				<img class="d-block mb-3 mx-auto" src="images/25個人資料管理.png">
				<h4>個人資料管理</h4> </a> <p class="text-muted">個人資料管理</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="RoomMeterList.php">
				<img class="d-block mb-3 mx-auto" src="images/magnifier.png">
				<h4>最高系統管理</h4> </a> <p class="text-muted">最高系統管理</p>
			</div> -->
			<!-- 只看到一塊 -->
		</div>
	</div>
	
</section>
<!-- 教官中心 End -->
<?php } ?>

<?php include('footer_layout.php'); ?>      