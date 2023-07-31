<?php 

include('header_layout.php');
include('nav.php');
include('chk_log_in.php');

$langEnglishValue = $lang->line("index.if_if");

if($admin_id) { ?>
<!-- 教官中心 教官查詢  -->
<section id="main" class="wrapper">
	
	<h2 style="margin-top: -30px;" align="center">時段設定</h2>
	<div class="col-12"><a href="parking_setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></a></div>
	
	<div class="inner">
		<div class="row">
			
			<div class="col-12">&nbsp;</div>
			
			<div class="col-lg-6 col-6 p-4 text-center"> 
				<a class="my_product" href="schedule_peak.php">
				<img class="d-block mb-3 mx-auto" src="images/09離峰時段.png">
				<h4>離峰時段設定</h4> </a> <p class="text-muted">離峰時段設定</p>
			</div>
			<div class="col-lg-6 col-6 p-4 text-center"> 
				<a class="my_product" href="schedule_offpeak.php">
				<img class="d-block mb-3 mx-auto" src="images/07尖峰時段.png">
				<h4>一般時段設定</h4> </a> <p class="text-muted">一般時段設定</p>
			</div>
			<!-- 
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomList4.php">
				<img class="d-block mb-3 mx-auto" src="images/06一般費率.png">
				<h4>費率設定</h4> </a> <p class="text-muted">費率設定</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomList1.php">
				<img class="d-block mb-3 mx-auto" src="images/11455398918529.png">
				<h4>時段設定</h4> </a> <p class="text-muted">時段設定</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="prepaid.php">
				<img class="d-block mb-3 mx-auto" src="images/07預收度數.png">
				<h4>預付設定</h4> </a> <p class="text-muted">預付設定</p>
			</div>
			-->
		</div>
	</div>

</section>
<!-- 教官中心 End -->
<?php } ?>
<?php // iframe('');?>
<?php include('footer_layout.php'); ?>      