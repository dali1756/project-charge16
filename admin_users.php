<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?> 
<?php
  //取第一筆(確認資料存在)
  $user_list_q="select * from room where 1 and id='1' "; 
  $user_list_r = $PDOLink->prepare($user_list_q); 
  $user_list_r->execute(); 
  $row2 = $user_list_r->fetch();         
  $UserRoom=$row2[id];

//   $user_list_q2="select * from BallGround_machine where 1 and id='1' "; 
//   $user_list_r2 = $PDOLink->prepare($user_list_q2); 
//   $user_list_r2->execute(); 
//   $row22 = $user_list_r2->fetch();          
//   $UserRoom2=$row22[id];    

?>
<!-- admin_users.php -->
<section id="main" class="wrapper"> 
	<!-- RWD修正 --> 
	<!-- <div class="rwd-box"></div><br><br>    
	<h2 style="margin-top: -30px;" align="center">名單管理建立</h2><br> -->
	
	<h2 style="margin-top: -30px;" align="center">名單管理建立</h2>
	<div class="col-12"><a href="data_manage.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	
	<div class="row">
	<?php if($_GET[success] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>資料成功上傳！ </strong>
		</div>
	<?php } elseif ($_GET[room_title] == 2) { ?> 
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>注意：以下資料無法匯入！<br>
			1.請確認是否完成搬出作業。<br>
			2.請確認匯入名單是否有誤。
			<br>
			<?php
			
				/* 待開啟功能.... */
				$RoomSetting_sql="select DISTINCT id_card from roomErrorAlert where data_type = '2' ";
				$RoomSetting_sql_rs=$PDOLink->Query($RoomSetting_sql);
				$RoomSetting_sql_rs->setFetchMode(PDO::FETCH_ASSOC);
				$nn=0;
				while($RoomSetting_sql_row=$RoomSetting_sql_rs->Fetch()){
						$RefundArr = array($RoomSetting_sql_row[id_card]);
						foreach ($RefundArr as &$value) {
								echo "<br>悠遊卡卡號：".$value = $value."";
						}	
				$nn++;
				}

			?>
			</strong>
		</div>
	<?php } elseif ($_GET[room_title] == 3) { ?> 
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>資料成功上傳！</strong>
		</div>
	<?php } elseif ($_GET[success] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>Error 您沒有上傳檔案！</strong>
		</div>
	<?php } elseif ($_GET[success] == 5) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>全部搬走！</strong>
		</div>
	<?php } elseif ($_GET[success] == 3) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>注意：資料無法匯入！<br>
			1.請確認是否完成搬出作業。<br>
			2.請確認匯入名單是否有誤。<br>
			3.請確認是否房間超出人數。</strong>
		</div>
	<?php } ?>
	</div>
	<br><br><br>   
<!-- 	<div class="inner">
		<div class="row">
			<div class="col-12">
					<div class="alert alert-success" role="alert">
					  <h4 align="center" class="alert-heading">貼心提醒 上傳注意事項</h4>
					  <p>
					  	1.上傳請按照以下格式建檔，Excel 從A~F欄位順序：<br>
					  	房號、學號、卡號、學生姓名、班級、預設金額。<br><br>
					  	2.上傳時盡量資料內容不能為空白，金額是無就輸入0。<br><br>
						3.本系統學生上傳條件依據學號，因此學號不得重複(卡號也是唯一)。<br><br>
					  </p>
					</div>
			</div>	
		</div>
	</div> -->
	<br>
	<div class="inner">
		<div class="row"> 
			<div class="col-6">
				<div class="card">
				    <div class="card-body">	
						<form id='mform1' action="UploadSubMember2019.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1"> <?php echo $lang->line("index.update_room_member_import"); ?> </label> 
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>                                                     
						  <button type="button" class="btn btn-primary" onclick='checkroom1()'><?php echo $lang->line("index.change_file_up"); ?></button>
						</form>
				    </div>
				</div>
			</div>
			<div class="col-6">
				<div class="card">                
				    <div class="card-body">	                     
						<form action="UploadMember2019.php" method="post" enctype='multipart/form-data'>
							<div class="form-group">
								<label for="exampleInputEmail1"> <?php echo $lang->line("index.all_room_member_import"); ?> </label> 
								<input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
							</div>
							<button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						</form>                                            
						<!-- <a href="demo_member.xlsx">Excel Upload Demo file</a><br> -->
						<!-- <a target="_blank" href="test_memberss.php">現有學生名單</a><br> -->
						<?php //if($_SESSION[admin_user][id] == 'andy'){ ?>
							<a role="button" class="btn btn-secondary" onclick="return confirm('確認提示，您確定全部搬走嗎?\n(確認後所有房間為空，學生還是存在)');" href="member_all_domp.php">
								<?php echo $lang->line("index.clear_member"); ?> 
							</a>
						<?php //} ?>
				    </div>                   
				</div>   
			</div>
			<!-- <div class="col-6">
				<div class="card"">
				    <div class="card-body">	 
						<form action="ball_data_excel_upload_up2017.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1">匯入球場臨時資料</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"> 房間資料已上傳完畢 Root ok</label>
						   </div>
						</form>
				    </div>
				</div>
   			</div>-->
			<?php if(!$UserRoom){ ?> 
			<div class="col-6">
				<div class="card">
				    <div class="card-body">	
						<form action="UploadRoom.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1">上傳宿舍房間資料</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"><!-- 房間資料已上傳完畢 -->Root ok</label>
						   </div>
						</form>
				    </div>
				</div>
   			</div>
			<?php } ?>
			
			<div class="col-6">
				<div class="card">
					<form action="excelmemberroom_save.php" method="post">
						<button type="submit" class="btn btn-success">現有住宿生名單匯出</button>
					</form>
				</div>
   			</div>
			<!-- <div class="col-6">
				<div class="card"">
				    <div class="card-body">	
						<form action="UploadIlluminationRoom.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1">上傳籃球場資料</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"></label>
						   </div>
						</form>
				    </div>
				</div>
			</div> -->
			<!-- <div class="col-6">
				<div class="card"">
				    <div class="card-body">	
						<form action="UploadIeWashingRoom.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1">上傳洗衣機資料</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"></label>
						   </div>
						</form>
				    </div>
				</div>
			</div> -->  
			<!-- <div class="col-6">
				<div class="card"">
				    <div class="card-body">	
						<form action="UploadDebugCard.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1">測試中(非工程人員請勿使用))</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"></label>
						   </div>
						</form>
				    </div>
				</div> 
			</div> -->
			<!-- col-6 -->
			<!-- <div class="col-6">
				<div class="card"">
				    <div class="card-body">	
						<form action="UploadCChangeCard.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group">
						    <label for="exampleInputEmail1">轉卡</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"></label>
						   </div>
						</form>
				    </div>
				</div> 
			</div>  -->
			<!-- col-6 End -->
			<!-- 
			<div class="col-6"> 
				<div class="card"">
				    <div class="card-body">	
						<form action="UploadBallGroundSchedule.php" method="post" enctype='multipart/form-data'>
						  <div class="form-group"> 
						    <label for="exampleInputEmail1">球場時段上傳</label>
						    <input name="link1" type="file" id="file" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
						  </div>
						  <button type="submit" class="btn btn-primary"><?php echo $lang->line("index.change_file_up"); ?></button>
						  <div class="form-group">
						    <label for="exampleInputEmail1"></label>
						   </div>
						</form>
				    </div>
				</div>
			</div>   
			-->



		</div>  
	</div>
</section>

<script>

function checkroom1() {
	
	if(confirm('提示，確定匯入嗎?\n(請確認是否完成搬出作業)')) {
				
		$('#mform1').submit(); 
		return false;
	}
}

</script>

<?php include('footer_layout.php'); ?>