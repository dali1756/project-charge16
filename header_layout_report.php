<?php 
	define('ROOT_PATH', __DIR__);
	require_once(ROOT_PATH . "/libraries/Language.php");
	$lang = new Language();
	$lang->load("index");
?>
<!DOCTYPE HTML>    
<html>
	<head>  
	   <title><?php echo $web_title; ?></title>
	   <meta charset="utf-8" />
	   <meta name="viewport" content="width=device-width, initial-scale=1" />
	   <link rel="Shortcut icon" type="image/x-icon" href="images/website_icon.png" />
	   <!-- school css UI更新 link -->
	   <link rel="stylesheet" href="assets/css/userroom_meter_list.css">
	   <!-- school css & RWD link -->
	   <link rel="stylesheet" href="assets/css/main.css" />
	   <!-- bootstrap online link -->
	   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	  <!-- jQuery loading 效果  -->
	  <link href="http://yandex.st/highlightjs/8.0/styles/default.min.css" rel="stylesheet">
	  <link href="loading.css" rel="stylesheet">
	  <link href="demo.css" rel="stylesheet">
	  <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.4.2/randomColor.min.js"></script>
	  <script src="loading.js"></script>
	   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous">
	   </script>
		<!-- bootstrap cdn update -- 20200224 -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<!--20200323新增CSS jQuery-->
    	<!-- fontawesome free online link-->
    	<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
	  	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	  	<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
	  	<link href="assets/css/style.css" rel="stylesheet">
	  	<link href="assets/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	</head>
<body>