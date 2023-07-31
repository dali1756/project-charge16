<?php 

	include_once('config/db.php');

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

	   <!-- school css & RWD link -->
	   <link rel="stylesheet" href="assets/css/main.css" />
	   <link rel="stylesheet" href="assets/css/school_main.css" />
        
	   <!-- bootstrap online link -->
	   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	
	  <!-- jQuery loading 效果  -->
	  <link href="http://yandex.st/highlightjs/8.0/styles/default.min.css" rel="stylesheet">
	  <link href="loading.css" rel="stylesheet">
	  <link href="demo.css" rel="stylesheet">
	  <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.4.2/randomColor.min.js"></script>
	  <script src="loading.js"></script>

	   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous">
	   </script>

	   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous">
	   </script>

	   <!-- fontawesome free online link-->
	   <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
	   <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
	</head>
<body>
<!-- <section id="main" class="wrapper"> -->

