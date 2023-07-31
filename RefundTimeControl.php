<?php 

include('header_layout.php'); 
include('nav.php'); 
include('chk_log_in.php'); 
// include('RefundTimeFunction.php');

$pagesize = 10;

$weekdays;

$sid = $_GET['sid'];

$refund_delete = $_GET[act];

// weekdays
for($i=0; $i<7; $i++) {
	$weekdays[] = $i;
}

// 防錯
$sql  = "SELECT * FROM `schedule` WHERE seat_id = '{$sid}'";
$rs  = $PDOLink->query($sql);
$rownum = $rs->fetchcolumn();

// if(!$rownum) {
	// echo "<script>";
	// echo "alert('查無資料!!');";
	// echo "history.go(-1);";
	// echo "</script>";
// }

$sql = "SELECT number FROM `seat` WHERE id = '{$sid}'";
$rs  = $PDOLink->query($sql);
$tmp = $rs->fetch();
$ps  = $tmp['number'];

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

	<h2 style="margin-top: -30px;" align="center">時段設定</h2>
	<div class="col-12"><a href="RoomList1.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>【Error】使用者行為錯誤</strong>
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>【Error】時段設置錯誤，請再確認</strong>
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
				<form action="" method="post" accept-charset="utf-8">
					<table class="table">
					  <thead class="thead-dark">
						<tr>
							<td colspan='4' align='center'>車位號碼 : <?php echo $ps; ?></td>
						</tr> 
						<tr>
							<th scope="col">週</th>
							<th scope="col">開始時間 ~ 結束時間</th>
							<th>備註</th>
							<th><!-- 操作 --></th>
						</tr> 
					  </thead>
					  <tbody>
						<?php 
							foreach($weekdays as $v) {
						?>
						<tr>
							<td scope='row' width='20%'><?php echo get_weekday($v) ?></td>
							<td width='40%'>
								<?php
								
									$op1;
								
									$div_str = "<div>%s</div>";
									$op1_str = "<a href='#' onclick='%s(%s, %s)'>%s</a>";
									
									$sql  = "
										SELECT id, enable, rate, prepaid, 
										CONCAT(DATE_FORMAT(starttime, '%H:%i'), ' ~ ', DATE_FORMAT(endtime, '%H:%i')) as 'new_time' 
										FROM schedule WHERE weeknumber = '{$v}' AND seat_id = '{$sid}' ORDER BY starttime";
									$rs   = $PDOLink->query($sql);
									$rs->execute();
									$data = $rs->fetchAll();
									foreach($data as $w) {
										
										$edit_str = "&nbsp;".sprintf($op1_str, 'edit_time', $sid, $w['id'], '修改');
										$del_str  = "&nbsp;".sprintf($op1_str, 'del_time',  $sid, $w['id'], '刪除');
										
										$status   = ($w['enable'] == 1) ? 
											"&nbsp;".sprintf($op1_str, 'disable_time', $sid, $w['id'], '啟用中') : 
											"&nbsp;".sprintf($op1_str, 'enable_time',  $sid, $w['id'], '停用中');
										
										echo sprintf($div_str, $w['new_time'] . $status . $edit_str . $del_str);
									}
									
									$op1 = sprintf($op1_str, 'add_time', $sid, $v, '新增');
								?>							
							</td>
							<td width='30%'>
								<?php
									// 新增費率、預付度數 -- 20200218
									foreach($data as $w) {
										echo sprintf($div_str, '費率:'.$w['rate'].'&nbsp;預付:'.$w['prepaid']);
									}
								?>	
							</td>
							<td width='10%'><?php // echo $op1; ?></td>
						</tr>
						<?php 
							}
						?>						
					  </tbody> 
					</table>
				</form>
			</div>
		</div> 
	</div>
</section>

<script>

function add_time(sid, weekday)
{	
	location.replace('schedule.php?act=add&sid=' + sid + '&weekday=' + weekday);
}

function edit_time(id, sid)
{	
	location.replace('schedule.php?act=edit&sid=' + sid + "&id=" + id);
}

function del_time(id, sid)
{	
	if(confirm('確定刪除?')) {
		location.replace('schedule_update.php?act=delete&sid=' + sid + "&id=" + id);
	}
}

function disable_time(id, sid)
{	
	if(confirm('目前啟用中，確定變更?')) {
		location.replace('schedule_update.php?act=disable&sid=' + sid + "&id=" + id);
	}
}

function enable_time(id, sid)
{	
	if(confirm('目前停用中，確定變更?')) {
		location.replace('schedule_update.php?act=enable&sid=' + sid + "&id=" + id);
	}
}
</script>


<?php include('footer_layout.php'); ?>