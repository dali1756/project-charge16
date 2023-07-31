<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<?php 
    if(!$one_page)$one_page=10;
    $room_numbers_kw = $_GET[room_numbers_kw];
    $sql_kw=""; 
    if ($kw):
    	$sql_kw.=" and (start_date like '%".$kw."%') ";
    elseif ($room_numbers_kw):
    	$sql_kw.=" and (room_number like '%".$room_numbers_kw."%') ";
    endif;
    if($room_numbers_kw)$sql_kw.=" and room_number='".$room_numbers_kw."'";  
?>
<section id="main" class="wrapper">
	<!-- <div class="rwd-box"></div><br><br> -->
	
	<div class="col-12"><h2 style="margin-top: -30px;" align="center">費率設定</h2></div>
	<div class="col-12"><a href='system_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a></div>
	
	<div class="row">
	<?php if($_GET[success] == 1){ ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong><?php echo $lang->line("index.success_room_settings"); ?>!!</strong>
		</div>
	<?php } elseif ($_GET[success] == 2) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong><?php echo $lang->line("index.success_room_settings_all"); ?>!!</strong>
		</div>
	<?php } elseif ($_GET[success] == 3) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>使用時數成功重置!!</strong>
		</div>
	<?php } elseif ($_GET[success] == 4) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong><?php echo $lang->line("index.success_create_member"); ?>!!</strong>
		</div>		
	<?php } elseif ($_GET[success] == 5) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>使用時數全部成功重置!!</strong> 
		</div>
	<?php } elseif ($_GET[success] == 6) { ?>
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
		  <strong>公用卡建立成功!!</strong> 
		</div>
	<?php }elseif ($_GET[error] == 1){ ?> 
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
		  <strong>Error</strong>非有效房號格式
		</div>	
	<?php } ?>
	</div>    
		<hr>
		<div class="inner">     
			<div class="row">
                <!-- 房間設定管理員權限管理 -->
                <?php if( ($_SESSION[admin_user][id] == '余惠真') || ($_SESSION[admin_user][id] == 'andy') || ($_SESSION[admin_user][id] == 'aoadmin') ){ ?>
					<div class="col-8">	
							<form id="myForm" action='#' method='get' style="width: 40%; margin: 0 auto;">
									<div class="form-group">
										<label for="exampleInputEmail1">輸入房號</label>  
										<input style="width: 100%;" type="search" name="room_numbers_kw" value="<?php echo $room_numbers_kw; ?>" class="RoomSearch form-control" placeholder="Ex. L101">
										<button style="width: 100%;height: 30px;">查詢</button>  
									</div>
							</form>
					</div>
					<div class="col-4">
						<label for="exampleInputEmail1">全部房間設定</label>  
						<div class="form-check">
							<input type="checkbox" class="form-check-input userroomBoxChangeAll" name="status" value="update_all" id="exampleCheck1">
							<label class="form-check-label" for="exampleCheck1">請先勾選</label><br><br>

							<input type="radio" class="BoxPriceElecDegree form-check-input userroomBoxChangePriceElecDegree" name="status" value="update_price_elec_degree" id="exampleCheck2" checked>
							<label class="BoxPriceElecDegree form-radio-label" for="exampleCheck2">更新費率</label>

							<!-- <input type="radio" class="BoxMode form-check-input userroomBoxChangeMode" name="status" value="update_mode" id="exampleCheck3">
							<label class="BoxMode form-radio-label" for="exampleCheck3">更新模式</label> -->
						</div>
					</div>
                <?php } else { ?>
					<div class="col-12">	
                        <form id="myForm" action='#' method='get' style="width: 40%; margin: 0 auto;">
							<div class="form-group">
								<label for="exampleInputEmail1">輸入房號</label>  
								<input style="width: 100%;" type="search" name="room_numbers_kw" value="<?php echo $room_numbers_kw; ?>" class="RoomSearch form-control" placeholder="Ex. L101">
								<button style="width: 100%;height: 30px;">查詢</button> 
							</div>
                        </form>
					</div>
                <?php } ?>
			</div>
		</div>
		<hr>
        <?php 
            $list_q="select * from room where 1 $sql_kw ";
            $list_r = $PDOLink->prepare($list_q); 
            $list_r->execute();
            $rs = $list_r->fetch();       
            $user_room_number=$rs[room_number];
            $id=$rs[id]; 
            $room_numbers_kw; 

            //左半邊
            $mode=$rs[mode];
            $elec_status=$rs[elec_status];
            $price_elec_degree=$rs[price_elec_degree];
            $amonut=$rs[amonut];

            //右半邊
            $price_start_date = $rs[price_start_date]; 
            $price_end_date = $rs[price_end_date];
            $contact = $rs[contact];
            $price_max = $rs[price_max];

            //使用時數
            $amount=$rs[amount];

        ?>
	
	<div class="inner">
		<div class="row">
			<!-- 單間設定 -->
			<div class="col-6">	
				<?php if($room_numbers_kw){ ?> 
					<form style="width: 55%; text-align: center; margin: 0 auto;" action="RoomControlUpd.php" method="post"> 
					<input type="hidden" name="act" value="1">  
					<input type="hidden" name="data_all" value="1">
					<input type="hidden" name="status" value="1"> 
					<input type="hidden" name="room_number" value="<?php echo $user_room_number; ?>"> 
					<input type="hidden" name="id" value="<?php echo $id; ?>"> 
						<!-- <div class="form-group">
							<label for="exampleInputEmail1">收費設定</label>
							<?php
								$sel_q="select * from var_list where var_type='收費設定' order by var_value desc";
								$sel_r= $PDOLink->Query($sel_q); 
								if($sel_r)
								{
									print "<select class='form-control' size='1' name='mode'>";
									while($rs=$sel_r->Fetch())
									{
											$v_name=$rs[var_name];
											$v_value=$rs[var_value1];
											print "<option value='".$v_value."'";if($mode==$v_value)print " selected "; print ">".$v_name."</option>";
									}
									print "</select>";
								}
							?>
						</div> -->
						<div class="form-group">
							<label for="exampleInputPassword1">用電度數</label>
							<input type="text" class="form-control" name="price_elec_degree" value="<?php echo $price_elec_degree; ?>">
						</div>
						<button type="submit" class="btn btn-primary">確認更新</button>
					</form>
				<?php } ?>
		</div>
		 <!-- 全部設定 -->
		 <div class="col-6">	 
			<form style="width: 55%; text-align: center; margin: 0 auto;" action="RoomControlUpd.php" method="post">
				<input type="hidden" name="act" value="1">  
				<input type="hidden" name="data_all" value="2">
				<input class="BoxValueStatus" type="hidden" name="status">
					<div class="InputModeALL form-group">
						<label for="exampleInputEmail1">收費設定</label>
						<?php
							$sel_q="select * from var_list where var_type='收費設定' order by var_value desc";
							$sel_r= $PDOLink->Query($sel_q); 
							if($sel_r)
							{
								print "<select class=' form-control' size='1' name='mode_all'>";
								while($rs=$sel_r->Fetch())
								{
										$v_name=$rs[var_name];
										$v_value=$rs[var_value1];
										print "<option value='".$v_value."'";if($mode==$v_value)print " selected "; print ">".$v_name."</option>";
								}
								print "</select>";
							}
						?>
					</div>
					<div class="InputPriceElecDegreeALL form-group">
						<label for="exampleInputPassword1">用電度數</label>
						<input type="text" class="form-control" name="price_elec_degree_all" value="<?php echo $price_elec_degree; ?>">
					</div>
					<button id="ChangeSubMitALL" type="submit" class="btn btn-primary">確認更新</button>
			</form>
		</div>
   </div>
</div>

<style>
	.ao_room_box {
		width: 100%;
		margin: 0 35% 60%;
	}
	.ao_room_box_main {
		float: left;
		width:  100%;
	}

	@media only screen and (min-width: 320px) and (max-width: 1200px){

		.ao_room_box {
			width: 100%;
			margin: 0 8%;
			margin-bottom: 200%;
		}
		.ao_room_box_main {      
			float: left;
			width:  100%;   
			/*background-color: #000;*/
		} 
   
	}
</style>

<script>
$(".RoomSearch").change(function(){
	document.getElementById("myForm").submit(); 
}); 

$(document).ready(function(){      
    $(".BoxPriceElecDegree").hide();  
    $(".BoxMode").hide();
    $(".InputModeALL").hide();
    $(".InputPriceElecDegreeALL").hide();
    $("#ChangeSubMitALL").hide(); 

    $(".userroomBoxChangeRoom").click(function(){ 
        $(".InputPriceElecDegreeALL").show();
        $(".InputModeALL").show();
        $("#ChangeSubMitALL").show();
    });

    $(".userroomBoxChangeAll").change(function(){
        if($(".userroomBoxChangeAll").prop("checked")) {
            $(".BoxPriceElecDegree").show();
            $(".BoxMode").show();
            $(".InputPriceElecDegreeALL").show();
            $("#ChangeSubMitALL").show();
        } else {
            $(".BoxPriceElecDegree").hide();
            $(".BoxMode").hide();
            $(".InputPriceElecDegreeALL").hide();
            $(".InputModeALL").hide();
            $("#ChangeSubMitALL").hide();
        }
    });
    
    $(".BoxPriceElecDegree").change(function(){
						var value = $(this).val();
						var t = $(".BoxValueStatus").val(value);

            if($(".BoxPriceElecDegree").prop("checked")){
                $(".InputPriceElecDegreeALL").show();
                $(".InputModeALL").hide();
                $("#ChangeSubMitALL").show();
            } else {
                $(".InputPriceElecDegreeALL").hide();
                $("#ChangeSubMitALL").hide();
            }           
    });        

    $(".BoxMode").change(function(){
						var value = $(this).val();
						var t = $(".BoxValueStatus").val(value);

            if($(".BoxMode").prop("checked")){
                $(".InputModeALL").show();
                $(".InputPriceElecDegreeALL").hide();
                $("#ChangeSubMitALL").show();
            } else {
                $(".InputMode").hide();
                $("#ChangeSubMitALL").hide();
            }  
    });
});
</script>
<?php include('footer_layout.php'); ?>