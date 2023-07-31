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
	<div class="col-12"><a href="check.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
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
			
			<!-- <div class="col-12"><a href="check.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div> -->
			
			<!-- 改成圖示 -->
			<div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="MemberPowerRecord.php?betton_color=primary">
				<img class="d-block mb-3 mx-auto" src="images/06用電紀錄.png">
				<h4><?php echo $lang->line("index.system-use-condition_state_history"); ?></h4> </a> <p class="text-muted"><?php echo $lang->line("index.system-use-condition_state_history"); ?></p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="MemberEZCardRecord.php?betton_color=primary">
				<img class="d-block mb-3 mx-auto" src="images/09付款紀錄.png">
				<h4><?php echo $lang->line("index.stored_value_query"); ?></h4> </a> <p class="text-muted"><?php echo $lang->line("index.stored_value_query"); ?></p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center">
				<a class="my_product" href="MemberStudentBalance.php">
				<img class="d-block mb-3 mx-auto" src="images/07餘額紀錄.png">
				<h4><?php echo $lang->line("index.balance_list"); ?></h4> </a> <p class="text-muted"><?php echo $lang->line("index.balance_list"); ?></p>
			</div>
			<!-- 只看到一塊 -->
		</div>
	</div>
	
</section>
<!-- 教官中心 End -->
<?php } ?>

<?php include('footer_layout.php'); ?>      