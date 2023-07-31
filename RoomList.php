<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');

	$pagesize = 40;

	$sql_kw = "";
	$kw = $_GET[kw];
	
	// if(!$one_page) $one_page = 10;
	
	// $room_data;
 	// $room_numbers_kw = $_GET['room_numbers_kw'];
	// $room_numbers_floor_kw = $_GET['room_numbers_floor_kw'];
	
	// $sql = "SELECT * FROM `var_list` WHERE var_type = '棟別'";
	// $list_r = $PDOLink->prepare($sql); 
	// $list_r->execute();
	// $row2 = $list_r->fetchAll();         
	
	// foreach($row2 as $v) {
		// $room_data[$v['var_value2']] = $v['var_name'];
	// }
	
    if($kw) {
		$sql_kw.=" AND ((room_number like '%".$kw."%') or (username like '%".$kw."%')) ";
		// $sql_kw.=" or  (id_card in (SELECT id_card FROM member WHERE cname like '%{$kw}%'))) ";
    }

	if($room_numbers_kw && $room_numbers_floor_kw) {
		$sql_kw.=" and (room_number_type like '%".$room_numbers_kw."%' and floor = '".$room_numbers_floor_kw."') ";
	} else if ($room_numbers_kw) {
		$sql_kw.=" and (room_number_type like '%".$room_numbers_kw."%') ";
	}

	// if($room_numbers_kw) $sql_kw.=" and room_number_type='".$room_numbers_kw."'";
	// if($room_numbers_kw && $room_numbers_floor_kw) $sql_kw.=" and room_number_type='".$room_numbers_kw."' and floor='".$room_numbers_floor_kw."' ";
        	
	/* 房間table */
	// $list_q = "select * from room where 1 and room_number_type='".$room_numbers_kw."' ";
	// $list_r = $PDOLink->prepare($list_q); 
	// $list_r->execute();
	// $row2 = $list_r->fetch();         
	// $room_number_type=$row2[room_number_type];

	/* 頁碼 */
	// $sql="select count(*) from room";
	// $rs=$PDOLink->query($sql);
	// $rownum=$rs->fetchcolumn();
	
	// 車位
	$sql  = "SELECT * FROM `seat` ";
	$list_r = $PDOLink->prepare($sql); 
	$list_r->execute();
	$row2 = $list_r->fetchAll();
	
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
	.div_block h5 {
		font-weight : bold;
		font-size : 24px;
		color : #ff8367;
	}
	.div_status {
		font-weight : bold;
		font-size : 21px;
		color : #193939;
		margin:0 auto; 
	} 
	.card-header{
		background-color:#006666;
	}
	.card-green{
		font-size : 18px;
		background-color: #F0F7F7;
		border:3px solid #006666;
		border-radius : 10px;
	}
	.nowsystem ul li{
		display: block;
	}
</style>

<section id="main" class="wrapper">
	
	<h2 class="text-center mb-4">系統使用現況</h2>
	<div class="col-12"><a href="parking_check.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	
	<div class="inner row" style="margin:0px auto;">
<?php
	
	/**/$div_string = "
	<div class='col-lg-4 col-md-6 mb-4'>
			<div class='card h-100 card-green text-green nowsystem'>
				<ul class='div_block'>
					<li><h5>車位 : %s</h5></li>
					<li>收費模式 : %s</li>
					<li>充電費率 : %s</li>
					<li>預付度數 : %s</li>
					<li>狀態 : %s</li>
					<li>目前電表度數 : %s</li>
					<li>&nbsp;<input type='hidden' value='%s'></li>
					<li class='div_status'>%s</li>
				</ul>
			</div>
	</div>
	";
	
	foreach($row2 as $v) {
		
		
		$perk     = '';
		$now_time = date('H:i:s');
		$week_day = date('w');
		
		$sql = "
			SELECT * FROM `schedule` WHERE seat_id = '{$v['id']}' 
			AND weeknumber = '{$week_day}' 
			AND starttime <= '{$now_time}' 
			AND endtime >= '{$now_time}' 
			AND enable = 1 LIMIT 0, 1";
// echo $sql;
		$rs = $PDOLink->prepare($sql); 
		$rs->execute();
		$row = $rs->fetch();
		
		if($row) {
			
			// 模式改抓車位設定 -- 20200221
			// $mode = get_mode($row['mode']);
			// $show = get_status($row['mode']);

			$mode = get_mode($v['mode']);
			$show = get_status($v['mode']);
			
			$rate = $row['rate']." 元";
			$paid = $row['prepaid'];
			
			$perk = "尖峰時段";
			
		} else {
			
			// $sql = "SELECT s.* FROM `seat` s LEFT JOIN machine m ON m.id = s.machine_id WHERE s.id = '{$v['id']}' LIMIT 0, 1";
			$sql = "SELECT s.* FROM `seat` s WHERE s.id = '{$v['id']}' LIMIT 0, 1";
// echo $sql;			
			$rs = $PDOLink->prepare($sql); 
			$rs->execute();
			$data = $rs->fetch();
			
			// $show = '設定時段中';
			$mode = get_mode($data['mode']);
			$rate = $data['rate']." 元";
			$paid = $data['prepaid'];
			$show = get_status($data['mode']);
			
			$perk = "離峰時段";
		}

		// $sql = "SELECT * FROM `usage_history` WHERE seat_id = '{$v['id']}' ORDER BY `usage_history`.`start_time` DESC LIMIT 0, 1";
		$sql = "SELECT * FROM `use_status` WHERE seat_id = '{$v['id']}' LIMIT 0, 1";
// echo $sql;
		$rs = $PDOLink->prepare($sql); 
		$rs->execute();
		$data2 = $rs->fetch();
		
		$curr = $data2['now_amount'];
		
		if($show == '1') {	
			$show = get_power($data2['power']);
		}
		
		echo sprintf($div_string, $v['number'], $mode, $rate, $paid, $show, $curr, $v['id'], $perk);
	}
?>
	</div>
	
</section>

<script>

function set_rate(sid) {
	
	location.replace('RefundTimeControl.php?sid=' + sid);
	
}

function set_refund(sid) {
	
	location.replace('setrefund.php?sid=' + sid);
	
}
</script>

<?php include('footer_layout.php'); ?>