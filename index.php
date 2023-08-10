<?php 
include('header_layout.php');
include('nav.php');

if (isset($_SESSION["text"]) && $_SESSION["text"] != "") {
    echo '<script language = "javascript">';
    echo 'alert("'. $_SESSION["text"]. '")';
    echo '</script>';
    unset($_SESSION["text"]);   // 清除session
}


$message_arr = array('1' => '帳號密碼錯誤，請重新登入');
$contact = "";
$message = $message_arr[$_GET['error']];

$title   = $lang->line("index.title");
$desc    = $lang->line("index.descrip");

?>

	<section id='banner'>  
		<div style='margin-top: 0px;' class='content'>
			<h1 style='font-weight:bold;text-shadow: shadow1, shadow2, shadow3;'>&nbsp;<?php // echo $title ?>&nbsp;</h1>
			<p style='font-weight:bold;font-size: 2.5em; color: #694124; text-shadow: shadow1, shadow2, shadow3; text-shadow: 2px 0px 1px #cc431f, 9px 1px 0px rgba(0,0,0,0.16);'>
				<?php // echo $desc ?> &nbsp;
			</p>
		</div>
	</section>

<?php include('footer_layout.php'); ?>



<div id='identity'>
	<form id='adminlogin' action='chk_adminlogin.php' method='post'>
		<div class='login form title mb-5'>管理員登入</div>
		
		<div class='login form'>
			<div class='div_column'>
				<span class='glyphicon glyphicon-user'></span>
			</div>
			<div class='div_column'>帳號&nbsp;</div>
			<div class='div_column'>
				<input class="form-control" type="text" name="id" placeholder="帳號" id="example-text-input">
			</div>
		</div>
		<div class='login form'>
			<div class='div_column'>
				<span class='glyphicon glyphicon-lock'></span>
			</div>
			<div class='div_column'>密碼&nbsp;</div>
			<div class='div_column'>
				<input class="form-control" type="password" name="pwd" placeholder="<?php Echo $lang->line("index.password"); ?>" id="example-search-input">
			</div>
		</div>
		<div class='login form notice'><?php echo $message; ?></div>
		<div class='login form'>
			<button id='btn_login' class='button alt login mt-5 mb-5'>登入</button>
		</div>
	</form>
</div>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


<style>
#identity {

	display  : none;
	
	position : absolute;
	top: 50%;
	left: 50%;
	
	width  : 340px;
	height : 400px;
	
	margin: -200px 0 0 -170px;
	
	background : #fff;
	
	z-index: 99;
	
	border:  1px solid #666;
	border-radius : 5px;
}

.login.form {
	
	height      : 70px;
	line-height : 70px;
	
	text-align: center;
	vertical-align: middle;
	
	margin: 0 auto;
}

.login.form.title {
	
	height      : 100px;
	line-height : 100px;
	
	background : #006666;
	
	font-weight : bold;
	font-family : Microsoft JhengHei;
	font-size   : 21px;
	color       : #fff;
	
	border-radius : 5px;
}

.login.form.notice {
	color       : red;
	height      : 20px;
	line-height : 20px;
}

.div_column {
	display:inline-block;
}

.glyphicon {
	
	font-size: 16px;
}

.button.alt {
	padding: 0;
}

.button.alt.login {
	width: 85%;
}
</style>

<script>
	
	var counter = 0;
	var chk_msg = "<?php echo $message; ?>";
  
	if(chk_msg != '') {
		$('#identity').show();
	}
  
	$('#btn_login').click(function() {
		$('#adminlogin').submit();
	});
		
	$(document).ready(function() {
			
		// 底圖 -- 20200227
		$('.button.alt').click(function() {
			
			if($('#identity').css('display') == 'block') {
				$('#banner').css("background-image", "url(images/main-background.png)");
			} else {
				$('#banner').css("background-image", "url(images/main-background-opacity.png)");
			}
			
		});		
	});
	
</script>