<?php 
include('header_layout.php');
include('nav.php');
include('chk_log_in.php');
$sql = "SELECT * FROM admin";
$stmt = $PDOLink->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$admin = array("99" => array(), "1" => array(), "2" => array(), "3" => array());
foreach ($result as $results) {
	$key = ($results["data_type"] >= 1 && $results["data_type"] <= 3) ? $results["data_type"] : "99";
	$admin[$key][] = $results["id"];
}
$langEnglishValue = $lang->line("index.if_if");
function parking($link, $imageSrc, $title) {
    ?>
    <div class="col-lg-6 col-6 p-4 text-center"> 
        <a class="my_product" href="<?php echo $link; ?>">
            <img class="d-block mb-3 mx-auto" src="<?php echo $imageSrc; ?>">
            <h4><?php echo $title; ?></h4>
        </a> 
        <p class="text-muted"><?php echo $title; ?></p>
    </div>
    <?php
}
if($admin_id) { ?>
<section id="main" class="wrapper">
	<h2 style="margin-top: -30px;" align="center">時段設定</h2>
	<div class="col-12"><a href="parking_setup.php"><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></a></div>
	<div class="inner">
		<div class="row">
		<?php 
            if (in_array($_SESSION["admin_user"]["id"], $admin['99'])) {
				echo '<div class="col-12">&nbsp;</div>';
                parking('schedule_peak.php', 'images/09離峰時段.png', '離峰時段設定');
                parking('schedule_offpeak.php', 'images/07尖峰時段.png', '一般時段設定');
            } else if (in_array($_SESSION["admin_user"]["id"], $admin['1']) || in_array($_SESSION["admin_user"]["id"], $admin['2']) || in_array($_SESSION["admin_user"]["id"], $admin['3'])) {
                parking('schedule_offpeak.php', 'images/07尖峰時段.png', '一般時段設定');
            }
            ?>
		</div>
	</div>
</section>
<?php } ?>
<?php include('footer_layout.php'); ?>      