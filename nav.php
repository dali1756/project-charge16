<!-- nav.php -->

	<header id='header'>
		<nav class='left'>
			<!-- <a href='#menu'><span>Menu</span></a> -->
			<a href='#menu'><span class='glyphicon glyphicon-option-horizontal' style='font-size:42px'></span></a>
		</nav>
		<!-- LOGO -->
		<a style='color: #fff;' href='index.php' class='logo'>
			<!-- <img class='school_image' src='assets/image/logo.png'>  -->
			<!-- ".$lang->line("index.scllo_title")." -->
		</a>
<?php

	print " <nav class='right'>";
	        if ($_SESSION['user']['id']){
		       print "	
				<a href='logout.php?data_type=member' class='button alt'>
					".$lang->line("index.logout")."</a>";
	        } elseif ($_SESSION['admin_user']['id']) {
	        	print "
				<a href='logout.php?data_type=admin' class='button alt'>
					".$lang->line("index.logout")."</a>";
	        } else {
		       print "
				<!-- <a href='login.php' class='button alt'>".$lang->line("index.login")."</a> -->
				<!-- <a href='admin_login.php' class='button alt'>".$lang->line("index.admin_login")."</a> -->
				<a href='#' onclick=\"$('#identity').toggle()\" class='button alt'>".$lang->line("index.admin_login")."</a>
				";
			}
	print "	</nav>
	    <!-- <nav class='left'>
			<a style='margin: 0px 55px;' href='logout.php?data_type=member' class='button alt'>
				".$lang->line("index.logout")."
			</a>
		<nav> -->
	</header>
	<nav id='menu'> 
		<a href='#menu'><span class='glyphicon glyphicon-option-horizontal' style='font-size:42px; margin-top:-30px; color: #066'></span></a>
		<ul class='links'>";
   	    	print "
	   	    	<li><a href='index.php'><span class='fas fa-home'></span>&nbsp;".$lang->line("index.home")."</a></li>
				<!-- <li><a href='content_us.php'><span class='glyphicon glyphicon-question-sign'></span>&nbsp;".$lang->line("index.customer_service")."</a></li> -->
				<!-- <li><a href='illumination_member.php'>".$lang->line("index.outside_courts_inquiry_system")."</a></li>
				<li><a href='about.php'>".$lang->line("index.intelligent_school_introduction")."</a></li> -->";

			 // 管理權限 
			if(isset($_SESSION['admin_user']['id'])) 
			{
				echo "<li><a href='member.php'><span class='glyphicon glyphicon-cog fa fa-cog'></span>&nbsp;".$lang->line("index.instructor_center")."</a></li>";

				// if($_SESSION['admin_user']['id'] == 'aoadmin') {
					// print "<li><a href='system_administration.php'>最高系統管理</a></li>";
				// } else {
					
					// $sn  = $_SESSION['admin_user']['sn'];
					// $sql = 'SELECT * FROM menu_access WHERE `sn` = :sn';
					// $sth = $PDOLink->prepare($sql);
					// $sth->execute(array('sn' => $sn));
					// $result = $sth->fetch();
					
					// $access = $result['access'];
						
					// if($access != '') {
						// $sql = "SELECT * FROM `menu_list` WHERE `id` in ({$access})";
						
						// $sth = $PDOLink->prepare($sql);
						// $sth->execute(array());
						// $result = $sth->fetchAll();
						
						// foreach($result as $v) {
							// print "<li><a href=\"{$v['page']}\">".$lang->line("{$v['item_name']}")."</a></li>";	
						// }
					// }
				// }

				// print "<br>
						// <li><b style='color:#000000'>Language：</b>
							// <select onChange='location = this.options[this.selectedIndex].value;' style='color: #000;' name='' class=''>
								// <option value='#'>".$lang->line("index.language_select")."</option>
								// <option value='/ndhu/index.php?lang=zh-TW'>".$lang->line("index.chinese")."</option>
								// <option value='/ndhu/index.php?lang=en-us'>".$lang->line("index.english")."</option>
							// </select>
						// </li>";
				
			}	

				print "</ul>";
				print "<ul class='actions vertical'>";
	
	        if ($_SESSION['user']['id']){ 
		       print "
				".$_SESSION['user']['cname']." 登入中
				<a style='width: 200px;' href='logout.php?data_type=member' class='button alt'>登出</a>";
			} elseif ($_SESSION['admin_user']['id']) {
		       print "
				管理員 登入中
				<a style='width: 200px;' href='logout.php?data_type=admin' class='button alt'>登出</a>";						
	        } else { 
		       print "
				<!-- <li><a style='color: #000;' href='login.php' class='button alt'>".$lang->line("index.login")."</a></li> -->
				<li><a style='color: #000;' href='admin_login.php' class='button alt'>".$lang->line("index.admin_login")."</a></li>";
			}

	print " </ul>
	</nav>";
?>