<?php 

include('header_layout.php'); 
include('nav.php'); 
include('chk_log_in.php'); 

$pagesize = 10;

$act;
$ps0; 
$ps6;

$week_opt;
$mode_opt;
$btn_name;

// $sql  = "SELECT * FROM `schedule` WHERE weeknumber = '1' AND `enable` = '1' LIMIT 0, 1";
$sql  = "SELECT * FROM `schedule` WHERE `enable` = '1' LIMIT 0, 1";
$rs   = $PDOLink->query($sql);
$row  = $rs->fetch();

if($row) {
	
	$btn_name = "修改";
	
	$act  = 'edit';

	// -- 20200406
	// $sql  = "SELECT (SELECT COUNT(*) FROM `schedule` WHERE weeknumber = '6' AND `enable` = 1) as 'ps6', 
					// (SELECT COUNT(*) FROM `schedule` WHERE weeknumber = '7' AND `enable` = 1) as 'ps0'";
	$sql  = "SELECT (SELECT TIME_FORMAT(starttime, '%H:%i') FROM `schedule` WHERE weeknumber = '6' AND `enable` = 1 LIMIT 0, 1) as 'ps6_start', 
					(SELECT TIME_FORMAT(endtime, '%H:%i')   FROM `schedule` WHERE weeknumber = '6' AND `enable` = 1 LIMIT 0, 1) as 'ps6_end', 
					(SELECT TIME_FORMAT(starttime, '%H:%i') FROM `schedule` WHERE weeknumber = '7' AND `enable` = 1 LIMIT 0, 1) as 'ps0_start', 
					(SELECT TIME_FORMAT(endtime, '%H:%i')   FROM `schedule` WHERE weeknumber = '7' AND `enable` = 1 LIMIT 0, 1) as 'ps0_end' ";
	$rsrs = $PDOLink->query($sql);
	$tmp  = $rsrs->fetch();
	
	if($tmp['ps6_start'] == '00:00' & $tmp['ps6_end'] == '23:59') {
		$ps6  = 0;
	} else {
		$ps6  = 1;
	}

	if($tmp['ps0_start'] == '00:00' & $tmp['ps0_end'] == '23:59') {
		$ps0  = 0;
	} else {
		$ps0  = 1;
	}
	
	
} else {
	
	$btn_name = "新增";
	
	$act  = 'add';
	
	// initial 
	$row['mode']    = 1;
	$row['rate']    = 1;
	$row['prepaid'] = 1;
	// $row['starttime'] = date('H:i');
	// $row['endtime']   = date('H:i');
	$row['starttime'] = '00 : 00';
	$row['endtime']   = '00 : 00';
}

// 
// $s_option = "<option value='%s' %s>%s</option>";

// for($i=1; $i < 5; $i++) {
	
	// if($i == 2) continue; // 保留
	// $tmp = ($row['mode'] == $i) ? 'selected' : '';
	// $mode_opt .= sprintf($s_option, $i, $tmp, get_mode($i));
// }


// weekdays
// for($i=0; $i < 7; $i++) {
	
	// $tmp = ($row['weeknumber'] == $i) ? 'selected' : '';
	// $week_opt .= sprintf($s_option, $i, $tmp, get_weekday($i));
// }


// 頁碼 
// if(isset($_GET['page'])) {               
	// $page=$_GET['page'];  
// } else {
	// $page=1;                                 
// }

// $pagenum=(int)ceil($rownum / $pagesize);  
// $prepage =$page-1;                        
// $nextpage=$page+1;                        
// $pageurl='';

// if($page == 1) {                         
	// $pageurl.=" ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
// } else {
	// $pageurl.="<ul class='pagination'><li><a href=\"?page=1\">".$lang->line("index.home")."</a> | <a href=\"?page=$prepage\">".$lang->line("index.previous_page")."</a> </li></ul> | ";
// }

// if($page==$pagenum || $pagenum==0){     
	// $pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
// } else {
	// $pageurl.="<ul class='pagination'><li><a href=\"?page=$nextpage\">".$lang->line("index.next_page")."</a> | <a href=\"?page=$pagenum\">".$lang->line("index.last_page")."</a></li> </ul>";
// }

?>
<!-- 教官查詢房號  -->
<section id="main" class="wrapper">

	<h2 style="margin-top: -30px;" align="center">離峰時段設定</h2>
	<div class="col-12"><a href="parking_schedule.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	
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
			<div class="col-12">
				<form id='mform' action="schedule_peak_update.php" method="post" accept-charset="utf-8">
					<table class="table">
						<tr>
							<td colspan='4' align='center'>&nbsp;</td>
						</tr>
						<tr>
							<td align="right" width='10%'><!-- 週 : --></td>
							<td align='left' width='40%'>
								<!-- <select name='weekday'><?php echo $week_opt ?> -->
								<input type='checkbox' id='ps6' class='pscheckbox' name='ps6' value='1'><label for='ps6'>週六</label>
								<input type='checkbox' id='ps0' class='pscheckbox' name='ps0' value='1'><label for='ps0'>週日</label>
							</td>
							<td align="right" width='10%'><!-- 模式 : --></td>
							<td width='40%'><!-- <select class="form-control" name='mode'><?php echo $mode_opt ?></select> --></td>
						</tr>
						<tr>
							<td align="right">
								開始時間 : 
							</td>
							<td>
								<input class="form-control" type="time" name="start_time" placeholder="hrs:mins" value="<?php echo $row['starttime'] ?>">
							</td>
							<td align="right">
								結束時間 : 
							</td>
							<td>
								<input class="form-control" type="time" name="end_time" placeholder="hrs:mins" value="<?php echo $row['endtime'] ?>">
							</td>
						</tr>
						<tr>
							<!--
							<td align='right'>預付 (度) : </td>
							<td align='center'>
								<input type='text' name='prepay' class="form-control" value='<?php echo $row['prepaid'] ?>'
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
							-->
							<td align='right'>費率 : </td>
							<td align='center'>
								<input type='text' name='rate' class="form-control" value='<?php echo $row['rate'] ?>'
									onkeyup="value=value.replace(/[^\d.]/g,'').replace(/^\./g,'').replace(/\.{2,}/g,'.').replace('.','$#$').replace(/\./g,'').replace('$#$','.');">
							</td>
						</tr> 
						<!--
						<tr>
							<td align="right" width='10%'>預付金額 : </td><td colspan='3' align='left' width='40%' id='prepay'></td>
						</tr>
						-->
						<tr>
							<td colspan='4' align='center'><button type='submit' id='btn_submit' class='form-control btn-primary'><?php echo $btn_name ?></button></td>
						</tr> 
					</table>
					
					<input type='hidden' name='act' value='<?php echo $act ?>'>
					<!--
					<input type='hidden' name='sid' value='<?php echo $sid ?>'>
					<input type='hidden' name='id'  value='<?php echo $id ?>'>
					-->
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

	var ps0 = <?php echo $ps0 ?>;
	var ps6 = <?php echo $ps6 ?>;
	
	if(ps0 > 0) {
		$('#ps0').prop('checked', true);
	} else {
		$('#ps0').prop('checked', false);
	}
	
	if(ps6 > 0) {
		$('#ps6').prop('checked', true);
	} else {
		$('#ps6').prop('checked', false);
	}	
	
	$(document).on('submit','#mform', function() {
		if(!confirm("確定設定嗎?")) {
			return false;
		}
	});
	
	$("input").keyup(function(){
		
		calc_result();
		
	});
	
	function calc_result() 
	{
		var prepaid = $('input[type=text][name=prepay').val();
		var rate    = $('input[type=text][name=rate').val();
		
		$('#prepay').html( prepaid * rate );
	}
	
	calc_result();
	
	
	
});
</script>

<?php include('footer_layout.php'); ?>