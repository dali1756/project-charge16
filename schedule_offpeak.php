<?php 
include('header_layout.php'); 
include('nav.php'); 
include('chk_log_in.php'); 
$pagesize = 10;
$sql  = "SELECT s.*, sc.starttime, sc.endtime FROM `seat` s
		 LEFT JOIN `schedule` sc ON sc.seat_id = s.id WHERE sc.weeknumber NOT IN (0, 6) GROUP BY `charging_type`";
$rs   = $PDOLink->query($sql);
$row  = $rs->fetchAll();
if($row) {
	$btn_name = "修改";
	$sql   = "SELECT (SELECT COUNT(*) FROM `schedule` WHERE weeknumber = '6' AND `enable` = 1) as 'ps6', 
					 (SELECT COUNT(*) FROM `schedule` WHERE weeknumber = '0' AND `enable` = 1) as 'ps0'";
	$rsrs  = $PDOLink->query($sql);
	$tmp   = $rsrs->fetch();
	$ps6   = $tmp['ps6'];
	$ps0   = $tmp['ps0'];
} else {
	$btn_name = "新增";
	// initial 
	$row['mode']    = 1;
	$row['rate']    = 1;
	$row['prepaid'] = 1;
	$row['starttime'] = date('H:i');
	$row['endtime']   = date('H:i');
}
$s_option = "<option value='%s' %s>%s</option>";
for($i=1; $i < 5; $i++) {
	if($i == 2) continue; // 保留
	$tmp = ($row['mode'] == $i) ? 'selected' : '';
	$mode_opt .= sprintf($s_option, $i, $tmp, get_mode($i));
}
// weekdays
for($i=0; $i < 7; $i++) {
	$tmp = ($row['weeknumber'] == $i) ? 'selected' : '';
	$week_opt .= sprintf($s_option, $i, $tmp, get_weekday($i));
}
?>
<!-- 教官查詢房號  -->
<section id="main" class="wrapper">
	<h2 style="margin-top: -30px;" align="center">一般時段設定</h2>
	<div class="col-12"><a href="parking_schedule.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>【Error】目前已套用此設定，無須重新設定。</strong>
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
			<div class="col-12">
				<form id='mform' action="schedule_offpeak_update.php" method="post" accept-charset="utf-8">
					<table class="table">
						<tr>
							<td colspan='4' align='center'>&nbsp;</td>
						</tr>
						<tr>
							<td align="right" width='10%'>
								開始時間 : 
							</td>
							<td width='40%'>
								<input disabled class="form-control" type="time" name="start_time" placeholder="hrs:mins" value="<?php echo $row[0]['endtime'] ?>">
							</td>
							<td align="right" width='10%'>
								結束時間 : 
							</td>
							<td>
								<input disabled class="form-control" type="time" name="end_time" placeholder="hrs:mins" value="<?php echo $row[0]['starttime'] ?>">
							</td>
						</tr>
						<tr>
							<td colspan='4' align='center'>汽車設定&nbsp;</td>
						</tr>
						<tr>
							<td align='right'>預付 (度) : </td>
							<td align='center'>
								<input type='text' name='prepay' class="form-control" value='<?php if ($row[0]["charging_type"] == 1) { echo $row[0]['prepaid']; } ?>' 
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
							<td align='right'>費率 : </td>
							<td align='center'>
								<input type='text' name='rate' class="form-control" value='<?php if ($row[0]["charging_type"] == 1) { echo $row[0]['rate']; } ?>' 
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
						</tr>
						<tr>
							<td align="right" width='10%'>預付金額 : </td><td colspan='3' align='left' width='40%' id='prepay'></td>
						</tr>
						<tr>
							<td colspan='4' align='center'>機車設定&nbsp;</td>
						</tr>
						<tr>
							<td align='right'>預付 (度) : </td>
							<td align='center'>
								<input type='text' name='prepay_mo' class="form-control" value='<?php if ($row[1]["charging_type"] == 2) { echo $row[1]['prepaid']; } ?>' 
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
							<td align='right'>費率 : </td>
							<td align='center'>
								<input type='text' name='rate_mo' class="form-control" value='<?php if ($row[1]["charging_type"] == 2) { echo $row[1]['rate']; } ?>' 
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
						</tr>
						<tr>
							<td align="right" width='10%'>預付金額 : </td><td colspan='3' align='left' width='40%' id='prepay_mo'></td>
						</tr>
						<tr>
							<td colspan='4' align='center'><button type='submit' id='btn_submit' class='form-control btn-primary'><?php echo $btn_name ?></button></td>
						</tr> 
					</table>	
					<input type='hidden' name='act' value='<?php echo $act ?>'>
				</form>
			</div>
		</div>
	</div>
</section>
<style>
.table>tbody>tr>td{
    vertical-align: middle;
}
</style>
<script>
function back() {
	history.go(-1);
}
$(document).ready(function() {
	$(document).on('submit','#mform', function() {
		if(!confirm("確定設定嗎?")) {
			return false;
		}
	});
	$("input").keyup(function(){
		calc_result();
		calc_result_mo();
	});
	// 汽車
	function calc_result() {
		var prepaid = $('input[type=text][name=prepay').val();
		var rate    = $('input[type=text][name=rate').val();
		$('#prepay').html( prepaid * rate );
	}
	calc_result();
	// 機車
	function calc_result_mo() {
		var prepaid = $('input[type=text][name=prepay_mo').val();
		var rate    = $('input[type=text][name=rate_mo').val();
		$('#prepay_mo').html( prepaid * rate );
	}
	calc_result_mo();
});
</script>
<?php include('footer_layout.php'); ?>