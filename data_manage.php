<?php 

include('header_layout.php');
include('nav.php');
include('chk_log_in.php');

$url;

if($admin_id) { ?>
<?php $langEnglishValue = $lang->line("index.if_if"); ?>

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
			
			<!-- <div class="container"><a href="setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div> -->
			
			<!-- 改成圖示 -->
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="member_new_users.php">
				<img class="d-block mb-3 mx-auto" src="images/17建立新學生.png">
				<h4>建立新學生</h4> </a> <p class="text-muted">建立新學生</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="MemberStudent.php">
				<img class="d-block mb-3 mx-auto" src="images/18修改學生資料.png">
				<h4>修改學生資料</h4> </a> <p class="text-muted">修改學生資料</p>
			</div>
			<div class="col-lg-4 col-6 p-4 text-center"> 
				<a class="my_product" href="admin_users.php">
				<img class="d-block mb-3 mx-auto" src="images/16名單管理建立.png">
				<h4>名單管理建立</h4> </a> <p class="text-muted">名單管理建立</p>
			</div>
		</div>
	</div>
	
</section>
<!-- 教官中心 End -->
<?php } ?>

<?php include('footer_layout.php'); ?>      