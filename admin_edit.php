<?php 

	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');

	$sn = $_SESSION['admin_user']['sn'];
	$id = $_SESSION['admin_user']['id'];

	$list_q = "SELECT * FROM admin WHERE 1 AND id = '{$id}' ";
	$list_r = $PDOLink->prepare($list_q); 
	$list_r->execute();
  
	$row    = $list_r->fetch();
	$sn     = $row['sn'];
	$id     = $row['id'];
	$pwd    = $row['pwd'];
	$cname  = $row['cname'];
	$mobile = $row['mobile'];
	$ext    = $row['ext'];
	$email  = $row['email'];
?>
<section id="main" class="wrapper">
	<!-- <div class='rwd-box'></div> -->	
	<h2 style="margin-top: -30px;" align="center">密碼修改</h2>
	<div class="col-12"><a href="member.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	
	<div class="row">
	<?php if($_GET[success]){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>系統已成功設定!!</strong> 
		</div>
	<?php } elseif ($_GET[error] == 1) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>您的"舊密碼"沒填或輸入錯誤!!</strong>
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>您的"新密碼"沒填!!</strong>
		</div>	
	<?php } elseif ($_GET[error] == 3) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>您的"確認密碼"沒填!!</strong>
		</div>			
	<?php } ?>
	</div>
	<div class="inner">
		<div class="panel">
			<div class="panel-body">
			
				<form action="admin_upd.php" method="post">
					<table class='table1'>
						<tr>
							<td width='15%' nowrap>管理帳號</td>
							<td><input readonly="readonly" type="text" class="form-control" name="id" placeholder="學號" value="<?php echo $id; ?>"></td>
						</tr>
						<tr>
							<td>您的舊密碼</td>
							<td><input type="password" class="form-control" name="o_pwd" placeholder="請輸入原密碼"></td>
						</tr>
						<tr>
							<td>您的新密碼</td>
							<td><input type="password" class="form-control" name="new_pwd" placeholder="請輸入新的密碼"></td>
						</tr>
						<tr>
							<td>確認新密碼</td>
							<td><input type="password" class="form-control" name="new_pwd_check" placeholder="請再輸入一次新的密碼"></td>
						</tr>
					</table>
					<input type="hidden" name="sn" value="<?php echo $sn; ?>">  
					<button type='submit' class='form-control btn-primary'>確認更新</button>
				</form>
				
			</div>
		</div>
	</div>
</section>

<style>
.table1>tbody>tr>td{
	text-align: right;
    vertical-align: middle;
}
</style>

<script>

</script>
<?php // iframe('');?>
<?php include('footer_layout.php'); ?>