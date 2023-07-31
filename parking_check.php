<?php 

include('header_layout.php');
include('nav.php');
include('chk_log_in.php');

$langEnglishValue = $lang->line("index.if_if");

if($admin_id) { ?>

<!-- 教官中心 教官查詢  -->
<section id="main" class="wrapper">

	<h2 style="margin-top: -30px;" align="center">查詢</h2>
	<div class="col-12"><a href="member.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>

	<div class="inner">
		<div class="row">
			
			<div class="col-12">&nbsp;</div>
			
			<!-- 改成圖示 -->	
			
			<div class="col-lg-6 col-6 p-4 text-center"> 
				<a class="my_product" href="RoomList.php">
				<img class="d-block mb-3 mx-auto" src="images/03系統使用現況.png">
				<h4>系統使用現況</h4> </a> <p class="text-muted">系統使用現況</p>
			</div>
			<div class="col-lg-6 col-6 p-4 text-center"> 
				<a class="my_product" href="MemberEZCardRecord.php?betton_color=primary">
				<img class="d-block mb-3 mx-auto" src="images/02付款紀錄查詢.png">
				<h4>付款紀錄查詢</h4> </a> <p class="text-muted">付款紀錄查詢</p>
			</div>
		</div>
	</div>

</section>

<!-- 教官中心 End -->
<?php } ?>
<?php // iframe('');?>
<?php include('footer_layout.php'); ?>      