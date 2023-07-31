<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<style>
	.ao_room_box {
		width: 100%;
		margin: 0 35% 60%;
	}
	.ao_room_box_main {
		float: left;
		width:  100%;
	}

	@media only screen and (min-width: 320px) and (max-width: 1200px){

		.ao_room_box {
			width: 100%;
			margin: 0 8%;
			margin-bottom: 200%;
		}
		.ao_room_box_main {      
			float: left;
			width:  100%;   
			/*background-color: #000;*/
		} 
   
	}
</style>
<section  id="main" class="wrapper">
	<!-- <div class="rwd-box"></div><br><br> -->
	<div class="col-12"><a href='normal_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a></div>
	<h2 style="margin-top: -30px;" align="center">公告事項</h2><br>
	<div class="row">
	<?php if($_GET[success]){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>系統已成功設定!!</strong>
		</div>
	<?php } elseif ($_GET[error] == 1) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>你打錯了唷!!</strong>
		</div>
	<?php } ?>
	</div>
	<div class="ao_room_box">
		<!-- <a href='normal_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a> -->
		<form action="system_upd.php" method=post>
			<div class="ao_room_box_main">        
			<?php 
				//過濾 房號
				$list_q="select * from system_info where sn = '1' ";
				$list_r = $PDOLink->prepare($list_q); 
				$list_r->execute();
				$rs = $list_r->fetch();     
				$sn = $rs[sn];  

				/* 智慧學校主系統設定 */
				$price_max = $rs[price_max];
				$contact = $rs[contact];

				/* PHP時區新用法 datetime 指定區間顯示處理 */
				// $start_datetime = new \DateTime();
				// $start_datetime->$rs[price_start_date];
				// $s_date = $start_datetime->format('Y-m-d H:i');
					$start_dates = date("Y-m-d",strtotime($rs[price_start_date]));

				// $end_datetime = new \DateTime();
				// $end_datetime->$rs[price_end_date];
				// $e_date = $end_datetime->format('Y-m-d H:i');
					$end_dates = date("Y-m-d",strtotime($rs[price_end_date]));

			?>
			<div class="ao_room_box_main">
			<?php 

				  // print "
				  // <div class='form-group'>
					// <label for='exampleInputEmail1'> ".$lang->line("index.maximum_of_stored_value")."  </label>
					// <input style='width: 300px;' class='form-control' type='text' name='price_max' value='".$price_max."'>
				  // </div>";

				  // print "
				  // <div class='form-group'>
					// <label for='exampleInputEmail1'> ".$lang->line("index.refund_period")." </label>
					// <input style='width: 300px;' class='form-control' type='date' name='price_start_date' value='".$start_dates."'><br>
					// <input style='width: 300px;' class='form-control' type='date' name='price_end_date' value='".$end_dates."'>
				  // </div>";

				  print "
				  <div class='form-group' style='width:500px'>
					<label for='exampleInputEmail1'> ".$lang->line("index.upcoming_vvents")."  </label>
						".get_edit2('contact',$contact,200,100)."
				  </div>";

			?>
			</div>

			<input type=hidden name=act>
			<input type=hidden name=id value="<?php echo $sn; ?>">
			<input type=hidden name=edit_sn>
			<button type="submit" class="btn btn-primary"><?php echo $lang->line("index.confirm_update"); ?></button>
			</form>
			<!-- 使用時數重製 -->
		</div>
	</div>
<!-- rwd bootstrap 頁面修正 -->
<!-- <div class="row">
	<div class="inner">
		<div class="col-12">
			
		</div>
	</div>
</div> -->
</section>

<script>
//回上一頁
function backs()
{
	history.go(-1);
}

</script>
<?php // iframe('');?>
<?php include('footer_layout.php'); ?>