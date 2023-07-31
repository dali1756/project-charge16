<?php 

	//include('header_layout.php');
	include('nav_qr.php');

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
	$sql  = "SELECT s.* FROM `seat` s ORDER BY CAST(substring(s.`number`,3) AS DECIMAL) ASC;";
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
<!--20210104系統使用現況-->
<section id="main" class="wrapper">
	<div class="container">
		<h1 class="text-center mb-4">系統使用現況</h1>
			</div>
			<div class="inner">
			<div class="row py-0">
			<div class='col-12 my-4'>
				<div class='h4 alert alert-orange text-center'>
						<span class='text-orange p-0 col-12'>
							<i class="fas fa-exclamation-circle"></i>
							費用計算=(一般時段度數*一般費率) + (離峰時段度數*離峰費率)
						</span> 
				</div>
			</div>
<?php	
/*	$div_string = "
		<div class='div_block col-3 ml-2 mb-3'>
			<div><h5>車位 : %s</h5></div>
			<div>收費模式 : %s</div>
			<div>充電費率 : %s</div>
			<div>預付度數 : %s</div>
			<div>狀態 : %s</div>
			<div>目前電表度數 : %s</div>
			<div>&nbsp;<input type='hidden' value='%s'></div>
			<div class='div_status'>%s</div>
		</div>";*/
		
	$div_string = "
		<div class='col-lg-4 col-md-6 mb-4'>
				<div class='card h-100 card-green text-green nowsystem'>
					<ul class='py-3 pl-0'>
						<li>車位： %s </li>
						<li >狀態：
							<span class='text-gray'><c><i class='fas fa-bolt'/></i>&nbsp;%s</c></span>
						</li>
						<li>開始時間：%s</li>
						<li>已充電時間：%s</li>
						<li>時段：%s</li>
						<li>充電費率：%s </li>
						<li>已充電度數：%s</li>
						<li>費用：%s </li>
						<li class='d-none'><div>&nbsp;<input type='hidden' value='%s'></div></li>
					</ul>
				</div>
	    </div>";
	foreach($row2 as $v) 
	{
		$perk     = '';
		$now_time = date('H:i:s');
		//$week_day = date('w');  
		$week_day = date('N'); 
/* 		$sql = "
			SELECT * FROM `schedule` WHERE seat_id = '{$v['id']}' 
			AND weeknumber = '{$week_day}'
			AND starttime <= '{$now_time}' 
			AND endtime >= '{$now_time}' 		
			AND enable = 1 LIMIT 0, 1"; */
		$sql = "
			SELECT * 
			FROM `schedule`
			WHERE seat_id = '{$v['id']}'  
			AND weeknumber = '{$week_day}' 
			AND (starttime <= '{$now_time}'  OR endtime >= '{$now_time}' ) 
			AND enable = 1 
			LIMIT 1 
		";	
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
			
			$perk = "離峰時段";
			
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
			
			$perk = "一般時段";
			 
		}
		// $sql = "SELECT * FROM `usage_history` WHERE seat_id = '{$v['id']}' ORDER BY `usage_history`.`start_time` DESC LIMIT 0, 1";
		//$sql = "SELECT * FROM `use_status` WHERE seat_id = '{$v['id']}' LIMIT 0, 1";
		$sql ="
			SELECT /*現在時間-開始時間=秒數 ,秒數轉成 小時:分:秒*/
			(CASE u.power WHEN '1' THEN
			CONCAT(
			CASE WHEN ROUND(TIMESTAMPDIFF(SECOND, start_time, now())/3600)<10 THEN CONCAT('0',ROUND(TIMESTAMPDIFF(SECOND, start_time, now())/3600)) ELSE ROUND(TIMESTAMPDIFF(SECOND, start_time, now())/3600) END,':', /*小時*/
			CASE WHEN ROUND((TIMESTAMPDIFF(SECOND, start_time, now())%3600)/60)<10 THEN CONCAT('0',ROUND((TIMESTAMPDIFF(SECOND, start_time, now())%3600)/60)) ELSE ROUND((TIMESTAMPDIFF(SECOND, start_time, now())%3600)/60) END,':', /*分*/
			CASE WHEN ROUND(TIMESTAMPDIFF(SECOND, start_time, now())%60)<10 THEN CONCAT('0',ROUND(TIMESTAMPDIFF(SECOND, start_time, now())%60)) ELSE ROUND(TIMESTAMPDIFF(SECOND, start_time, now())%60) END /*秒*/
			) ELSE NULL END) AS proc_time, ROUND((u.onpeek_amount+u.offpeek_amount),2) AS onOff_amount, 
			(CASE u.power WHEN '1' THEN CEILING((u.onpeek_amount*se.rate)+(u.offpeek_amount*sc.rate)) ELSE NULL END) AS onoff_peek, u.* 
			FROM use_status u 
			INNER JOIN `seat` se ON se.id = u.seat_id /*一般費率*/
			INNER JOIN (SELECT DISTINCT seat_id, rate FROM `schedule`) sc ON sc.seat_id = u.seat_id  /*離峰費率*/
			WHERE u.seat_id = '{$v['id']}' LIMIT 1;
		";	

// echo $sql;
		$rs = $PDOLink->prepare($sql); 
		$rs->execute();
		$data2 = $rs->fetch();
		
		$curr = $data2['now_amount'];
		
		if($show == '1') {	
			$status = get_power($data2['power']);
		}
		
		#使用中
		if(!empty($data2['power']) == '1')
		{	
			$st_time = date_format(new DateTime($data2['start_time']), 'yy/m/d H:i');
			$proc_time =$data2['proc_time'];
			$amount = $data2['onOff_amount'];
			$cost = $data2['onoff_peek'].' 元';
			
		}#待機中
		else{
			$st_time = "";
			$proc_time ="";
			$amount = "";
			$cost = "";
		}
		
/* 	舊echo sprintf(
		$div_string,  
		$v['number'],  //車位
		$mode, 		 //收費模式
		$rate,    		 //充電費率
		$paid,   		 //預付度數
		$show,  		 //狀態
		$curr,    		 //目前電表度數
		$v['id'],  		 //id 隱藏
		$perk    		 //時段
		); */
		
		echo sprintf(
		$div_string,  
		$v['number'],  //車位
		$status,  		 //狀態
        $st_time, //開始充電時間
		$proc_time,     //已充電時間
	    $perk,    		 //時段
		$rate,    		 //充電費率
		$amount,       //已充電度數
		$cost,   		 //費用
		$v['id']  		 //id 隱藏
		);
	}

?>
</div>
</div>					
</section>
<!--20210104系統使用現況 END-->

<script>
$(document).ready(function() 
{
    $("span").find("c").each(function(index, v) 
	{ 
		//console.log(index +$(v).text());
		var status = $(v).text();
		if(status.trim() === "使用中") 
		{
			$(this).css("color","#24D354");
		}
    });
});

function set_rate(sid) {
	
	location.replace('RefundTimeControl.php?sid=' + sid);
	
}

function set_refund(sid) {
	
	location.replace('setrefund.php?sid=' + sid);
	
}
</script>
<?php
	include('footer_layout.php');
?>
