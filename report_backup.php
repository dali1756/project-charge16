<?php 

	include('header_layout_report.php');
	include('nav.php');
	include('chk_log_in.php');
	
	$pagesize  = 10;
	
	$mc_data;
	
	$sql_kw    = "";
	
 	$kw_start  = $_GET['kw_start'];
 	$kw_end    = $_GET['kw_end'];
	$sel_build = $_GET['sel_build'];
	$sel_level = $_GET['sel_level'];
	$sel_dev   = $_GET['sel_dev'];

	// $sql = "SELECT * FROM `usage_history`";
	// $rs = $PDOLink->prepare($sql);
	// $rs->execute();
	// $rs_data = $rs->fetchAll();
	
	// foreach($rs_data as $v) {
		// $mc_data[$v['mac']] = $v;
	// }

	// 查詢條件 -- 20200205
	$b_opt; $l_opt; $p_opt;
	
	$def_opt  = "請選擇";
	// $wash_str = "洗衣機";
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

	// $compare = ($sel_dev == $wash_str) ? "<=" : ">";
	
	if($sel_dev != '') $sql_kw .= " AND i.seat_id = '{$sel_dev}' ";
	
	if($kw_start) {
		$s_date = date('Y-m-d H:i:s', strtotime($kw_start));
		$sql_kw.= " AND start_time >= '{$s_date}' ";
	}
	
	if($kw_end) {
		$e_date = date('Y-m-d H:i:s', strtotime($kw_end.'+1 day'));
		$sql_kw.= " AND start_time < '{$e_date}' ";
	}

	/* 頁碼 */
	$sql = "SELECT count(*) FROM usage_history i WHERE 1 ".$sql_kw;
// echo $sql;
	$rs  = $PDOLink->query($sql);
	$rownum = $rs->fetchcolumn();               
	
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
		$pageurl.="<a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=1\">".$lang->line("index.home")."</a> | 
				   <a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=$prepage\">".$lang->line("index.previous_page")."</a> | ";
	}

	if($page==$pagenum || $pagenum==0) {     
		$pageurl.=" ".$lang->line("index.next_page")." | ".$lang->line("index.last_page")." ";
	} else {
		$pageurl.="<a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=$nextpage\">".$lang->line("index.next_page")."</a> | 
				   <a href=\"?sel_build={$sel_build}&sel_level={$sel_level}&sel_dev={$sel_dev}&kw_start={$kw_start}&kw_end={$kw_end}&page=$pagenum\">".$lang->line("index.last_page")."</a>";
	}
?>


<section id="main" class="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 mb-0 ">報表匯出</h2>
          </div>
          <div class="col-12 back-btn">
            <a href="member.php">
              <i class="fas fa-chevron-circle-left fa-3x"></i>
              <label class="previous"></label>
            </a>
          </div>

          <!-- Content Row -->
          <!--標籤 INDEX用-->
        <div class="container-fluid">
          <div class="row">
          <!-- Content Row -->
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
                        <a class="nav-link card border-left-primary shadow active card-border" data-toggle="tab" href="#day" role="tab" aria-selected="false">
                          <!--<span class="hidden-sm-up"><i class="ion-person"></i></span>-->
                          <span class="h5 mb-0 font-weight-bold ">日報表查詢</span>
                        </a>    
                      </li>

				              <li class="nav-item col-xl-4 col-md-4">
                        <a class="nav-link card border-left-primary shadow card-border" data-toggle="tab" href="#month" role="tab" aria-selected="false">
                          <!--<span class="hidden-sm-up"><i class="ion-person"></i></span> -->
                          <span class="h5 mb-0 font-weight-bold ">月報表查詢</span>
                        </a>           
                      </li>

				              <li class="nav-item col-xl-4 col-md-4 "> 
                        <a class="nav-link card border-left-primary shadow card-border" data-toggle="tab" href="#year" role="tab" aria-selected="false">
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
                              <div class="tab-pane active show" id="day" role="tabpanel">
                                <form class="form-center search-mar"  action="admin_upd.php" method="post">

                                  <!--開始/結束時間-->
                                  <div class="row">
                                    <div class="form-group col-lg-5">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">開始時間</label>
                                    <input class="form-control form-control2  " type="date" placeholder="開始時間：yyyy-mm-dd" size="20" name="kw_start" value="">
                                    
                                    </div>
                                    <div class="form-group col-lg-2 d-none d-sm-inline-block">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font"></label>
                                     <p class=" label-font ">～</p>
                                    </div>

                                  <div class="form-group col-lg-5">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">結束時間</label>
                                    <input class="form-control   form-control2 " type="date" placeholder="結束時間：yyyy-mm-dd" size="20" name="kw_end" value="">
                                  </div>
                                </div>

                                
                                  <!--BTN 查詢-->
                                  <div class="text-center btn-mar ">
                                    <button type="button" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                
                                  
                                </form>
                                <!--BTN 匯出-->
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a">
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
                                      <th scope="col" >付款金額</th>
                                      <th scope="col">退費金額</th> 
                                      <th scope="col">小計</th>
                                      <th scope="col">備註</th>
                                    </tr>
                                  </thead>
                                  <tbody class="text-black">
                                   <tr>
                                          <th scope="row">1</th>
                                          <td>2020-02-21 10:32:13</td> 
                                          <td>1000</td>
                                          <td>100</td>
                                          <td>900</td>
                                          <td></td>
  
                                  </tbody>
                                </table>

                              </div>


                              <!-- 日期跳頁 上下頁-->

                              <div class="row ">

                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
                                    <ul class="pagination text-left col-lg-8">
                                      <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a href="#"
                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                      </li>
                                      <li class="paginate_button page-item active"><a href="#" aria-controls="dataTable" data-dt-idx="1"
                                        tabindex="0" class="page-link">1</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="2"
                                        tabindex="0" class="page-link">2</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="3"
                                        tabindex="0" class="page-link">3</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="4"
                                        tabindex="0" class="page-link">4</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="5"
                                        tabindex="0" class="page-link">5</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="6"
                                        tabindex="0" class="page-link">6</a>
                                      </li>
                                      <li class="paginate_button page-item next" id="dataTable_next"><a href="#" aria-controls="dataTable"
                                        data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                      </li>
                                    </ul>

                                    
                                
                                  </div>
                                  
                                </div>
                              </div>

                              </div>
                              <!--月份-->
                              <div class="tab-pane pad" id="month" role="tabpanel">
                                <form class="form-center search-mar"  action="admin_upd.php" method="post">

                                  <!--月份區間-->
                                  <div class="row">
                                    <!--開始月份--->
                                    <span class="h5 span-mar mb-0 font-weight-bold col-lg-12">開始月份</span>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_level" class="form-control  form-control2 ">
                                        <option value="請選擇">請選擇</option>
                                        <option value="2020">2020</option>
                                        <!--<option value="2021">2021</option> 下一年新增-->
                                      </select>
                                    </div>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_level" class="form-control  form-control2 ">
                                        <option value="請選擇">請選擇</option>
                                        <option value="1月">1月</option>
                                        <option value="2月">2月</option>
                                        <option value="3月">3月</option>
                                        <option value="4月">4月</option>
                                        <option value="5月">5月</option>
                                        <option value="6月">6月</option>
                                        <option value="7月">7月</option>
                                        <option value="8月">8月</option>
                                        <option value="9月">9月</option>
                                        <option value="10月">10月</option>
                                        <option value="11月">11月</option>
                                        <option value="12月">12月</option>
                                        <!--<option value="2021">2021</option> 下一年新增-->
                                      </select>
                                    </div>

                                    <!--結束月份--->
                                    <span class="h5 span-mar mb-0 font-weight-bold col-lg-12">結束月份</span>
                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                      <select name="sel_level" class="form-control  form-control2 ">
                                        <option value="請選擇">請選擇</option>
                                        <option value="2020">2020</option>
                                        <!--<option value="2021">2021</option> 下一年新增-->
                                      </select>
                                    </div>

                                    <div class="form-group  col-lg-6">
                                      <label for="exampleFormControlInput1" class="col-lg-12 label-font">月份</label>
                                      <select name="sel_level" class="form-control  form-control2 ">
                                        <option value="請選擇">請選擇</option>
                                        <option value="1月">1月</option>
                                        <option value="2月">2月</option>
                                        <option value="3月">3月</option>
                                        <option value="4月">4月</option>
                                        <option value="5月">5月</option>
                                        <option value="6月">6月</option>
                                        <option value="7月">7月</option>
                                        <option value="8月">8月</option>
                                        <option value="9月">9月</option>
                                        <option value="10月">10月</option>
                                        <option value="11月">11月</option>
                                        <option value="12月">12月</option>
                                        <!--<option value="2021">2021</option> 下一年新增-->
                                      </select>
                                    </div>
                                  </div>

                                
                                  <!--BTN 查詢-->
                                  <div class="text-center btn-mar ">
                                    <button type="button" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                 
                                  
                                </form>
                                <!--BTN 匯出-->
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a">
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
                                        <th scope="col" >付款金額</th>
                                        <th scope="col">退費金額</th> 
                                        <th scope="col">小計</th>
                                        <th scope="col">備註</th>
                                      </tr>
                                    </thead>
                                    <tbody class="text-black">
                                     <tr>
                                            <th scope="row">1</th>
                                            <td>2020-02-01</td> 
                                            <td>1000</td>
                                            <td>100</td>
                                            <td>900</td>
                                            <td></td>
  
                                      </tr> 
                                        	  
                                    </tbody>
                                  </table>
  
                                </div>
  

                              <!-- 月跳頁 上下頁-->

                              <div class="row ">
 
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
                                    <ul class="pagination text-left col-lg-8">
                                      <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a href="#"
                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                      </li>
                                      <li class="paginate_button page-item active"><a href="#" aria-controls="dataTable" data-dt-idx="1"
                                        tabindex="0" class="page-link">1</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="2"
                                        tabindex="0" class="page-link">2</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="3"
                                        tabindex="0" class="page-link">3</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="4"
                                        tabindex="0" class="page-link">4</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="5"
                                        tabindex="0" class="page-link">5</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="6"
                                        tabindex="0" class="page-link">6</a>
                                      </li>
                                      <li class="paginate_button page-item next" id="dataTable_next"><a href="#" aria-controls="dataTable"
                                        data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                      </li>
                                    </ul>

                                
                                  </div>
                                </div>
                              </div>

                              </div>

                              <!--年度-->
                              <div class="tab-pane pad" id="year" role="tabpanel">
                                <form class="form-center search-mar"  action="admin_upd.php" method="post">
                                  <div class="row">
                                    <!--年度-->
                                    <div class="form-group  col-lg-12">
                                    <label for="exampleFormControlInput1" class="col-lg-12 label-font">年份</label>
                                    <select name="sel_level" class="form-control  form-control2 ">
                                      <option value="請選擇">請選擇</option>
                                      <option value="2020">2020</option>
                                      <!--<option value="2021">2021</option> 下一年新增-->
                                    </select>
                                    </div>

                                  </div>
                                
                                  <!--BTN 查詢-->
                                  <div class="text-center btn-mar ">
                                    <button type="button" class=" btn btn-lg shadow-sm col-lg-3 form-a">
                                      <i class="fas fa-search fa-sm "></i>查詢
                                  </div>                                 
                                
                                </form>
                                <!--BTN 匯出-->
                                <div class="text-right btn-mar">
                                  <button type="button" class="btn btn-lg shadow-sm form-a">
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
                                        <th scope="col" >付款金額</th>
                                        <th scope="col">退費金額</th> 
                                        <th scope="col">小計</th>
                                        <th scope="col">備註</th>
                                      </tr>
                                    </thead>
                                    <tbody class="text-black">
                                     <tr>
                                            <th scope="row">1</th>
                                            <td>2020</td> 
                                            <td>1000</td>
                                            <td>100</td>
                                            <td>900</td>
                                            <td></td>
  
 	  
                                    </tbody>
                                  </table>
  
                                </div>
  


                              <!-- 年跳頁 上下頁-->

                              <div class="row ">
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
                                    <ul class="pagination text-left col-lg-8">
                                      <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a href="#"
                                        aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                      </li>
                                      <li class="paginate_button page-item active"><a href="#" aria-controls="dataTable" data-dt-idx="1"
                                        tabindex="0" class="page-link">1</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="2"
                                        tabindex="0" class="page-link">2</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="3"
                                        tabindex="0" class="page-link">3</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="4"
                                        tabindex="0" class="page-link">4</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="5"
                                        tabindex="0" class="page-link">5</a>
                                      </li>
                                      <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="6"
                                        tabindex="0" class="page-link">6</a>
                                      </li>
                                      <li class="paginate_button page-item next" id="dataTable_next"><a href="#" aria-controls="dataTable"
                                        data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                      </li>
                                    </ul>

                                  </div>

                                </div>
                              </div>
                              <!-- 跳頁 上下頁END-->
                              </div>

                              </div>


                              <!--TEST END-->

                              
                            </div>
                            <!--查詢選項 END-->

                            
                          </div>
                          <!--切換頁位置 END-->
                        </div>
                      </div>
                    </div>
                    

	          </div><!--container-fluid-->
    	    </div>
	        <!-- 日/月/年分頁 標籤 END -->
          </div>
          </div>
        <!-- 標籤 INDEX用END -->

      </div>
      <!-- End of Main Content -->



        </div>
    <!-- End of Content Wrapper -->
    </div>
  <!-- End of Page Wrapper -->
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

  //回上一頁
  function backs()
  {
    history.go(-1);
  }
  
  function reset_form() {
    location.replace('MemberEZCardRecord.php');
  }
  
  function search() {
    $('#mform').prop('action', 'MemberEZCardRecord.php');
    $('#mform').prop('method', 'get');
    
    $('#mform').submit();
  }
  
  function export2excel() {
    
    $('#mform').prop('action', 'MemberEZCardRecordExcel.php');
    $('#mform').prop('method', 'get');
    
    $('#mform').submit();
    return false;
  }
</script>


  <?php // iframe('');?>
  <?php include('footer_layout.php'); ?>