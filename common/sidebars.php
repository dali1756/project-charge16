<!-- nav.php -->
<?php 
  print "
	<header id='header'>
		<nav class='left'>
			<a href='#menu'><span>Menu</span></a>
		</nav>
		<!-- LOGO -->
		<a style='color: #fff;' href='index.php' class='logo'>
			
			<img class='school_image' src='images/ndhu_logo.png'>  
			<!-- ".$lang->line("index.scllo_title")." -->
		</a> 
		<nav class='right'>";
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
				<a href='login.php' class='button alt'>學生".$lang->line("index.login")."</a>
				<a href='admin_login.php' class='button alt'>管理員登入</a>";
			}
	print "	</nav>
	    <!-- <nav class='left'>
			<a style='margin: 0px 55px;' href='logout.php?data_type=member' class='button alt'>
				".$lang->line("index.logout")."
			</a>
		<nav> -->
	</header>
	<nav id='menu' style='opacity: 0.8;'>
		<ul class='links'>";
   	    	print "
	   	    	<li><a href='index.php'>".$lang->line("index.home")."</a></li>  
				<li><a href='content_us.php'>".$lang->line("index.customer_service")."</a></li> 
				<!-- <li><a href='illumination_member.php'>東華校園球場</a></li> -->   
				<!-- <li><a href='about.php'>".$lang->line("index.intelligent_school_introduction")."</a></li>-->";

			/* 學生權限 */
	        if ($_SESSION['user']['id']){

		       print "
					<li><a href='member.php'>".$lang->line("index.student_center")."</a></li>
					<!-- <li><a href='content_us.php'>".$lang->line("index.customer_service")."</a></li>--> ";

			} 

			/* 管理權限 */
			switch ($_SESSION['admin_user']['id']) {

				/* 超級管理員 */ 
				case 'andy':
		        print "
					<li><a href='member.php'>".$lang->line("index.instructor_center")."</a></li> 
					<li><a href='admin_users.php'>".$lang->line("index.student_information_management")."</a></li>
				    <!-- <li><a href='content_us.php'>客服中心</a></li> -->";
					break;
				
				case 'admin1':
		        print "
					<li><a href='member.php'>".$lang->line("index.instructor_center")."</a></li> 
					<li><a href='admin_users.php'>".$lang->line("index.student_information_management")."</a></li> 
					<!-- <li><a href='content_us.php'>客服中心</a></li> -->";
					break;

				case 'admin2':
		        print "
					<li><a href='member.php'>".$lang->line("index.instructor_center")."</a></li>
					<li><a href='admin_users.php'>".$lang->line("index.student_information_management")."</a></li>
					<!-- <li><a href='content_us.php'>客服中心</a></li> -->";
					break;

				case 'admin3':
		        print "
					<li><a href='member.php'>".$lang->line("index.instructor_center")."</a></li>
					<li><a href='admin_users.php'>".$lang->line("index.student_information_management")."</a></li> 
					<!-- <li><a href='content_us.php'>客服中心</a></li> -->";
					break;		

				/* 通用管理員 */
				case 'aoadmin':
		        print "
					<li><a href='member.php'>".$lang->line("index.instructor_center")."</a></li>
					<li><a href='admin_users.php'>".$lang->line("index.student_information_management")."</a></li> 
					<!-- <li><a href='content_us.php'>客服中心</a></li> -->";
					break;									
					
				default:
					# code...
					break;
			}

		       //print "
					// <li><a href='member.php'>學生中心</a></li>


			print "<br>
					<li>Language：
						<select onChange='location = this.options[this.selectedIndex].value;' style='color: #000;' name='' class=''>
							<option value='#'>".$lang->line("index.language_select")."</option>
							<option value='/ndhu/index.php?lang=zh-TW'>中文</option>
							<option value='/ndhu/index.php?lang=en-us'>English</option>
						</select>
					</li>";

	print "	</ul> 
		<ul class='actions vertical'>";
	        if ($_SESSION['user']['id']){ 
		       print "
				".$_SESSION['user']['cname']." 登入中
				<a style='width: 200px;' href='logout.php?data_type=member' class='button alt'>登出</a>";
			} elseif ($_SESSION['admin_user']['id']) {
		       print "
				教官 登入中
				<a style='width: 200px;' href='logout.php?data_type=admin' class='button alt'>登出</a>";						
	        } else { 
		       print "
				<li><a href='login.php' class='button alt'>Login</a></li>
				<!-- <li><a href='admin_login.php' class='button alt'>admin_login</a></li> --> ";
			}

	print " </ul>
	</nav>
  ";
?>