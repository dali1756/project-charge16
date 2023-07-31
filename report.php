<?php 
	include('config/db.php');
	include('chk_log_in.php');
	include('header_layout_report.php');
	include('nav.php');
	
	$pagesize  = 15;
	$opt_start = 2020;
	
	$kw_start  = $_GET['kw_start'];
	$kw_end    = $_GET['kw_end'];
	
	// 給初值 -- 20200330
 	if($kw_start == "") { $kw_start = date('Y-m-d'); }
	if($kw_end   == "") { $kw_end   = date('Y-m-d'); }
	
	if($sel_year_start  == "") { $sel_year_start  = date('Y'); }
	if($sel_year_end    == "") { $sel_year_end    = date('Y'); }
	if($sel_month_start == "") { $sel_month_start = date('m'); }
	if($sel_month_end   == "") { $sel_month_end   = date('m'); }
	
	if($sel_year_all == "") { $sel_year_all = date('Y'); }
	
	$sql_kw    = "";
	
	$get_tab   = $_GET['get_tab'];
	
	if($get_tab == '') {
		$get_tab = 'day';
	}

?>

<section id="main" class="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 mb-0 ">報表匯出</h2>
          </div>
          <div class="col-12 back-btn">
            <a href="member.php">
              <i class="fas fa-chevron-circle-left fa-3x"></i>
              <label class="previous"></label>
            </a>
          </div>
          <!--標籤 INDEX用-->
        <div class="container-fluid">
          <div class="inner">
            <div class="row">
            <!--付款明細查詢-->
            <div class="container-fluid table-mar">
            <!--付款明細查詢 表格內容-->
            <!--日/月/年分頁 標籤-->
	          <!-- tabs -->
    	      <div class="row ">
	            <div class="container-fluid">
                 <!-- Nav tabs 標籤-->
                 <!--加入COL-->
                  <!--<div class="">-->
			              <ul class="nav nav-tabs  nav-border" role="tablist">
				              <li class="nav-item col-xl-4 col-md-4"> 
                        <a class="nav-link card border-left-primary shadow active card-border" data-toggle="tab" id='tab_day' href="#day" role="tab" aria-selected="false">
                          <!--<span class="hidden-sm-up"><i class="ion-person"></i></span>-->
                          <span class="h5 mb-0 font-weight-bold ">日報表查詢</span>
                        </a>    
                      </li>

				              <li class="nav-item col-xl-4 col-md-4">
                        <a class="nav-link card border-left-primary shadow card-border" data-toggle="tab" id='tab_month' href="#month" role="tab" aria-selected="false">
                          <!--<span class="hidden-sm-up"><i class="ion-person"></i></span> -->
                          <span class="h5 mb-0 font-weight-bold ">月報表查詢</span>
                        </a>           
                      </li>

				              <li class="nav-item col-xl-4 col-md-4 "> 
                        <a class="nav-link card border-left-primary shadow card-border" data-toggle="tab" id='tab_year' href="#year" role="tab" aria-selected="false">
                          <!--<span class="hidden-sm-up"><i class="ion-email"></i></span> -->
                          <span class="h5 mb-0 font-weight-bold ">年報表查詢</span>
                        </a>
                      </li>

                    </ul>

                    
                    <!-- Content Row Tab panes 查詢標籤切換頁-->
                    <div class="row ">
                      <!-- Content Column -->
                      <div class="col-lg-12 mb-4">
                        <!-- Approach -->
                        <div class="card shadow mb-4">

                          <!--切換頁位置-->
                          <div class="card-body">
                            <!--查詢選項-->
                            <div class="tab-content tabcontent-border">
                              <!--日期-->
							  <div class="tab-pane show" id="day" role="tabpanel">
								<form id='mform1' class="form-center search-mar" action="report.php?get_tab=day" method="get">
									<input type='hidden' name='get_tab' value='day'>
                                  <!--開始/結束時間-->
                                  <div class="row">
                                    <div class="form-group col-lg-5">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">開始時間</label>
                                    <input class="form-control form-control2  " type="date" placeholder="開始時間：yyyy-mm-dd" size="20" name="kw_start" value="<?php echo $kw_start ?>">
                                    
                                    </div>
                                    <div class="form-group col-lg-2 d-none d-sm-inline-block">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font"></label>
                                     <p class=" label-font ">～</p>
                                    </div>

                                  <div class="form-group col-lg-5">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">結束時間</label>
                                    <input class="form-control   form-control2 " type="date" placeholder="結束時間：yyyy-mm-dd" size="20" name="kw_end" value="<?php echo $kw_end ?>">
                                  </div>
                                </div>

                                
                                  <!--BTN 查詢-->
                                  <div class="text-center btn-mar ">
                                    <button type="submit" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                
                                  
                                </form>
                                <!--BTN 匯出-->
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a" onclick='export2excel1()'>
                                    <i class="fas fa-download fa-sm "></i>匯出 Download
                                  </button>
                                </div>
                                <!--TABLE 日期-->
                              <div class="row  btn-mar">
                                <table class="table container-fluid ">
                                  <thead class="thead-dark ">
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">日期</th>
									  <th scope="col">卡號</th>
                                      <th scope="col">付款金額</th>
                                      <th scope="col">退費金額</th> 
                                      <th scope="col">小計</th>
                                      <th scope="col">備註</th>
                                    </tr>
                                  </thead>
                                  <tbody class="text-black">
<?php
											$j = 0;
											
											if($kw_start) {
												$s_date = date('Y-m-d H:i:s', strtotime($kw_start));
												$sql_kw.= " AND date_time >= '{$s_date}' ";
											}
											
											if($kw_end) {
												$e_date = date('Y-m-d H:i:s', strtotime($kw_end.'+1 day'));
												$sql_kw.= " AND date_time < '{$e_date}' ";
											}											
											
											$sql = "SELECT cardnum, postive, PayValue, date_time as 'day' 
													FROM `icer_pay` i WHERE 1 {$sql_kw} ORDER BY date_time DESC";
													
											$rs = $PDOLink->Query($sql);
											$rs_tmp = $rs->fetchAll();
											
											foreach($rs_tmp as $v) {
												
												$day = $v['day'];												
												$pos = $v['postive'];
												$amt = $v['PayValue'];
												
												$rs_data1[$day]['cardnum'] = $v['cardnum'];
												
												if($pos == '1') {
													$rs_data1[$day]['amt'] += $amt;
												}
												
												if($pos == '0') {
													$rs_data1[$day]['ref'] += $amt;
												}
											}

											if(isset($_GET['page'])) {               
												$page = $_GET['page'];  
											} else {
												$page = 1;                                 
											}
											
											foreach($rs_data1 as $d => $row)
											{
												$j++;
												
												$amt = $row['amt'] == '' ? 0 : $row['amt'];
												$ref = $row['ref'] == '' ? 0 : $row['ref'];
												$sum = $amt - $ref;
												
												if( ($j > (($page-1) * $pagesize)) & ($j <= ($page) * $pagesize) ) 
												{	
													print " <tr>
																<td>{$j}</td>
																<td>{$d}</td> 
																<td>".$row['cardnum']."</td>
																<td>{$amt}</td>
																<td>{$ref}</td>
																<td>{$sum}</td>
																<td></td>
															</tr>";
												}
											}
											
											$rownum = $j;
?>		
                                  </tbody>
                                </table>

                              </div>


                              <!-- 日期跳頁 上下頁-->

                              <div class="row ">

                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
									  <?php
									  
										$pagenum  = (int) ceil($rownum / $pagesize);  
										$prepage  = $page - 1;                        
										$nextpage = $page + 1;                        
										$pageurl  = '';
									
										if($page == 1) {                         
											$pageurl.=" ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
										} else {
											$pageurl.="<a href=\"?cardnum={$cardnum}&sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&get_tab={$get_tab}&page=1\">".$lang->line("index.home")."</a> | 
													   <a href=\"?cardnum={$cardnum}&sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&get_tab={$get_tab}&page=$prepage\">".$lang->line("index.previous_page")."</a> | ";
										}

										if($page==$pagenum || $pagenum==0) {     
											$pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
										} else {
											$pageurl.="<a href=\"?cardnum={$cardnum}&sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&get_tab={$get_tab}&page=$nextpage\">".$lang->line("index.next_page")."</a> | 
													   <a href=\"?cardnum={$cardnum}&sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&get_tab={$get_tab}&page=$pagenum\">".$lang->line("index.last_page")."</a>";
										}											  
									  
										if($rownum > $pagesize) {
											echo $pageurl;
											echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
										}
										
										$rownum = 0; // inital
									  ?>
                                    
                                
                                  </div>
                                  
                                </div>
                              </div>

                              </div>
                              <!--月份-->
<?php

										if($get_tab == 'month') 
										{
											
											$rs_data2;
											
											$sql_kw = "";
											
											$sel_year_start  = $_GET['sel_year_start'];
											$sel_year_end    = $_GET['sel_year_end'];
											$sel_month_start = $_GET['sel_month_start'];
											$sel_month_end   = $_GET['sel_month_end'];
											
											if($sel_year_start != '' & $sel_month_start != '') {
												
												$qry_date = date('Y-m-d H:i:s', strtotime("{$sel_year_start}-{$sel_month_start}-01 00:00:00"));
												
												$sql_kw .= " AND date_time >= '{$qry_date}' ";
												
											} else {

												if($sel_year_start != '') {
													$sql_kw .= " AND YEAR(date_time) >= {$sel_year_start} ";
												} 
												
												if($sel_month_start != '') {
													$sql_kw .= " AND MONTH(date_time) >= {$sel_month_start} ";
												}												
											}
												
											if($sel_year_end != '' & $sel_month_end != '') {
												
												$qry_date = date('Y-m-d H:i:s', strtotime("{$sel_year_end}-{$sel_month_end}-01 00:00:00 +1 month"));
												
												$sql_kw .= " AND date_time < '{$qry_date}' ";
												
											} else {
												if($sel_year_end != '') {
													$sql_kw .= " AND YEAR(date_time) <= {$sel_year_end} ";
												} 
												
												if($sel_month_end != '') {
													$sql_kw .= " AND MONTH(date_time) <= {$sel_month_end} ";
												}												
											}
											
											$sql = "SELECT YEAR(date_time) as 'year', MONTH(date_time) as 'month', 
													DAY(date_time) as 'day', SUM(PayValue) as 'amount', postive 
													FROM `icer_pay` WHERE 1 {$sql_kw} GROUP BY YEAR(date_time), 
													MONTH(date_time), DAY(date_time), postive ORDER BY date_time ";
											$rs  = $PDOLink->Query($sql);
											$rs_tmp = $rs->fetchAll();
											
											foreach($rs_tmp as $v) {
												
												$yy  = $v['year'];
												$mm  = $v['month'];
												$dd  = $v['day'];
												$pos = $v['postive'];
												$amt = $v['amount'];

												if($pos == '1') {
													$rs_data2[$yy][$mm][$dd]['amt'] += $amt;
												}
												
												if($pos == '0') {
													$rs_data2[$yy][$mm][$dd]['ref'] += $amt;
												}
											}
										}
?>
                              <div class="tab-pane pad" id="month" role="tabpanel">
								<form id='mform2' class="form-center search-mar" action="report.php?get_tab=month" method="get">
									<input type='hidden' name='get_tab' value='month'>
                                  <!--月份區間-->
                                  <div class="row">
                                    <!--開始月份--->
                                    <span class="h5 span-mar mb-0 font-weight-bold col-lg-12">開始月份</span>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                      <select name="sel_year_start" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
										<?php
											for($i=date('Y'); $i>=$opt_start; $i--) { 
												echo "<option value='{$i}' ". (($i == $sel_year_start) ? "selected" : "") .">{$i}</option>"; 
											}
										?>
                                        <!--<option value="2021">2021</option> 下一年新增-->
                                      </select>
                                    </div>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_month_start" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
										<?php
											for($i=1; $i<=12; $i++) { 
												echo "<option value='{$i}' ". (($i == $sel_month_start) ? "selected" : "") .">{$i} 月</option>"; 
											} 
										?>
                                      </select>
                                    </div>

                                    <!--結束月份--->
                                    <span class="h5 span-mar mb-0 font-weight-bold col-lg-12">結束月份</span>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                      <select name="sel_year_end" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
										<?php
											for($i=date('Y'); $i>=$opt_start; $i--) { 
												echo "<option value='{$i}' ". (($i == $sel_year_end) ? "selected" : "") .">{$i}</option>"; 
											}
										?>
                                        <!--<option value="2021">2021</option> 下一年新增-->
                                      </select>
                                    </div>

                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_month_end" class="form-control  form-control2 ">
                                        <option value="">請選擇</option>
										<?php
											for($i=1; $i<=12; $i++) { 
												echo "<option value='{$i}' ". (($i == $sel_month_end) ? "selected" : "") .">{$i} 月</option>"; 
											} 
										?>
                                      </select>
                                    </div>
                                  </div>

                                
                                  <!--BTN 查詢-->
                                  <div class="text-center btn-mar ">
                                    <button type="submit" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                 
                                  
                                </form>
                                <!--BTN 匯出-->
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a" onclick='export2excel2()'>
                                    <i class="fas fa-download fa-sm "></i>匯出 Download
                                  </button>
                                </div>
                                <!--TABLE 月份-->
                                <div class="row  btn-mar">
                                  <table class="table container-fluid ">
                                    <thead class="thead-dark ">
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">日期</th>
                                        <th scope="col">付款金額</th>
                                        <th scope="col">退費金額</th> 
                                        <th scope="col">小計</th>
                                        <th scope="col">備註</th>
                                      </tr>
                                    </thead>
                                    <tbody class="text-black">
<?php 
										if($get_tab == 'month') 
										{
											$j = 0;

											if(isset($_GET['page'])) {               
												$page = $_GET['page'];  
											} else {
												$page = 1;                                 
											}
					
											foreach($rs_data2 as $y => $outer)
											{
												foreach($outer as $m => $inner) 
												{
													foreach($inner as $d => $row) 
													{
														$j++;
														
														$amt = $row['amt'] == '' ? 0 : $row['amt'];
														$ref = $row['ref'] == '' ? 0 : $row['ref'];
														$sum = $amt - $ref;
														
														if( ($j > (($page-1) * $pagesize)) & ($j <= ($page) * $pagesize) ) 
														{	
															echo "<tr>
																	<th scope='row'>".$j."</th>
																	<td>{$y}-".str_pad($m,2,"0",STR_PAD_LEFT)."-".str_pad($d,2,"0",STR_PAD_LEFT)."</td>
																	<td>{$amt}</td>
																	<td>{$ref}</td>
																	<td>{$sum}</td>
																	<td></td>
																  </tr>";													
														}
													}
												}
											}
											
											$rownum = $j;
										}
?>  
                                    </tbody>
                                  </table>
  
                                </div>
  

                              <!-- 月跳頁 上下頁-->

                              <div class="row ">
 
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
<?php
									$pagenum  = (int) ceil($rownum / $pagesize);  
									$prepage  = $page - 1;                        
									$nextpage = $page + 1;                        
									$pageurl  = '';

									if($page == 1) {                         
										$pageurl.=" ".$lang->line("index.home")." | ".$lang->line("index.previous_page")." | ";
									} else {
										$pageurl.="<a href=\"?get_tab=month&sel_year_start={$sel_year_start}&sel_month_start={$sel_month_start}&sel_year_end={$sel_year_end}&sel_month_end={$sel_month_end}&page=1\">".$lang->line("index.home")."</a> | 
												   <a href=\"?get_tab=month&sel_year_start={$sel_year_start}&sel_month_start={$sel_month_start}&sel_year_end={$sel_year_end}&sel_month_end={$sel_month_end}&page=$prepage\">".$lang->line("index.previous_page")."</a> | ";
									}

									if($page==$pagenum || $pagenum==0) {     
										$pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
									} else {
										$pageurl.="<a href=\"?get_tab=month&sel_year_start={$sel_year_start}&sel_month_start={$sel_month_start}&sel_year_end={$sel_year_end}&sel_month_end={$sel_month_end}&page=$nextpage\">".$lang->line("index.next_page")."</a> | 
												   <a href=\"?get_tab=month&sel_year_start={$sel_year_start}&sel_month_start={$sel_month_start}&sel_year_end={$sel_year_end}&sel_month_end={$sel_month_end}&page=$pagenum\">".$lang->line("index.last_page")."</a>";
									}
									
									if($rownum > $pagesize) {
										echo $pageurl;
										echo " ".$lang->line("index.current_page")." $page | ".$lang->line("index.a_few_pages")." $pagenum ".$lang->line("index.page")."";
									}
?>
                                  </div>
                                </div>
                              </div>

                              </div>

                              <!--年度-->
<?php
										if($get_tab == 'year') 
										{
											
											$rs_data3;
											
											$sql_kw = "";
											
											$sel_year_all  = $_GET['sel_year_all'];
											
											if($sel_year_all != '') {
												$sql_kw = " AND YEAR(date_time) = '{$sel_year_all}' ";
											}										
											
											$sql = "SELECT YEAR(date_time) as 'year', MONTH(date_time) as 'month', 
													SUM(PayValue) as 'amount', postive 
													FROM `icer_pay` WHERE 1 {$sql_kw} GROUP BY YEAR(date_time), 
													MONTH(date_time), postive ORDER BY date_time";
											$rs  = $PDOLink->Query($sql);
											$rs_tmp = $rs->fetchAll();
											
											foreach($rs_tmp as $v) {
												
												$yy  = $v['year'];
												$mm  = $v['month'];
												$pos = $v['postive'];
												$amt = $v['amount'];
												
												if($pos == '1') {
													$rs_data3[$yy][$mm]['amt'] += $amt;
												}
												
												if($pos == '0') {
													$rs_data3[$yy][$mm]['ref'] += $amt;
												}
											}
										}
?>
                              <div class="tab-pane pad" id="year" role="tabpanel">
								<form id='mform3' class="form-center search-mar" action="report.php?get_tab=year" method="get">
									<input type='hidden' name='get_tab' value='year'>
                                  <div class="row">
                                    <!--年度-->
                                    <div class="form-group  col-lg-12">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                    <select name="sel_year_all" class="form-control  form-control2 ">
                                      <option value="">請選擇</option>
										<?php
											for($i=date('Y'); $i>=$opt_start; $i--) { 
												echo "<option value='{$i}' ". (($i == $sel_year_all) ? "selected" : "") .">{$i}</option>"; 
											}
										?>
                                      <!--<option value="2021">2021</option> 下一年新增-->
                                    </select>
                                    </div>

                                  </div>
                                
                                  <!--BTN 查詢-->
                                  <div class="text-center btn-mar ">
                                    <button type="submit" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                 
                                
                                </form>
                                <!--BTN 匯出-->
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a" onclick='export2excel3()'>
                                    <i class="fas fa-download fa-sm "></i>匯出 Download
                                  </button>
                                </div>

                                <!--TABLE 年份-->
                                <div class="row  btn-mar">
                                  <table class="table container-fluid ">
                                    <thead class="thead-dark ">
                                      <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">月份</th>
                                        <th scope="col">付款金額</th>
                                        <th scope="col">退費金額</th> 
                                        <th scope="col">小計</th>
                                        <th scope="col">備註</th>
                                      </tr>
                                    </thead>
                                    <tbody class="text-black">
                                     <tr>
<?php
											$j = 0;
											
											foreach($rs_data3 as $y => $row)
											{
												foreach($row as $m => $v) 
												{
													$amt = $v['amt'] == '' ? 0 : $v['amt'];
													$ref = $v['ref'] == '' ? 0 : $v['ref'];
													$sum = $amt - $ref;
													
													echo "<tr>
															<th scope='row'>".++$j."</th>
															<td>".str_pad($y,2,"0",STR_PAD_LEFT)."-".str_pad($m,2,"0",STR_PAD_LEFT)."</td>
															<td>{$amt}</td>
															<td>{$ref}</td>
															<td>{$sum}</td>
															<td></td>
														  </tr>";
												}
											}
?>	
                                    </tbody>
                                  </table>  
                                </div>
                              <div class="row ">
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
                                  </div>

                                </div>
                              </div>
                              </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
    	        </div>
            </div>
          </div>
        </div>
      </div>

        </div>
    </div>
</section>
<!--20200323 新增-->
		<!-- Bootstrap core JavaScript-->
		<script src="assets/js/jquery1.min.js"></script>
		<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- Core plugin JavaScript-->
		<script src="assets/js/jquery.easing.min.js"></script>
		<!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
  <!-- Page level plugins -->
  <script src="assets/js/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/js/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
	
	var get_tab = '<?php echo $get_tab ?>';
	
	switch(get_tab) {
		
		case 'day':
		
			$('#day').addClass('active');
			$('#month').removeClass('active');
			$('#year').removeClass('active');
			
			$('#tab_day').click();
		
			break;
		case 'month':

			$('#tab_month').click();
		
			break;
		case 'year':

			$('#tab_year').click();
			
			break;
		default:

			$('#tab_day').click();
			
			break;
	}
	
});

function export2excel1() {
	
	$('#mform1').prop('action', 'excelmember_report_save1.php');
	$('#mform1').prop('method', 'get');
	
	$('#mform1').submit();
	
	$('#mform1').prop('action', 'report.php');
	return false;
}
function export2excel2() {
	
	$('#mform2').prop('action', 'excelmember_report_save2.php');
	$('#mform2').prop('method', 'get');
	
	$('#mform2').submit();
	
	$('#mform2').prop('action', 'report.php');
	return false;
}
function export2excel3() {
	
	$('#mform3').prop('action', 'excelmember_report_save3.php');
	$('#mform3').prop('method', 'get');
	
	$('#mform3').submit();
	
	$('#mform3').prop('action', 'report.php');
	return false;
}

// 檢查用戶權限
setInterval(function() {
        fetch("/check_status.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ id: "<?php echo $_SESSION["admin_user"]["id"]; ?>" })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "X") {
                window.location.href = "index.php";
            }
        });
    }, 1000);

</script>
  <?php // iframe('');?>
  <?php include('footer_layout.php'); ?>