<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');

	$pagesize = 10;

	$sql_kw = "";
	$kw = $_GET[kw];
	
 	// $kw_start  = $_GET['kw_start'];
 	// $kw_end    = $_GET['kw_end'];
	// $sel_build = $_GET['sel_build'];
	// $sel_level = $_GET['sel_level'];
	$sel_dev   = $_GET['sel_dev'];

	// $sql = "SELECT * FROM `usage_history`";
	// $rs = $PDOLink->prepare($sql);
	// $rs->execute();
	// $rs_data = $rs->fetchAll();
	
	// foreach($rs_data as $v) {
		// $mc_data[$v['mac']] = $v;
	// }

	// 查詢條件 -- 20200205
	$b_opt; $l_opt; $p_opt;
	
	$def_opt  = "請選擇";
	// $wash_str = "洗衣機";
	$s_option = "<option value='%s' %s>%s</option>";
	
	$sql = "SELECT * FROM `seat`";
	$rs  = $PDOLink->query($sql);
	$tmp = $rs->fetchAll();
	
	$proc[''] = $def_opt;
	
	foreach($tmp as $v) {
		$proc[$v['id']] = $v['number'];
	}
	
	foreach($proc as $k => $v) {
		$tmp    = ($sel_dev == $k) ? 'selected' : '';
		$p_opt .= sprintf($s_option, $k, $tmp, $v);
	}
	
	if($sel_dev != '') {
		$sql_kw = " AND s.id = '{$sel_dev}' ";
	}
	
	/* 頁碼 */
	$sql = "SELECT * FROM `seat` WHERE 1 ".$sql_kw;
// echo $sql;
	$rs_page = $PDOLink->query($sql);
	$rownum  = $rs_page->fetchcolumn();
	
	if(isset($_GET['page'])) {
	   $page=$_GET['page'];  
	} else {
	   $page=1;                                 
	}
	  
	$pagenum=(int)ceil($rownum / $pagesize);  
	$prepage =$page-1;                        
	$nextpage=$page+1;                        
	$pageurl='';
	  
	if($page == 1) {
	   $pageurl.='首页 | 上一页 | ';
	} else {
	   $pageurl.="<a href=\"?page=1\">首页</a> | <a href=\"?page=$prepage\">上一页</a> | ";
	}
	  
	if($page==$pagenum || $pagenum==0) {
	   $pageurl.='下一页 | 最後一页';
	} else {
	   $pageurl.="<a href=\"?page=$nextpage\">下一页</a> | <a href=\"?page=$pagenum\">最後一页</a>";
	}
?>
<style>
.div_block {
	/* border-width: 3px; */
	/* border-style: double solid outset; */
	/* border-color: pink; */
	border: solid 3px #666;
}
/*.div_col {
	/* border-width: 3px; */
	/* border-style: double solid outset; */
	/* border-color: pink; */
	border: solid 3px #666;
}*/
.div_col {
	col-12;
}
</style>

<section id="main" class="wrapper">
	
	<h2 style="margin-top: -30px;" align="center">時段設定</h2>
	<div class="col-12"><a href="parking_setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>【Error】使用者行為錯誤</strong>
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>【Error】時段設置有誤，請再確認</strong>
		</div>
	<?php } elseif ($_GET[success] == 1) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>【Success】成功設置！！</strong> 
		</div>
	<?php } ?>
	</div>
	
	<div class="inner">
		<div class="row">
				<div class='col-12'>
					<section class='panel'>
						<div class='panel-body'>
						<form id='mform' >
							<!--
							<?php print "".$lang->line("index.please_enter_roomnumber_or_name")."：  "; ?>
							<input type='hidden' name='betton_color' value='<?php echo $betton_color ?>'>
							<input class='form-control' type='text' placeholder='<?php echo $lang->line("index.input_format")."：".$lang->line("index.member_name") ?>' size=20 name=cname value='<?php echo $cname ?>'> 
							<input class='form-control' type='text' placeholder='<?php echo $lang->line("index.input_format")."：".$lang->line("index.room_number") ?>' size=20 name=room_numbers_kw value='<?php echo $roomname ?>'>
							
							建築 : <select name="sel_build"><?php echo $b_opt ?></select>
							樓層 : <select name="sel_level"><?php echo $l_opt ?></select> -->
							車位 : <select name="sel_dev">  <?php echo $p_opt ?></select>
						
							<button type='button' class='form-control btn-success' onclick='search()'><?php echo $lang->line("index.confirm_query") ?></button>
							<button type='button' class='form-control btn-warning' onclick='reset_form()'><?php echo $lang->line("index.reset") ?></button>
							<!--
							<button type='button' class='form-control btn-info' style='width:90px' onclick='set_rate_all()'>新增時段</button>
							<button type='button' class='form-control btn-danger' style='width:90px' onclick='del_rate_all()'>刪除時段</button>
							-->
							<table border='0' cellpadding='0' cellpadding='0'>
							  <tr>
								<td class='tbl-btn' width='90px'><button type='button' class='form-control btn-info' style='width:90px' onclick='set_rate_all()'>新增時段</button></td>
								<td class='tbl-btn' width='90px'><button type='button' class='form-control btn-danger' style='width:90px' onclick='del_rate_all()'>刪除時段</button></td>
								<td class='tbl-btn'>&nbsp;</td>
							  </tr>
							</table>							
							<!-- 
							<table border='0' cellpadding='0' cellpadding='0'>
							  <tr>
							    <td class='tbl-btn' colspan='3'><button type='button' class='form-control btn-success' onclick='search()'><?php echo $lang->line("index.confirm_query") ?></button></td>
							  </tr>
							  <tr>
								<td class='tbl-btn' width='10%'><button type='button' class='form-control btn-info' onclick='set_rate_all()'>新增時段</button></td>
								<td class='tbl-btn' width='1'>&nbsp;</td>
								<td class='tbl-btn'><button type='button' class='form-control btn-warning' onclick='reset_form()'><?php echo $lang->line("index.reset") ?></button></td>
							  </tr>
							</table>
							-->
						</form>	
						</div> 
					</section>
				</div>

				<div class="col-12">
					<!-- 付款 table -->
					
						<table class="table">
						  <thead class="thead-dark">
						    <tr>
								<th scope="col" width='10'>勾選</th>
								<th scope="col">#</th>
								<th scope="col">車號</th>
								<th scope="col">目前模式</th>
								<th scope="col">目前費率</th>
								<th scope="col">目前預付</th>
								<th scope="col">&nbsp;</th>
						    </tr>
						  </thead>
						  <tbody>
						  <form id='mform1'>
							<input type='hidden' id='act' name='act'>
						  <?php 
							$j=0;
							// $ezcard_record_sql = "
								// SELECT s.*, s.number, s.id as 'sid' FROM `seat` s LEFT JOIN `machine` m ON m.id = s.machine_id WHERE 1 {$sql_kw} 
								// ORDER BY s.id LIMIT " . ($page-1) * $pagesize .", ".$pagesize;
							$ezcard_record_sql = "
								SELECT *, s.id as 'sid' FROM `seat` s WHERE 1 {$sql_kw} ORDER BY s.id LIMIT " . ($page-1) * $pagesize .", ".$pagesize;
// echo $ezcard_record_sql;
							$rs = $PDOLink->Query($ezcard_record_sql);
							$rs->setFetchMode(PDO::FETCH_ASSOC);
							// $SortTypes = array('Stored' => '付款','Refund' => '退費');
							while($row = $rs->Fetch())
							{
								$id = $row['sid'];
								$now_time = date('H:i:s');
								$week_day = date('w');
								
								$sql = "
									SELECT * FROM `schedule` WHERE seat_id = '{$id}' 
									AND weeknumber = '{$week_day}' AND enable = 1
									AND starttime <= '{$now_time}' AND endtime >= '{$now_time}' LIMIT 0, 1";
// echo $sql;
								$rs_tmp = $PDOLink->prepare($sql); 
								$rs_tmp->execute();
								$tmp = $rs_tmp->fetch();
								
								if($tmp) {
									
									$mode = get_mode($tmp['mode']);
									$rate = $tmp['mode'] == 1 ? $tmp['rate']." 元" : '';
									$paid = $tmp['mode'] == 1 ? $tmp['prepaid'] : '';
									$show = get_status($tmp['mode']);
								} else {
									
									$mode = get_mode($row['mode']);
									$rate = $row['mode'] == 1 ? $row['rate']." 元" : '';
									$paid = $row['mode'] == 1 ? $row['prepaid'] : '';
									$show = get_status($row['mode']);
								}
								
								print " <tr>
											<td><input type='checkbox' id='ps{$id}' class='pscheckbox' name='parkingspace[]' value='{$id}'><label for='ps{$id}'> &nbsp;</label></td>
											<td>".++$j."</td>
											<td>".$row['number']."</td> 
											<td>{$mode}</td>
											<td>{$rate}</td>
											<td>{$paid}</td>
											<td width='10%' nowrap>
												<a onclick='set_rate(".$row['sid'].")' href='#'><i class='fas fa-eye'></i></a>
											</td>
										</tr>";
							}
						   ?>
						   </form>	
						  </tbody>
						</table>
					<!-- End 付款 table -->
				</div>
		</div>
		<?php 
		if($rownum > $pagesize){   
	        echo $pageurl;
	        echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
		}
		?> 
	</div>
	
</section>

<script>
function reset_form() {
	location.replace('RoomList1.php');
}

function search() {
	$('#mform').prop('action', 'RoomList1.php');
	$('#mform').prop('method', 'get');
	
	$('#mform').submit();
}

function set_rate_all() {
	
	var pscheck = false;
	
	$('.pscheckbox').each(function(){
		if($(this).prop('checked')) {
			// alert('123');
			pscheck = true;
		}
	});
	
	if(!pscheck) {
		
		alert('未勾選車位');
		return false;
	}
	
	// location.replace('RefundTimeControl.php?sid=' + sid);
	
	$('#act').val('add');
	
	$('#mform1').prop('action', 'schedule_all.php');
	$('#mform1').prop('method', 'POST');
	
	$('#mform1').submit();
}

function del_rate_all() {
	
	var pscheck = false;
	
	$('.pscheckbox').each(function(){
		if($(this).prop('checked')) {
			// alert('123');
			pscheck = true;
		}
	});
	
	if(!pscheck) {
		
		alert('未勾選車位');
		return false;
	}
	
	// location.replace('RefundTimeControl.php?sid=' + sid);
	$('#act').val('del');
	
	$('#mform1').prop('action', 'schedule_all_update.php');
	$('#mform1').prop('method', 'POST');
	
	$('#mform1').submit();
}

function set_rate(sid) {
	
	location.replace('RefundTimeControl.php?sid=' + sid);
	
}

function set_refund(sid) {
	
	location.replace('setrefund.php?sid=' + sid);
	
}

$(".tbl-btn").css({ 'padding' : 0 });

</script>

<?php include('footer_layout.php'); ?>