<?php 

include('header_layout.php');
include('nav.php');
include('chk_log_in.php');

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
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomList.php">
				<img class="d-block mb-3 mx-auto" src="images/04系統使用現況.png">
				<h4><?php echo $lang->line("index.room_air-condition_state"); ?></h4> </a> <p class="text-muted"><?php echo $lang->line("index.room_air-condition_state"); ?></p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="check_member.php">
				<img class="d-block mb-3 mx-auto" src="images/05學生使用紀錄.png">
				<h4><?php echo $lang->line("index.student_utilize_records"); ?></h4> </a> <p class="text-muted"><?php echo $lang->line("index.student_utilize_records"); ?></p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center">
				<!-- <a class="my_product" href="MemberEZCardRecordReport.php?betton_color=primary"> -->
				<a class="my_product" href="MemberEZCardRecordALL2.php?betton_color=primary">
				<img class="d-block mb-3 mx-auto" src="images/08報表匯出功能.png">
				<h4>報表匯出功能</h4> </a> <p class="text-muted">報表匯出功能</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="RoomMeterList.php">
				<img class="d-block mb-3 mx-auto" src="images/10電錶度數現況.png">
				<h4><?php echo $lang->line("index.room_power_state"); ?></h4> </a> <p class="text-muted"><?php echo $lang->line("index.room_power_state"); ?></p>
			</div>
			<!-- 只看到一塊 -->
		</div>
	</div>
	
</section>
<!-- 教官中心 End -->
<?php } ?>

<?php include('footer_layout.php'); ?>      