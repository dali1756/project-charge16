<?php 
	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');
	$pagesize = 10;
	$sql_kw = "";
	$kw = $_GET[kw];
	$sel_dev   = $_GET['sel_dev'];
	// 查詢條件 -- 20200205
	$b_opt; $l_opt; $p_opt;
	$def_opt  = "請選擇";
	$s_option = "<option value='%s' %s>%s</option>";
	$sql = "SELECT * FROM `seat`";
	$rs  = $PDOLink->query($sql);
	$tmp = $rs->fetchAll();
	$proc[''] = $def_opt;
	foreach($tmp as $v) {
		$proc[$v['id']] = $v['number'];
	}
	foreach($proc as $k => $v) {
		$tmp    = ($sel_dev == $k) ? 'selected' : '';
		$p_opt .= sprintf($s_option, $k, $tmp, $v);
	}
	if($sel_dev != '') {
		$sql_kw = " AND s.id = '{$sel_dev}' ";
	}
	/* 頁碼 */
	$sql="SELECT count(*) from seat s WHERE 1".$sql_kw;
	$rs=$PDOLink->query($sql);
	$rownum=$rs->fetchcolumn();
	if(isset($_GET['page'])) {
	   $page=$_GET['page'];  
	} else {
	   $page=1;                                 
	}
	$pagenum=(int)ceil($rownum / $pagesize);  
	$prepage =$page-1;                        
	$nextpage=$page+1;                        
	$pageurl='';
	if($page == 1) {
		$pageurl.=" ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
	} else {
		$pageurl.="<a href=\"?sel_dev={$sel_dev}&page=1\">".$lang->line("index.home")."</a> | 
				   <a href=\"?sel_dev={$sel_dev}&page=$prepage\">".$lang->line("index.previous_page")."</a> | ";
	}
	if($page==$pagenum || $pagenum==0) {
		$pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
	} else {
		$pageurl.="<a href=\"?sel_dev={$sel_dev}&page=$nextpage\">".$lang->line("index.next_page")."</a> | 
				   <a href=\"?sel_dev={$sel_dev}&page=$pagenum\">".$lang->line("index.last_page")."</a>";
	}
?>
<style>
.div_block {
	border: solid 3px #666;
}
.div_col {
	col-12;
}
</style>
<section id="main" class="wrapper">
	<h2 style="margin-top: -30px;" align="center">模式設定</h2>
	<div class="col-12"><a href="parking_setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	<form id='mform' >							
		<div class="inner">
			<div class="row">
					<div class='col-12'>
						<section class='panel'>
							<div class='panel-body'>
								<table class='table1 mb-3' border='0'>
									<tr>
										<td align='right' width='10%'>車位：</td>
										<td><select name="sel_dev">  <?php echo $p_opt ?></select></td>
									</tr>
								</table>
								
								<button type='button' class='form-control btn-primary' onclick='search()'><?php echo $lang->line("index.confirm_query") ?></button>
								<button type='button' class='form-control btn-warning' onclick='reset_form()'><?php echo $lang->line("index.reset") ?></button>
							</div> 
						</section>
					</div>

					<div class="col-12">
						<!-- 付款 table -->
							<table class="table">
							<thead class="thead-dark">
								<tr>
								<th scope="col">#</th>
								<th scope="col">車號</th>
								<th scope="col">預設模式</th>
								<th scope="col">預設費率</th>
								<th scope="col">預設預付</th>
								<th scope="col">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$j=0;
								$ezcard_record_sql = "
									SELECT *, s.id as 'sid' FROM `seat` s WHERE 1 {$sql_kw} ORDER BY s.id LIMIT " . ($page-1) * $pagesize .", ".$pagesize;
								$rs = $PDOLink->Query($ezcard_record_sql);
								$rs->setFetchMode(PDO::FETCH_ASSOC);
								while($row = $rs->Fetch()) {
									print " <tr>
												<th scope='row'>".++$j."</th>
												<td>".$row['number']."</td> 
												<td>".get_mode($row['mode'])."</td>
												<td>".$row['rate']."</td>
												<td>".$row['prepaid']."</td>
												<td width='10%' nowrap>
													<a onclick='set_mode(".$row['sid'].")' href='#'>模式設定</a>
												</td>
											</tr>";
								}
							?>
							</tbody>
							</table>
						<!-- End 付款 table -->
					</div>
			</div>
				<?php 
					if($rownum > $pagesize){   
						echo $pageurl;
						echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
					}
				?> 
		</div>
	</form>
</section>
<script>
function reset_form() {
	location.replace('RoomList3.php');
}
function search() {
	$('#mform').prop('action', 'RoomList3.php');
	$('#mform').prop('method', 'get');
	$('#mform').submit();
}
function set_mode(sid) {
	location.replace('setmode.php?sid=' + sid);
}
</script>
<?php include('footer_layout.php'); ?>