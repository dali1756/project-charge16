<?php include_once('config/db.php'); ?>
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
	   <link rel="stylesheet" href="assets/css/ball_public.css">
	   <link rel="stylesheet" href="assets/css/userroom_meter_list.css">

	   <!-- school css & RWD link -->
	   <link rel="stylesheet" href="assets/css/main.css" />
	
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
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	   <!-- fontawesome free online link-->
	   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	</head>
<body>