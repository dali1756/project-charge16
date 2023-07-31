<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php //include('chk_log_in.php'); ?>
<section id="main" class="wrapper">
	
	<h2 style="margin-top: -30px;" align="center">問題反應</h2>
	
	<div class='rwd-box'></div>
	
	<div class="row">
		<?php if($_GET[success] == 1){ ?>
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
			  <strong>您的問題我們已收到，我們會盡快為您服務！</strong>
			</div>
		<?php } elseif($_GET[error] == 2){ ?>
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
			  <strong><?php echo $lang->line("index.error"); ?>!!</strong>
			</div>
		<?php } elseif($_GET[error] == 3){ ?>
			<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
			  <strong>您有選項沒有填寫！</strong>
			</div>
		<?php } ?>
	</div>
	
	<div class="inner">
		<div class="row">
			<div class="col-12">
				<div style="padding: 20px;" class="alert alert-warning" role="alert">
					<h4 align="center" class="alert-heading"><b>客服反應時段</b></h4>
					<p style="font-size: 18px; text-align: center;">
						我們的客服時間為 <b>週一 ~ 週五9:00~18:00</b>，非此時段的問題反應，將會在下個客服時間儘速回覆您。<br>
						感謝您的耐心等候。若是無選項可勾時，請留行動電話，告知客服時間。
					</p>
				</div>
			</div>	
		</div>
	</div>
	
	<div class="inner">
		<form action="content_us_add.php" enctype="multipart/form-data"  method="post">
		<table border='1'>
			<tr>
				<td width='15%' nowrap><label for="example-text-input" class="col-4 col-form-label"><?php echo $lang->line("index.basic_information"); ?></label></td>
				<td>			  	  
					<div class="form-group">
						<input id="RoomNumberValue" class="form-control" type="text" name="room_number" placeholder="宿舍房號" >
					</div>
				</td>
				<td>
					<div class="form-group">
						<input id="UsernameValue" class="form-control" type="text" name="username_number" placeholder="學號" >
					</div>
				</td>
				<td>
					<div class="form-group">
						<input id="TitleValue" class="form-control" type="text" name="title" placeholder="姓名" >
					</div>
				</td>
			</tr>
			<tr>
				<td nowrap><label for="example-text-input" class="col-4 col-form-label">處裡進度回覆方式</label></td>
				<td>
					<div class="form-group">
						<input class="form-control" type="text" name="phone" placeholder="手機">
					</div>
				</td>
				<td colspan='2'>
					<div class="form-group">
						<input id="eMailValue" class="form-control" type="email" name="email" placeholder="E-Mail"  >
					</div>
				</td>
			</tr>
			<tr>
				<td nowrap><label for="example-text-input" class="col-4 col-form-label">儲值主機操作</label></td>
				<td>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" name="host_type[]" value="無此卡號" id="defaultCheck12">
					  <label class="form-check-label" for="defaultCheck12">無此卡號</label>
					</div>
					
					
				</td>
				<td colspan='2'>
					<div class="form-check">
					  <input class="form-check-input" type="checkbox" name="host_type[]" value="非學生證悠遊卡餘額不足的儲值失效" id="defaultCheck14">
					  <label class="form-check-label" for="defaultCheck14">非學生證悠遊卡餘額不足的儲值失效</label>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<div class="form-check">
						<input type="checkbox" class="form-check-input host_type_check" name="host_type[]" id="exampleCheck1">
						<label class="form-check-label" for="exampleCheck1">其他</label>
					</div>
				</td>
				<td colspan='2'>
					<div class="form-group" style='margin:0 -130px auto; width:50%'>
						<input type='text' class="form-control" id='exampleCheck1txt'>
					</div>
				</td>
			</tr>
			<tr>
				<td nowrap><label for="example-text-input" class="col-4 col-form-label">房內卡機使用</label></td>
				<td colspan='3'>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="room_type[]" value="卡片感應後綠燈未亮" id="defaultCheck6">
						<label class="form-check-label" for="defaultCheck6">卡片感應後綠燈未亮</label>
						<input class="form-check-input" type="checkbox" name="room_type[]" value="綠燈亮但冷氣無法開啟" id="defaultCheck13">
						<label class="form-check-label" for="defaultCheck13">綠燈亮但房內電源無法開啟</label>
						<input class="form-check-input" type="checkbox" name="room_type[]" value="日期及時間異常" id="defaultCheck9">
						<label class="form-check-label" for="defaultCheck9">日期及時間異常</label>
					</div>
				</td>
			</tr>
			<tr>
				<td nowrap></td>
				<td colspan='3'>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="room_type[]" value="出現 USER INVALID" id="defaultCheck7">
						<label class="form-check-label" for="defaultCheck7"><?php echo $lang->line("index.USER_INVALID"); ?></label>
						<input class="form-check-input" type="checkbox" name="room_type[]" value="出現CHECK BLANCE" id="defaultCheck8">
						<label class="form-check-label" for="defaultCheck8"><?php echo $lang->line("index.CHECK_BLANCE"); ?></label>		
						<input class="form-check-input" type="checkbox" name="room_type[]" value="學生證號及系統金額有誤" id="defaultCheck11">
						<label class="form-check-label" for="defaultCheck11">學生證號及系統金額有誤</label>
					</div>
				</td>
			</tr>
			<tr>
				<td nowrap></td>
				<td colspan='2'>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="room_type[]" value="使用累計超過二小時-無扣款顯示" id="defaultCheck10">
						<label class="form-check-label" for="defaultCheck10">使用累計超過二小時-無扣款顯示</label>
						<input type="checkbox" class="form-check-input room_type_check" name="room_type[]" id="exampleCheck2">
						<label class="form-check-label" for="exampleCheck2">其他</label>
					</div>
				</td>
				<td>
					<div class="form-group" style='margin:0 -130px auto; width:90%'>
						<input type='text' class="form-control" id='exampleCheck2txt'>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan='4' align='center'>
					<input id="loading-body2-btn" class="button alt" style='width:20%; padding: 0;' type='submit' value='確認送出' name='send'>
				</td>
			</tr>
		</table>
		</form>
	</div>
</section>

<div style='clear:both;'></div>

<style>

.wrapper {
	
	font-family : Microsoft JhengHei;
	// font-size : 14pt;
	
}

#footer {
	
	/* padding: 2em 0 4em 0; */
	// margin-top : 100px;
	text-align: center;
	width : 100%;
	left: 0;
	bottom: 0;
	height: 74px;
	background: #006666;
}

.table>tbody>tr>td{
	// text-align: right;
    vertical-align: middle;
}
</style>

<script>
 //$('.PPPPPPPPPPPPPPPPPPPPPPP').css("display","none");

 $('.host_other_css').css("display","none");
 $('.room_other_css').css("display","none");

 $('.host_type_check').change(function(){
	$('.host_other_css').css("display","inline");
 });
 $('.room_type_check').change(function(){
	$('.room_other_css').css("display","inline");
 });

//  $("#RoomNumberValue").change(function(){

//  	NumberIf = $(this).val();
//  	TotalValue = NumberIf.length;

//  	if(TotalValue == '4'){
//  		$('.PPPPPPPPPPPPPPPPPPPPPPP').css("display","inline");
//  	} else {
//  		$('.PPPPPPPPPPPPPPPPPPPPPPP').css("display","none");
//  	}

//  });

$('#loading-body2-btn').click(function() {

   // 取值
   room_number = $("#RoomNumberValue").val();
   username = $("#UsernameValue").val();
   title = $("#TitleValue").val();
   email = $("#eMailValue").val();
   
   
   $('#exampleCheck1').val($('#exampleCheck1txt').val());
   $('#exampleCheck2').val($('#exampleCheck2txt').val());
   
   // 防呆判斷
   if (room_number && username && title && email) {

		alert($('#exampleCheck1').val()); return false;

	    $('body').loading({
	        stoppable: true,
	        message: '信件正在發送中.....',
	        theme: 'dark'
	    });
		
   } else {
   	
		alert('【提示】基本資訊和e-mail尚未填寫完');
		return false;
   
   }

});
</script>

<?php include('footer_layout.php'); ?>