<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<?php 
  $list_q="select * from member where 1 and id='".$_GET[id]."' ";
  $list_r = $PDOLink->prepare($list_q); 
  $list_r->execute();
  $row = $list_r->fetch();         
  $user_room_id=$row[user_room_id];
?>
<!-- 會員中心  -->
<section id="main" class="wrapper">
	<h2 style="margin-top: -30px;" align="center">設定房號</h2><br>

	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>Error</strong>您舊的密碼輸入有誤喔，請再想想!!
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>Error</strong>新密碼沒填，請重新填寫!!
		</div>
	<?php } elseif ($_GET[success]) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>Success</strong>您的密碼已成功更新!!
		</div>
	<?php } ?>
	</div>

	<div class="inner">
		<div class="row">
			<a href="#" onclick="backs();"><i class="fas fa-chevron-circle-left"></i></a><br>
				<div class="col-12">
					<!-- 儲值 table -->
					<form action="chk_roomnumber.php" method="post">
					<input type="hidden" name="id" value="<?php echo $_GET[id]?>">
					<?php print " 
				      <div class='form-group'>
				        <label for='exampleInputEmail1'> 選擇設定房號 </label> ";
				        $sel_q="select * from user_room";
				        $sel_r=db_q($sel_q);
				        if(r_size($sel_r))
				        {
				          print "<select class='form-control' size='1' name='user_room_id'>";
				          while($rs=get_data($sel_r))
				          {
				            $v_name=$rs[room_number];
				            $v_value=$rs[id];
				            print "<option value='".$v_value."'";if($user_room_id==$v_value)print " selected "; print ">".$v_name."</option>";
				          }
				          print "</select>";
				        }
				        else
				        {
				          print "<input class='form-control' type='text' name='user_room_id' value='".$user_room_id."'   style='width:400px'>";
				        }
				     ?>
				     <?php print " </div> "; ?>
					  <button type="submit" class="btn btn-success">完成設置</button>
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