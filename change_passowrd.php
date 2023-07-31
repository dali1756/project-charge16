<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<?php 
	if(!isset($_SESSION['user']['id'])){
		header("location: login.php"); 
		exit();
	}
?>
<!-- 會員中心  -->
<section id="main" class="wrapper">
	<div class="rwd-box"></div><br><br>
	<h2 style="margin-top: -30px;" align="center"><?php echo $lang->line("index.password_change"); ?></h2><br>

	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong></strong><?php echo $lang->line("index.Password_error"); ?>
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong></strong><?php echo $lang->line("index.Your_information_is_incompleted"); ?>
		</div>
	<?php } elseif ($_GET[error] == 3) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong></strong><?php echo $lang->line("index.Your_information_is_incompleted"); ?>
		</div>
	<?php } elseif ($_GET[error] == 4) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong></strong><?php echo $lang->line("index.Password_should_contain"); ?>
		</div>
	<?php } elseif ($_GET[error] == 5) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong></strong><?php echo $lang->line("index.Password_is_number"); ?>
		</div>
	<?php } elseif ($_GET[success]) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong></strong><?php echo $lang->line("index.Password_success"); ?>
		</div>
	<?php } ?>
	</div>

	<div class="inner">
		<div class="row">
			<a  href='member.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a><br>
				<div class="col-12">
					<!-- 儲值 table -->
					<form action="chk_pwd.php" method="post">
					  <input type="hidden" name="id" value="<?php echo $_SESSION[user][id]; ?>">
					  <div class="form-group">
					    <label for="exampleInputEmail1"><?php echo $lang->line("index.title_please_enter_the_original_password"); ?></label>
					    <input pattern="[0-9]*" inputmode="numeric" name="old_pwd" type="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="<?php echo $lang->line("index.please_enter_the_original_password"); ?>">
					    <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
					  </div>
					  <div class="form-group">
					    <label for="exampleInputPassword1"><?php echo $lang->line("index.title_please_set_new_password"); ?></label>
					    <input pattern="[0-9]*" inputmode="numeric" name="new_pwd" type="password" class="form-control" id="exampleInputPassword1" placeholder="<?php echo $lang->line("index.please_set_new_password"); ?>">
					  </div>
					  <div class="form-group">
					    <label for="exampleInputPassword1"><?php echo $lang->line("index.please_re-enter_new_password"); ?></label>
					    <input pattern="[0-9]*" inputmode="numeric" name="check_new_pwd" type="password" class="form-control" id="exampleInputPassword1" placeholder="<?php echo $lang->line("index.please_re-enter_new_password"); ?>">
					  </div>
					  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.password_change"); ?></button>
					</form>
					<!-- End 儲值 table -->
				</div>
		</div>
	</div>
</section>
<!-- 會員中心 End -->
<script>
//回上一頁
function backs()
{
	history.go(-1);
}
</script>
<?php iframe('');?>
<?php include('footer_layout.php'); ?>