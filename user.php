<?php 
include('header_layout.php'); 
include('nav.php');
include('chk_log_in.php');

// header('Content-type: text/html; charset=utf-8');			//指定utf8編碼 

$user = array();
$menu = array();

$all_user;
$all_data;

$sql = "SELECT * FROM `admin` WHERE `status` = 'Y'";
$sth = $PDOLink->prepare($sql);
$sth->execute(array());
$result = $sth->fetchAll();

foreach($result as $v) 
{
	$user[$v['sn']] = $v['cname'].' '.$v['id'];
	$all_user[$v['sn']] = array('cname' => $v['cname'], 'id' => $v['id']);
}

$sql = "SELECT * FROM `menu_list` WHERE `status` = 'Y'";
$sth = $PDOLink->prepare($sql);
$sth->execute(array());
$result = $sth->fetchAll();

foreach($result as $v) 
{
	$menu[$v['id']] = $v['item_name']; // .' '.$v['id'];
}

$sql = "SELECT * FROM `menu_access`";
$sth = $PDOLink->prepare($sql);
$sth->execute(array());
$all_data = $sth->fetchAll();
// print_r($user); exit;
?>

<?php if($admin_id) { ?>
<section id="main" class="wrapper">
	<!-- <div class="rwd-box"></div><br>
	<div class="rwd-box"></div><br> -->
	<h2 style="margin-top: -30px;" align="center">最高系統管理</h2>
	<div class="col-12"><a href='system_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a></div>
	
	<!-- <div class="container" style="text-align: left;">
		<a href='system_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a>
	</div> -->
	
	<div class="inner">
		<div class="row"> 
			<div class="col-12">
				<div class="card">
				  <div class="member_header card-header">            
<?php 
					if($_SESSION['admin_user']['id'] == 'andy'){ 
						echo '超級管理員';
					} else {  
						echo $lang->line("index.instructor_info");
					} 
?>
				  </div>
				  
				  <div class="container mt-3">
					<form id='menu_access' name='menu_access' action='chk_menu_access.php' method='POST'>
						
					  <div class='pb-3 pt-3'>管理人員 : </div>
					  <select name="user">
						<option value=''>請選擇</option>
<?php
						foreach($user as $k => $v)
						{
							// 20191219 -- AO管理員不顯示
							if($k == 127) {	continue; } 
							echo "<option value='{$k}'>{$v}</option>";
						}
?>
					  </select>
					  
					  
					  <div class='pb-3 pt-5'>功能 :</div>
					  <div class="container">
<?php
						foreach($menu as $k => $v)
						{
							
							$desc = $lang->line($v) == '' ? $v : $lang->line($v);
							
							echo "<input type='checkbox' id='menu_list{$k}' name='menu_list[]' value='{$k}'>";
							echo "<label for='menu_list{$k}'>".$desc."</label>";
						}
?>
						</div>
						<div class="row justify-content-center">
							<button type="button" id='btn_submit' class="btn btn-primary btn-sm">更新</button>
						</div>
					</form>
				  </div>
				</div>
			</div> 
		</div>
	</div>
</section>

<?php } ?>

<table>
<?php

	if($menu & $all_data) 
	{
		$count = 0;
		foreach($all_data as $row) 
		{
			if($count == 0) {
				echo "<tr><th>&nbsp;</th>";
				foreach($menu as $k => $v) {
					$desc = $lang->line($v) == '' ? $v : $lang->line($v);
					echo "<th>{$desc}</th>";
				}
				echo "</tr>";
			}
		
			$sn   = $row['sn'];			
			$name = $all_user[$sn]['cname'].' '.$all_user[$sn]['id'];
			$show = explode(',', $row['access']);
			
			echo "<tr><td>{$name}</td>";
			foreach($menu as $k => $v) {
				
				if(in_array($k, $show)){
					echo "<td>O</td>";
				} else {
					echo "<td>&nbsp;</td>";
				}
			}
			
			echo "</tr>";
			
			$count++;
		}
	}

?>
</table>

<script>

$('#btn_submit').click(function(){

	if($('select[name=user]').val() == '') {
		
		return false;
	}
	
	$('#menu_access').submit();
});

</script>

<?php include('footer_layout.php'); ?>