<?php 

include('header_layout.php'); 
include('nav.php'); 
include('chk_log_in.php'); 
// include('RefundTimeFunction.php');

$pagesize = 10;

$row;
// $act = $_GET['act'];
// $sid = $_GET['sid'];
// $wid = $_GET['weekday'];

// $week_opt;
// $mode_opt;
// $btn_name;

$sql = "SELECT * FROM `machine` LIMIT 0, 1";
$rs  = $PDOLink->query($sql);
$row = $rs->fetch();

$prepaid = $row['prepaid'];

// $s_option = "<option value='%s' %s>%s</option>";

?>
<!-- 教官查詢房號  -->
<section id="main" class="wrapper">

	<h2 style="margin-top: -30px;" align="center">預付設定</h2>
	<div class="col-12"><a href="parking_setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>【Error】使用者行為錯誤</strong>
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>【Error】系統錯誤</strong>
		</div>
	<?php } elseif ($_GET[success] == 1) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>【Success】成功設置！！</strong> 
		</div>
	<?php } ?>
	</div>
	
	<div class="inner">
		<div class="row">
			<div class="col-6" style='margin:0 auto'>
				<form id='mform' action="prepaid_update.php" method="post" accept-charset="utf-8">
					<table class="table">
						<tr>
							<td colspan='2' align='center'>預設</td>
						</tr>
						<tr>
							<td align="right" width='10%'>預付設定 : </td>
							<td align='center' width='40%'>
								<input type='text' name='prepaid' value='<?php echo $prepaid ?>' 
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
						</tr>
						<tr>
							<td colspan='2' align='center'><input type='submit' id='btn_submit' class='form-control' value='確定'></td>
						</tr> 
					</table>
					
					<input type='hidden' name='act' value='<?php echo $act ?>'>
					<input type='hidden' name='sid' value='<?php echo $sid ?>'>
				</form>
			</div>
		</div>
	</div>
</section>

<script>

function back() {
	
	history.go(-1);
	
}
</script>

<?php include('footer_layout.php'); ?>