<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<?php 

// try {
// 	$test_PDOLink = new PDO('mysql:host=localhost;dbname=system_test_db','andy','aotech2018'); 
// 	$test_PDOLink->query("SET NAMES 'utf8'");
// 	$test_PDOLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// // 此處放 DB做事的程式碼
// }
// catch(PDOException $e) {
// print "連線失敗" . $e->getMessage();
// }

 	if(!$one_page) $one_page = 10; 	
	
	$kw = $_GET[kw];
 	$room_numbers_kw = $_GET[room_numbers_kw];
 	$room_numbers_floor_kw = $_GET[room_numbers_floor_kw];
	
	$sql = "SELECT * FROM `var_list` WHERE var_type = '棟別'";
	$sql_kw = "";
	$list_r = $PDOLink->prepare($sql); 
	$list_r->execute();
	$row2 = $list_r->fetchAll();
	
	foreach($row2 as $v) {
		$room_data[$v['var_value2']] = $v['var_name'];
	}
	
    if($kw) {
 	  $sql_kw.=" and (room_number like '%".$kw."%') ";
    } elseif ($room_numbers_kw) {
	  $sql_kw.=" and (room_number_type like '%".$room_numbers_kw."%') ";
	} elseif($room_numbers_kw && $room_numbers_floor_kw) {
	  $sql_kw.=" and (room_number_type like '%".$room_numbers_kw."%' and floor like '%".$room_numbers_floor_kw."%') ";
	} 

	if($room_numbers_kw)$sql_kw.=" and room_number_type='".$room_numbers_kw."'";
	if($room_numbers_kw && $room_numbers_floor_kw)$sql_kw.=" and room_number_type='".$room_numbers_kw."' and floor='".$room_numbers_floor_kw."' ";

	/* 房間table */
	$list_q="select * from room where 1 and room_number_type='".$room_numbers_kw."' ";
	$list_r = $PDOLink->prepare($list_q); 
	$list_r->execute();
	$row2 = $list_r->fetch();         
	$room_number_type=$row2[room_number_type];

	  /* 頁碼 */
	  $sql="select count(*) from room";
	  $rs=$PDOLink->query($sql);
	  $rownum=$rs->fetchcolumn();               
	  $pagesize=40;                             
	  if (isset($_GET['page'])) {               
	   $page=$_GET['page'];  
	  } else {
	   $page=1;                                 
	  }
	  
	  $pagenum=(int)ceil($rownum / $pagesize);  
	  $prepage =$page-1;                        
	  $nextpage=$page+1;                        
	  $pageurl='';
	  
	  if ($page == 1) {                         
	   $pageurl.='首页 | 上一页 | ';
	  } else {
	   $pageurl.="<a href=\"?page=1\">首页</a> | <a href=\"?page=$prepage\">上一页</a> | ";
	  }
	  
	  if ($page==$pagenum || $pagenum==0){     
	   $pageurl.='下一页 | 最後一页';
	  } else {
	   $pageurl.="<a href=\"?page=$nextpage\">下一页</a> | <a href=\"?page=$pagenum\">最後一页</a>";
	  }
?>
<!-- 教官查詢房號 name=form1 -->
<section id="main" class="wrapper">
<!-- <div class="rwd-box"></div><br><br> -->

<h2 style="margin-top: -30px;" align="center"><?php echo $lang->line("index.room_power_state"); ?></h2>
<div class="col-12"><a href='check.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a></div>

<!-- 返回 -->
<div class="container" style="text-align: center;">
	<!-- <a href='check.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a> -->
	<!-- <p align='center'></p> -->
	<div class="col-12"><a href="excelelecall_save.php" class="btn btn-success my-2"><?php echo $lang->line("index.export"); ?></a></div>
</div>
</section>
<!--<div class="back_icon">
	 <a  href='member.php'><img src="assets/image/icon4.png" alt=""></a> 
	<a href="check.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a><br>
</div>-->
<!-- 返回 End -->
<?php

	// 20200106 -- 改寫
	$sql = 'SELECT room_number_type FROM `room` GROUP BY room_number_type';
	$rs  = $PDOLink->Query($sql);
	$row = $rs->fetchAll();
	
	foreach($row as $k => $v) 
	{
		$room_number_type = $v['room_number_type'];
?>
<section class='content'>
	<div class='col-xs-12'>
		<div class='box'>
			<div class='box-header'><h2><?php echo $room_data[$room_number_type] ?></h2></div>
			<div class='box-body'>
					<?php 
						/* 房間狀態類別 */
						$sql = "SELECT * FROM room WHERE room_number_type = '{$room_number_type}' AND amonut > 0 ORDER BY room_number DESC";
						$rs  = $PDOLink->Query($sql);
						$rs->setFetchMode(PDO::FETCH_ASSOC);
						while($row=$rs->Fetch())
						{
							echo "<div style='width: 100%'>";
							echo "<div class='meter_boxs'>房號：".$row[room_number]."<br>";
								
							if($row[power_status] == '1') {
								echo "
									目前電表度數：<b style='color: blue;'>".$row[amonut]."</b><br>
									<!-- 上次最新時間：<b style='color: #000000;'>".$row[update_date]."</b><br> --> ";
							} elseif($row[power_status] == '2') {
								echo "
									目前電表度數：<b style='color: blue;'>".$row[amonut]."</b><br>
									<!-- 最新更新時間：".$row[update_date]."<br> -->";
							}	

							echo "</div>";
					
							print "
							<div sryle='clear: left;'></div>
							</div>";
						}
					?>
			</div>
		</div>
	</div>
</section>

<?php
	}
?>

<script>

// 回上一頁
function backs() { history.go(-1); }

function ch_page(x) 
{
	if(x) {
		document.getElementsByName("form1")[0].target='_self';
		document.getElementsByName("form1")[0].action='';
		document.getElementsByName("form1")[0].submit();
	}
}
</script>
<?php include('footer_layout.php'); ?>