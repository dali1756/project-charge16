<?php 

include('header_layout.php'); 
include('nav.php'); 
include('chk_log_in.php'); 
// include('RefundTimeFunction.php');

$pagesize = 10;

$act = $_GET['act'];
$sid = $_GET['sid'];
$wid = $_GET['weekday'];

$sql = "SELECT * FROM `seat` WHERE id = '{$sid}'";
$rs  = $PDOLink->query($sql);
$tmp = $rs->fetch();

$ps  = $tmp['number'];
$ref = $tmp['refundcertification'];

$ref_opt;

$s_option = "<option value='%s' %s>%s</option>";

for($i=0; $i<=1; $i++) {
	
	$select  = ($ref == $i) ? "selected" : '';
	$ref_opt.= sprintf($s_option, $i, $select, get_refund($i));
}

?>
<!-- 教官查詢房號  -->
<section id="main" class="wrapper">

	<h2 style="margin-top: -30px;" align="center">退費設定</h2>
	<div class="col-12"><a href="RoomList2.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>【Error】設定失敗</strong>
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
		<div class="panel">
			<div class="panel-body col-6" style='margin:0 auto'>
				<form id='mform' action="setrefund_update.php" method="post" accept-charset="utf-8">
					<table class="table">
						<tr>
							<td colspan='2' style='text-align: center;'>車位號碼 : <?php echo $ps; ?></td>
						</tr>
						<tr>
							<td width='15%' nowrap>不認卡片退費設定 : </td>
							<td width='40%'><select name='refund'><?php echo $ref_opt ?></td>
						</tr> 
						<tr>
							<td colspan='4' align='center'>
								<button type='submit' id='btn_submit' class='form-control btn-primary'>確定</button>
							</td>
						</tr> 
					</table>
					
					<input type='hidden' name='act' value='<?php echo $act ?>'>
					<input type='hidden' name='sid' value='<?php echo $sid ?>'>
				</form>
			</div>
		</div>
	</div>
</section>

<style>
.table>tbody>tr>td{
	text-align: right;
    vertical-align: middle;
}
</style>

<script>

function back() {
	
	history.go(-1);
	
}
</script>

<?php include('footer_layout.php'); ?>