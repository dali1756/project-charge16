<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php $langEnglishValue = $lang->line("index.if_if"); // value = en-us ?>
<section id="main" class="wrapper">
	<div class='rwd-box'></div><br>
	<h2 class="rwd-login-title" style="margin-top: -30px;" align="center"><?php echo $lang->line("index.user_login"); ?></h2><br>
	<div class="rwd-box"></div>
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong><?php echo $lang->line("index.Password_error"); ?></strong>
		</div>
	<?php } ?>
	</div>
	<div class="inner">
		<div class="row">
			<div class="col-8">
				<form name="form1" onsubmit="return chk();">
					<div class="form-group row">
						<!-- <label for="example-text-input" class="col-4 col-form-label">登入學號</label> -->
						<div class="col-10">
							<input class="form-control" type="text" name="username" placeholder="<?php Echo $lang->line("index.username"); ?>" id="example-text-input">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-10">
							<input pattern="[0-9]*" inputmode="numeric"  class="form-control" type="password" name="password" placeholder="<?php Echo $lang->line("index.password"); ?>" id="example-search-input">
							<b style="color: #a7a4a4; font-size: 10px;"><?php echo $lang->line("index.Password_default"); ?></b>
						</div>
					</div>
					<input style="width: 83%;" class="form-control" type='submit' value='<?php Echo $lang->line("index.login"); ?>' name='send'>
				</form>
			</div><!-- col-8 -->
			<!-- Operating Manual 操作手冊 -->  
			<div class="col-4"> 
				<div class="form-group row"> 
				  <!-- <label for="example-text-input" class="col-12 col-form-label" style="font-size: 20px;">溫馨提供</label> --> 
					<?php /* if($langEnglishValue == 'en-us'){ ?>
						<div class="col-10" style="margin-top: 0px; text-align: center;"> 
							<a class="form-control" href="OperatingManual/網頁平台操作手冊/智慧電力管理系統網頁平台操作手冊（學生）(英文)-東華大學-V04.pdf" style="background-color: #e7c972;" target="_blank"><?php Echo $lang->line("index.web_page_platform_user_manual"); ?></a>
						</div>
						<div class="col-10" style="margin-top: 38px; text-align: center;">
							<a class="form-control" href="OperatingManual/儲值主機操作手冊/電力系統主機及操作說明(英文)-東華大學-V07.pdf" style="background-color: #e7c972;" target="_blank"><?php Echo $lang->line("index.top_up_machine_user_manual"); ?></a>
						</div>
						<div class="col-10" style="margin-top: 38px; text-align: center;">
							<a class="form-control" href="OperatingManual/常見問題說明/智慧電力系統說明手冊Q_A(學生)-東華大學-V04.pdf" style="background-color: #e7c972;" target="_blank"><?php echo $lang->line('index.faq'); ?></a>
						</div>
					<?php } else { ?>
						<div class="col-10" style="margin-top: 0px; text-align: center;">
							<a class="form-control" href="OperatingManual/網頁平台操作手冊/智慧電力管理系統網頁平台操作手冊（學生）-東華大學-V04.pdf" style="background-color: #e7c972;" target="_blank"><?php Echo $lang->line("index.web_page_platform_user_manual"); ?></a>
						</div>
						<div class="col-10" style="margin-top: 38px; text-align: center;">
							<a class="form-control" href="OperatingManual/儲值主機操作手冊/電力系統主機及操作說明-東華大學-V07.pdf" style="background-color: #e7c972;" target="_blank"><?php Echo $lang->line("index.top_up_machine_user_manual"); ?></a>
						</div>
						<div class="col-10" style="margin-top: 38px; text-align: center;">
							<a class="form-control" href="OperatingManual/常見問題說明/智慧電力系統說明手冊Q_A(學生)-東華大學-V04.pdf" style="background-color: #e7c972;" target="_blank"><?php echo $lang->line('index.faq'); ?></a>
						</div>
					<?php } */ ?>
				</div> 
			</div><!-- col-4 -->
		</div>
	</div>
</section>
<script>
function chk()
{
	if(document.getElementsByName("form1")[0].send.value)
	{
		var user_name=document.getElementsByName("form1")[0].username;
		if(!user_name.value)
		{
			alert('請輸入帳號');
			user_name.focus();
			return false;
		}
		var pwd=document.getElementsByName("form1")[0].password;
		if(!pwd.value)
		{
			alert('請輸入密碼'); 
			pwd.focus();
			return false;
		}														
		document.getElementsByName("form1")[0].action='chk_login.php';
		document.getElementsByName("form1")[0].target='_self'; 					// _blank, _self, _parent, _top
		document.getElementsByName("form1")[0].method='post';
		document.getElementsByName("form1")[0].enctype='multipart/form-data';
	}
}
</script>
<?php include('footer_layout.php'); ?>