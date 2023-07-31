<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<?php 
	$betton_color = $_GET[betton_color];
	if(!$one_page)$one_page=10;
	 
 	  $room_strings = trim($_GET[room_strings]);
    $sql_kw=""; 
		
		if($username){
		$list_q="select username from member where 1 and username='".$username."' ";
		$list_r = $PDOLink->prepare($list_q); 
		$list_r->execute();
		$row2 = $list_r->fetch();         
		$get_username=$row2[username]; 
		if($username)$sql_kw.=" and username='".$get_username."'";									
    }
		
		if($room_strings)$sql_kw.=" and room_strings='".$room_strings."'";
    if($cname)$sql_kw.=" and cname='".$cname."'"; 

  /* 頁碼 */
  $sql="select count(*) from member where 1 and del_mark=0 and publicCardName='公用卡' $sql_kw order by TimeUpdated desc";
  $rs=$PDOLink->query($sql);
  $rownum=$rs->fetchcolumn();               
  $pagesize=15;                             
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
   $pageurl.=" ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
  } else {
   $pageurl.="<ul class='pagination'><li><a href=\"?betton_color=primary&room_strings=".$room_strings."&page=1\">".$lang->line("index.home")."</a> | <a href=\"?betton_color=primary&room_strings=".$room_strings."&page=$prepage\">".$lang->line("index.previous_page")."</a> </li></ul> | ";
  }
  
  if ($page==$pagenum || $pagenum==0){     
   $pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
  } else {
   $pageurl.="<ul class='pagination'><li><a href=\"?betton_color=primary&room_strings=".$room_strings."&page=$nextpage\">".$lang->line("index.next_page")."</a> | <a href=\"?betton_color=primary&room_strings=".$room_strings."&page=$pagenum\">".$lang->line("index.last_page")."</a></li> </ul>";
  }
?>
<!-- 教官查詢房號  -->  
<form action="PublicMemberStudent.php?betton_color=primary" method="get">
<section id="main" class="wrapper">
	<!-- <div class="rwd-box"></div><br><br> -->
	
	<h2 style="margin-top: -30px;" align="center">公用卡管理</h2>
	<div class="col-12"><a href="setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a></div>
	
    <!--<div class="container" style="text-align: center;">
          <h1 class="jumbotron-heading">公用卡管理</h1>
		  <p>
            <a href="MemberPowerRecord.php?betton_color=primary" class="btn btn-info my-2"
            	><?php echo $lang->line("index.system-use-condition_state_history"); ?>
            </a> 
            <!-- <a href="MemberPowerRecordALL.php?betton_color=primary" class="btn btn-info my-2">
            	<?php echo $lang->line("index.overall_electricity_statistics_query"); ?>
            </a>  --><!--
            <a href="MemberEZCardRecord.php?betton_color=primary" class="btn btn-info my-2">
            	<?php echo $lang->line("index.stored_value_query"); ?>
            </a> 
            <a href="MemberEZCardRecordALL2.php?betton_color=primary" class="btn btn-info my-2">
            	<?php echo $lang->line("index.overall_stored_value_query"); ?>
            </a> 
          	<a href="MemberStudent.php?betton_color=primary" class="btn btn-info my-2">
          		<?php echo $lang->line("index.balance_list"); ?>
          	</a>
            <a href="PublicMemberStudent.php?betton_color=primary" class="btn btn-<?php echo $betton_color; ?> my-2">
          		公用卡管理專區
          	</a>
          	<a href="member_history.php?betton_color=primary" class="btn btn-info my-2">
          		<?php echo $lang->line("index.accommodation_history"); ?>
          	</a>	 
          </p>
    </div>-->
	<div class="row">
	<?php if($_GET[error] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>Error</strong>Error！！
		</div>
	<?php } elseif ($_GET[error] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>Error</strong>Error！！
		</div>
	<?php } elseif ($_GET[success]) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>Success</strong>成功設置！！
		</div>
	<?php } ?>
	</div>
	<div class="inner">
		<div class="row">
			<!-- <a href="setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i></a><br> -->
				<?php 
					print "
					<div class='col-12'>
						<section class='panel'>
	             			<div class='panel-body'>";
	             			print "
	             			 	<input type='hidden' name='betton_color' value='".$betton_color."'>
	             				<input class='form-control' type='text' placeholder='".$lang->line("index.input_format")."：".$lang->line("index.room_number")."' size=20 name=room_strings value='".$room_strings."'> 
	             			    <input class='form-control btn-success' type='submit' name=search onclick='ch_page(1);' value='".$lang->line("index.confirm_query")."'>
						        <div style='text-align: center;'><a class='form-control btn-success' href='PublicMemberStudent.php?betton_color=primary'>".$lang->line("index.reset")."</a></div>";
						    print "
								<!-- <div class='row'> 
									<div class='col-12' style='text-align: center;'>
										<a class='form-control btn-danger' href='excelpublicmember_save.php'>公用卡".$lang->line("index.export")."</a>
							        </div>
							    </div>  -->";
							print "
	             			</div>
	             		</section>
	             	</div>";
	             ?>
				<div class="col-12">
						<table class="table">
						  <thead class="thead-dark">
						    <tr>
						      <th scope="col"><?php echo $lang->line("index.time_updated"); ?></th> 
						      <th scope="col">公用卡名稱/<?php echo $lang->line("index.room_number"); ?></th>  
						      <th scope="col"><?php echo $lang->line("index.balance"); ?></th> 
						      <th scope="col"><?php echo $lang->line("index.operating"); ?></th> 
						    </tr>
						  </thead>
						  <tbody>
						  <?php 
						      /* 學生儲值餘額 */
								  $sql="select * from member where 1 and del_mark=0 and publicCardName='公用卡' $sql_kw order by TimeUpdated desc limit  " . ($page-1)* $pagesize . ",$pagesize ";
									$rs=$PDOLink->Query($sql);
									$rs->setFetchMode(PDO::FETCH_ASSOC);
									$i=0;
									$j=1;
									$Building = array(1 => 'A棟',2 => 'B棟');
									$UserRoomDataTypeName = array('E' => '一莊','F' => '二莊');
	                while($row=$rs->Fetch()){
   							  $TimeUpdateds = date("Y-m-d H:i:s",strtotime($row[TimeUpdated]));
   							  $room_strings = substr($row[room_strings],1,4);  //去前面一個
   							  $room_type = $row[room_type];
									$berth_number = $row[berth_number];
									$publicCardName = $row[publicCardName]; 
   							  //$del_mark =$row[del_mark]; 

							        print "<tr>
									      <td scope='row'>".$TimeUpdateds."</td>
									      <td>
									      	".$row[cname]."/
									        ".$room_type.$room_strings."<br>
									      </td>
									      <td scope='row'>".PayValueZero($row[balance])." / NTD</td>
									      <td>
												<a href='member_show.php?id_card=".$row[id_card]."&id=".$row[id]."&act=公用卡' class='btn btn-primary'><i class='fas fa-eye'></i></a>
												<a href='member_edit.php?id=".$row[id]."&act=".$publicCardName."' class='btn btn-primary'><i class='fas fa-pencil-alt'></i></a> ";
						    ?>
												<?php //if($row[user_class] != '共用卡'){ ?>
													<!-- <a onclick="return confirm('確認提示，您確定要離開嗎?\n(離開後該筆資料將移除)');"  href='member_home_card_upd.php?id=<?php echo $row[id]; ?>&get_act=del' class='btn btn-danger' ><i class='fas fa-trash'></i></a> -->
												<?php //} ?>
												<?php //if($_SESSION['admin_user']['data_type'] == '空調'){ ?>
													<a onclick="return confirm('確認提示，您確定要將密碼恢復成預設值嗎?\n');" href='member_home_card_upd.php?id=<?php echo $row[id]; ?>&get_act=default' class='btn btn-warning'><i class='fas fa-key'></i></a>   
												<?php //} ?>

							<?php	print "</td></tr>";
							$j++;
							$i++;	    
							}
						     
						    ?>
						  </tbody>
						</table>
				</div>
		</div>
		<?php  
	        Echo $pageurl;
	        Echo "".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."
	        ";
		?> 
		<!-- 原頁碼 -->
		
		<?php if($_SESSION[admin_user][id] == 'andy'){ ?>
			<br><br><a href="dump_db.php?act=member">SQL Member Download</a>
		<?php } ?>
	</div>

</section>
<input type=hidden name=act>
<input type=hidden name=sn>
<input type=hidden name=edit_sn>
</from>
<script>
//回上一頁
function backs()
{
	history.go(-1);
}
</script>
<?php iframe('');?>
<?php include('footer_layout.php'); ?>