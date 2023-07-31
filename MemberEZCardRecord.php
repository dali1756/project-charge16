<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');
	
	$pagesize  = 10;
	
	$mc_data;
	
	$sql_kw    = "";
	
 	$kw_start  = $_GET['kw_start'];
 	$kw_end    = $_GET['kw_end'];
	$sel_build = $_GET['sel_build'];
	$sel_level = $_GET['sel_level'];
	$sel_dev   = $_GET['sel_dev'];

	// $sql = "SELECT * FROM `usage_history`";
	// $rs = $PDOLink->prepare($sql);
	// $rs->execute();
	// $rs_data = $rs->fetchAll();
	
	// foreach($rs_data as $v) {
		// $mc_data[$v['mac']] = $v;
	// }
	
	// 給初值 -- 20200330
 	if($kw_start == "") { $kw_start = date('Y-m-d'); }
	if($kw_end   == "") { $kw_end   = date('Y-m-d'); }
	
	// 時間範圍限當天 -- 20200506
	// if((strtotime($kw_start)) != (strtotime($kw_end))) {
		
		// $kw_start = date('Y-m-d');
		// $kw_end   = date('Y-m-d');
		
		// echo "<script> location.replace('MemberEZCardRecord.php?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&sel_num={$sel_num}&kw_start={$kw_start}&kw_end={$kw_end}&get_tab={$get_tab}&error=3')</script>";
	// }
	
	$status_map = array('1'  => '正常使用',
						'2'  => '有退費',
						'3'  => '切免費',
						'4'  => '切停用', 
						'-1' => '放棄退費');
	
	// 查詢條件 -- 20200205
	$b_opt; $l_opt; $p_opt;
	
	$def_opt  = "全部";
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

	// $compare = ($sel_dev == $wash_str) ? "<=" : ">";
	
	if($sel_dev != '') $sql_kw .= " AND seat_id = '{$sel_dev}' ";
	
	if($kw_start) {
		$s_date = date('Y-m-d H:i:s', strtotime($kw_start));
		$sql_kw.= " AND start_time >= '{$s_date}' ";
	}
	
	if($kw_end) {
		$e_date = date('Y-m-d H:i:s', strtotime($kw_end.'+1 day'));
		$sql_kw.= " AND start_time < '{$e_date}' ";
	}

	/* 頁碼 */
	$sql = "SELECT count(*) FROM usage_history i WHERE 1 ".$sql_kw;
// echo $sql;
	$rs  = $PDOLink->query($sql);
	$rownum = $rs->fetchcolumn();               
	
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
		$pageurl.=" ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
	} else {
		$pageurl.="<a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=1\">".$lang->line("index.home")."</a> | 
				   <a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=$prepage\">".$lang->line("index.previous_page")."</a> | ";
	}

	if($page==$pagenum || $pagenum==0) {     
		$pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
	} else {
		$pageurl.="<a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=$nextpage\">".$lang->line("index.next_page")."</a> | 
				   <a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=$pagenum\">".$lang->line("index.last_page")."</a>";
	}
?>

<!-- new -->
<style>
	#main button{
		font-size: 18px;
		height: 40px;
	}
	#mform label{
		text-align: right;
    	color: black;
	}
	.table thead tr th{
		border:3px solid;
	}
	.table thead tr th,
	.table tbody tr td{
		vertical-align: middle;
		text-align:center;
	}
	.page_num{
		margin: 0em 1em 5em;
	}
	.page_num a{
		color:#337ab7;
	}
</style>
<section id="main" class="wrapper container-faluid">
	<h2 class="text-center">付款紀錄查詢</h2>
	<div class="col-12"><a href="parking_check.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	
	<div class="row justify-content-center m-0">
		<?php if($_GET[error] == 1){ ?>
			<div class="col-lg-4 alert alert-danger text-center" role="alert">
				<strong>【Error】沒有可匯出的資料</strong>
			</div>
		<?php } elseif ($_GET[error] == 2) { ?>
			<div class="col-lg-4 alert alert-danger text-center" role="alert">
			<strong>Error</strong>
			</div>
		<?php } elseif ($_GET[success]) { ?>
			<div  class="col-lg-4 alert alert-success text-center" role="alert">
			<strong>Success</strong>成功設置！！
			</div>
		<?php } else if($_GET['error'] == 3) { ?>
			<div class="col-lg-4 alert alert-danger text-center" role="alert">
				<strong>時間範圍過大，請設定在1天以內</strong>
			</div>
		<?php } ?>
	</div>

	<div class="row justify-content-center m-0">
		<form id='mform' class="col-lg-6">
			<div class="form-group row">
				<label for="sel_dev" class="col-sm-3 col-form-label">車位：</label>
				<div class="col-sm-9">
					<select name="sel_dev" class="form-control"><?php echo $p_opt ?></select>
				</div>
			</div>

			<div class="form-group row">
				<label for="kw_start" class="col-sm-3 col-form-label">請輸入時間範圍：</label>
				<div class="col-sm-9">
					<input class='form-control' type='date' placeholder='開始時間：yyyy-mm-dd' size=20 name=kw_start value='<?php echo $kw_start ?>'>
				</div>
			</div>
			<div class="form-group row">
				<label for="kw_end" class="col-sm-3 col-form-label"></label>
				<div class="col-sm-9">
					<input class='form-control' type='date' placeholder='結束時間：yyyy-mm-dd' size=20 name=kw_end value='<?php echo $kw_end ?>'>
				</div>
			</div>
			<input type=hidden name=act>
			<input type=hidden name=sn>
			<input type=hidden name=edit_sn> 

			<div class="row justify-content-between">
				<div class="col-md-6">
					<button type='button' class='form-control btn btn-primary' onclick='search()'><?php echo $lang->line("index.confirm_query") ?></button>
				</div>
				<div class="col-md-6">
					<button type='button' class='form-control btn btn-warning' onclick='reset_form()'><?php echo $lang->line("index.reset") ?></button>
				</div>
			</div>
		</form>
	</div>


	<div class="row justify-content-center m-0">
			<div class="col-md-auto ml-auto my-3">
				<button type='button' class='form-control btn btn-primary' onclick='export2excel()'><?php echo $lang->line("index.export") ?></button>
			</div>
			<div class="col-12">
				<div class="table-responsive">
					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th rowspan="2">#</th>
								<th rowspan="2">計費<br>方式</th>
								<th colspan="2">日期</th>
								<th rowspan="2">車位</th>
								<th rowspan="2">卡號</th>
								<th rowspan="2">充電時間</th>
								<th colspan="2">尖峰</th>
								<th colspan="2">離峰</th>  
								<th rowspan="2">付款<br>金額</th>
								<th rowspan="2">退費<br>金額</th> 
								<th rowspan="2">狀態</th>
								<th rowspan="2">發票</th>
							</tr>
							<tr>
								<th>進場</th>
								<th>離場</th>
								<th>時段度數</th>
								<th>充電時段</th>
								<th>時段度數</th>
								<th>充電時段</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td scope="row">1</td>
								<td>時間</td>
								<td>2023-07-18 15:54:52</td>
								<td>2023-07-18 16:00:58</td>
								<td>181</td>
								<td>638054849</td>
								<td>00:06:06</td>
								<td>0.2</td>  
								<td>00:05:51</td>  
								<td>0</td>
								<td>00:00:00</td>
								<td>5</td> 
								<td>10</td>
								<td>有退費</td>
								<td>
									<a href="invoice.php" target="_blank" onclick="sendInvoice('261fa29f-6c92-4920-9c16-b42be8f31922')">
										<i class="fa fa-receipt"></i>
									</a>
								</td>
							</tr>
							<tr>
								<td scope="row">2</td>
								<td>度數</td>
								<td>2023-07-18 15:49:46</td>
								<td>2023-07-18 15:53:38</td>
								<td>181</td>
								<td>638054849</td>
								<td>00:03:52</td>
								<td>3</td>  
								<td>00:03:33</td>  
								<td>0</td>
								<td>00:00:00</td>
								<td>15</td> 
								<td>0</td>
								<td>正常使用</td>
								<td>
									<a href="invoice.php" target="_blank" onclick="sendInvoice('e313d0b1-38b2-464c-96c0-f5cfcc66348a')">
										<i class="fa fa-receipt"></i>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
	</div>
	<!-- 分頁頁碼 -->
	<div class="page_num">
		<?php 
			if($rownum > $pagesize){   
				echo $pageurl;
				echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
			}
		?> 
	</div>

</section>


<!-- old  付款紀錄查詢-->
<section id="main" class="wrapper d-none">
	<h2 style="margin-top: -30px;" align="center">付款紀錄查詢</h2>
	<div class="col-12"><a href="parking_check.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
    
	<div class="row">
		<?php if($_GET[error] == 1){ ?>
			<div class="col-lg-4 alert alert-danger text-center" role="alert">
				<strong>【Error】沒有可匯出的資料</strong>
			</div>
		<?php } elseif ($_GET[error] == 2) { ?>
			<div class="col-lg-4 alert alert-danger text-center" role="alert">
			<strong>Error</strong>Error！！
			</div>
		<?php } elseif ($_GET[success]) { ?>
			<div  class="col-lg-4 alert alert-success text-center" role="alert">
			<strong>Success</strong>成功設置！！
			</div>
		<?php } else if($_GET['error'] == 3) { ?>
			<div class="col-lg-4 alert alert-danger text-center" role="alert">
				<strong>時間範圍過大，請設定在1天以內</strong>
			</div>
		<?php } ?>
	</div>
<form id='mform' >
	<div class="inner">
		<div class="row">
				<div class='col-12'>
					<section class='panel'>
						<div class='panel-body'>
							<table class='table1 mb-3' border='0'>
								<tr>
									<td align='right' width='10%'>車位：</td>
									<td><select name="sel_dev">  <?php echo $p_opt ?></select></td>
								</tr>
								<tr>
									<td nowrap>請輸入時間範圍：</td>
									<td><input class='form-control' type='date' placeholder='開始時間：yyyy-mm-dd' size=20 name=kw_start value='<?php echo $kw_start ?>'></td>
								</tr>							
								<tr>
									<td></td>
									<td><input class='form-control' type='date' placeholder='結束時間：yyyy-mm-dd' size=20 name=kw_end value='<?php echo $kw_end ?>'></td>
								</tr>
							</table>							
							<button type='button' class='form-control btn-primary' onclick='export2excel()'>付款紀錄匯出</button>
							<button type='button' class='form-control btn-primary' onclick='search()'><?php echo $lang->line("index.confirm_query") ?></button>
							<button type='button' class='form-control btn-warning' onclick='reset_form()'><?php echo $lang->line("index.reset") ?></button>
						</div> 
					</section>
				</div>

				<div class="col-12">
						<table class="table">
						  <thead class="thead-dark">
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">交易日期</th>
							  <th scope="col">車位號碼</th>
							  <th scope="col">卡號</th>
							  <th scope="col">充電時間</th>
							  <th scope="col">一般時段度數</th>
						      <th scope="col">離峰時段度數</th>  
							  <th scope="col">付款金額</th>
						      <th scope="col">退費金額</th> 
							  <th scope="col">狀態</th>
							  <th scope="col">發票</th>
						    </tr>
						  </thead>
						  <tbody>
						  <?php 
							$j=1;
							// $ezcard_record_sql="select Time,member_id,PayValue,Sort from ezcard_record where 1 $sql_kw order by Time desc limit " . ($page-1)* $pagesize . ",$pagesize  ";
							$sql = "
								SELECT *, (SELECT `number` FROM seat WHERE id = u.seat_id LIMIT 0, 1) as 'parking_space',
								TRUNCATE(prepaid * rate, 0) as 'prepay', 
								(onpeek_amount * onpeek_rate) as 'onpeek_fee', 
								(offpeek_amount * offpeek_rate) as 'offpeek_fee', 
								TIMEDIFF(end_time, start_time) as 'timediff' FROM `usage_history` u 
								WHERE 1 {$sql_kw} ORDER BY start_time DESC LIMIT " . ($page-1) * $pagesize .", ".$pagesize;
							$rs = $PDOLink->Query($sql);
							// $rs->setFetchMode(PDO::FETCH_ASSOC);
							$data = $rs->fetchAll();
							
							// while($row = $rs->Fetch())
							foreach($data as $row)
							{
								
								$fee = 0;
								$ref = 0;
								
								$prepay; $onpeek_fee; $offpeek_fee;
								
								$prepay      = $row['prepay'];
								$onpeek_fee  = $row['onpeek_fee'];
								$offpeek_fee = $row['offpeek_fee'];
								
								$status  = $row['status'];
								$str_sts = $status_map[$status];
								
								switch($status) {
									
									case 1:
									case -1:
										$fee = $prepay;
										$ref = 0;
										break;
									case 2:
										$fee = $onpeek_fee + $offpeek_fee;
										$ref = $prepay - $onpeek_fee - $offpeek_fee;
										// $ref = $prepay - $fee;
										break;
									case 3:
									case 4:
										$fee = 0;
										$ref = 0;
										break;
									default:
										break;										
								}
								?>
								<tr>
								    <th scope="row"><?php echo $j++ ?></th>
								    <th ><?php echo $row['start_time'] ?></th>
									<th ><?php echo $row['parking_space']?></th>
									<th ><?php echo $row['id_card']?></th>
									<th ><?php echo $row['timediff']?></th>
								    <th ><?php echo $row['onpeek_amount']?></th>  
									<th ><?php echo $row['offpeek_amount']?></th>
								    <th ><?php echo ceil($fee)?></th> 
									<th ><?php echo floor($ref)?></th>
									<th ><?php echo $str_sts?></th>
									<td>
							        	<?php if($fee>0){ ?>
								        	<a href='invoice.php' target='_blank' onclick="sendInvoice('<?php echo $row['uuid']?>')">
								            	<i class='fa fa-receipt'></i>
								        	</a>
										<?php } ?>
							    	</td>
							    </tr>
							<?php
							}
						   ?>
						  </tbody>
						</table>
				</div>
		</div>
		<?php 
		if($rownum > $pagesize){   
	        echo $pageurl;
	        echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
		}
		?> 
	</div>
	
<!-- 教官查詢房號 End-->
<input type=hidden name=act>
<input type=hidden name=sn>
<input type=hidden name=edit_sn> 
</form>
</section>
<script>

//回上一頁
function backs()
{
	history.go(-1);
}

function reset_form() {
	location.replace('MemberEZCardRecord.php');
}

function search() {
	$('#mform').prop('action', 'MemberEZCardRecord.php');
	$('#mform').prop('method', 'get');
	
	$('#mform').submit();
}

function export2excel() {
	
	// $('#mform').prop('action', 'MemberEZCardRecordExcel.php');
	$('#mform').prop('action', 'excelmember_history_save1.php');
	$('#mform').prop('method', 'get');
	
	$('#mform').submit();
	return false;
}
function sendInvoice(uuid) {
    var data = {
        value: uuid
    };
    $.ajax({
        url: 'api/invoice.php', // 将请求发送到的 PHP 文件路径
        method: 'POST',
        data: data,
        
    });
}
</script>
<?php // iframe('');?>
<?php include('footer_layout.php'); ?>