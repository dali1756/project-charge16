<?php 

include('header_layout.php'); 
include('nav.php'); 
include('chk_log_in.php'); 
// include('RefundTimeFunction.php');

$pagesize = 10;

$row;
$act = $_GET['act'];
$sid = $_GET['sid'];
$id  = $_GET['id'];
$wid = $_GET['weekday'];

$week_opt;
$mode_opt;
$btn_name;

$sql = "SELECT number FROM `seat` WHERE id = '{$sid}'";
$rs  = $PDOLink->query($sql);
$tmp = $rs->fetch();
$ps  = $tmp['number'];

if($act == 'edit') {
	
	$btn_name = "修改";
	
	$sql = "SELECT * FROM `schedule` WHERE id = '{$sid}'";
	$rs  = $PDOLink->query($sql);
	$row = $rs->fetch();

	if(!$row) {
		echo "<script>";
		echo "alert('查無資料!!');";
		echo "history.go(-1);";
		echo "</script>";
	}
}

if($act == 'add') {
	
	$btn_name = "新增";
	
	// initial 
	$row['mode']    = 1;
	$row['rate']    = 1;
	$row['prepaid'] = 1;
	$row['starttime'] = date('H:i');
	$row['endtime']   = date('H:i');
	$row['weeknumber'] = $wid;
}

// 
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

	<h2 style="margin-top: -30px;" align="center">車位設定</h2>
	<div class="col-12"><a href="#" onclick='back()'><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	
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
				<form id='mform' action="schedule_update.php" method="post" accept-charset="utf-8">
					<table class="table">
						<tr>
							<td colspan='4' align='center'>車位號碼 : <?php echo $ps; ?></td>
						</tr>
						<tr>
							<td align="right" width='10%'>週 : </td>
							<td align='center' width='40%'><select name='weekday'><?php echo $week_opt ?></td>
							<td align="right" width='10%'>費率 : </td>
							<td width='40%'><input type='text' name='rate' class="form-control" value='<?php echo $row['rate'] ?>'></td>
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
							<td align='right'>預付 (度) : </td>
							<td align='center'><input type='text' name='prepay' class="form-control" value='<?php echo $row['prepaid'] ?>'></td>
							<td align='right'>模式 : </td>
							<td align='center'>
								<select class="form-control" name='mode'><?php echo $mode_opt ?></select>
							</td>
						</tr> 
						<tr>
							<td colspan='4' align='center'><input type='submit' id='btn_submit' class='form-control' value='<?php echo $btn_name ?>'></td>
						</tr> 
					</table>
					
					<input type='hidden' name='act' value='<?php echo $act ?>'>
					<input type='hidden' name='sid' value='<?php echo $sid ?>'>
					<input type='hidden' name='id'  value='<?php echo $id ?>'>
				</form>
			</div>
		</div>
	</div>
</section>

<script>

function back() {
	
	history.go(-1);
	
}


$(document).ready(function() {
	
	var act = $('input[type=hidden][name=act]').val();
	
	if(act == 'edit') {
		$('select[name=weekday]').prop('disabled', true);
		$('select[name=weekday]').css('background-color', '#ddd');
	}
	
	// 送出時打開 -- 20200220
	$(document).on('submit','#mform', function(){
		$('select[name=weekday]').prop('disabled', false);
	});
	
});
</script>

<?php include('footer_layout.php'); ?>