<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php $langEnglishValue = $lang->line("index.if_if"); // value = en-us ?>
<!-- admin-login -->
<section id="main" class="wrapper">
	<div class='rwd-box'></div><br>
	<div class='rwd-box'></div><br>
	<h2 style="margin-top: -30px;" align="center"><?php Echo $lang->line("index.admin_login"); ?></h2><br>
	<div class="inner">
		<div class="row">
		    <div class="col-12">      
					<form name="form2" onsubmit="return chk_admin();">
						<div class="form-group row">
							<!-- <label for="example-text-input" class="col-4 col-form-label">教官管理帳號</label> -->
							<div class="col-12">
								<input class="form-control" type="text" name="id" placeholder="<?php Echo $lang->line("index.admin_username"); ?>" id="example-text-input">
							</div> 
						</div>
						<div class="form-group row"> 
							<!-- <label for="example-search-input" class="col-4 col-form-label">管理密碼</label> -->
							<div class="col-12">
								<input class="form-control" type="password" name="pwd" placeholder="<?php Echo $lang->line("index.password"); ?>" id="example-search-input">
							</div>
						</div>
						<input style="width: 100%;" class="form-control" type='submit' value='<?php Echo $lang->line("index.admin_login"); ?>' name='send2'>
					</form>
			  </div>
		</div>
	</div>
</section>
<script>
function chk_admin()
{
	if(document.getElementsByName("form2")[0].send2.value)
	{
		var admin_id=document.getElementsByName("form2")[0].id;
		if(!admin_id.value)
		{
			alert('請輸入帳號');
			admin_id.focus();
			return false;
		}
		var admin_pwd=document.getElementsByName("form2")[0].pwd;
		if(!admin_pwd.value)
		{
			alert('請輸入密碼');
			admin_pwd.focus(); 
			return false;
		}														
		document.getElementsByName("form2")[0].action='chk_adminlogin.php';
		document.getElementsByName("form2")[0].target='_self'; 					// _blank, _self, _parent, _top
		document.getElementsByName("form2")[0].method='post';
		document.getElementsByName("form2")[0].enctype='multipart/form-data';
	}
}
</script>
<?php include('footer_layout.php'); ?>