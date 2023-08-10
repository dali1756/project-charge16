<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include('config/db.php');
	include('chk_log_in.php');
	include('header_layout_report.php');
	include('nav.php');
	$page = 10;   // 每頁顯示多少筆資料
	$currentpage = isset($_GET["page"]) ? (int)$_GET["page"] : 1;   // 當前頁碼
	$off = ($currentpage - 1) * $page;
	$sql_total = "SELECT COUNT(*) FROM log_list";
	$total = $PDOLink->query($sql_total);
	$result_total = $total->fetchColumn();
	$pages = ceil($result_total / $page);   // 總頁數
	$sql = "SELECT li.content, li.add_date, li.id FROM log_list li ORDER BY add_date DESC LIMIT :offset, :page";
	$stmt = $PDOLink->prepare($sql);
	$stmt->bindParam(":offset", $off, PDO::PARAM_INT);
	$stmt->bindParam(":page", $page, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$show_pages = 10;                                   // 最初顯示的頁數和之後每次點擊的頁數
	if ($currentpage % $show_pages == 0) {
		$start_page = $currentpage - $show_pages + 1;   // 頁碼開始
		$end_page = $currentpage;                       // 頁碼結束
	} else {
		$start_page = ((int)($currentpage / $show_pages) * $show_pages) + 1;
		$end_page = $start_page + $show_pages - 1;
	}
	if ($end_page > $pages) {
		$end_page = $pages;
	}
?>
<section id="main" class="wrapper">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 mb-0 ">Log記錄</h2>
          </div>
          <div class="col-12 back-btn">
            <a href="member.php">
              <i class="fas fa-chevron-circle-left fa-3x"></i>
              <label class="previous"></label>
            </a>
          </div>
                              <div class="row  btn-mar">
                                <table class="table container-fluid ">
                                  <thead class="thead-dark ">
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">訊息</th>
                                      <th scope="col">時間</th> 
                                    </tr>
                                  </thead>
                                  <tbody class="text-black">
									<?php
										foreach ($result as $row) {
											echo "<tr>";
											echo "<td>{$row['id']}</td>";
											echo "<td>{$row['content']}</td>";
											echo "<td>{$row['add_date']}</td>";
											echo "</tr>";
										}
									?>
                                  </tbody>
                                </table>
                              </div>
                              <div class="tab-pane pad" id="month" role="tabpanel">
                              <div class="row ">
                                <div class="btn-mar container-fluid">
                                  <div class="dataTables_paginate paging_simple_numbers text-right " id="dataTable_paginate">
									<?php // 上一頁 ?>
								  	<?php if ($currentpage > 1) { ?>
										<a href = "?page=<?php echo $currentpage - 1; ?>">上一頁</a>
									<?php } ?>
									<?php for($i = $start_page; $i <= $end_page; $i++) { ?>
										<a href = "?page=<?php echo $i; ?>" class="<?php echo $i == $currentpage ? '' : ''; ?>"><?php echo $i; ?></a>
									<?php } ?>
									<?php // 下一頁 ?>
									<?php if ($end_page < $pages) { ?>
										<a href = "?page=<?php echo $currentpage + 1; ?>">下一頁</a>
									<?php } ?>
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
</script>
<?php include('footer_layout.php'); ?>