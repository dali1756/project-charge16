<?php 
	include('header_layout.php');
	include('nav.php');
	include('chk_log_in.php');

	$RefundTimeTitle = array(1 => "星期一",2 => "星期二",3 => "星期三",4 => "星期四",5 => "星期五",6 => "星期六",7 => "星期日"); 
	$Get_list_q="select * from refund_interval_setting where id='".$_GET[Id]."' ";
	$Get_list_r = $PDOLink->prepare($Get_list_q);
	$Get_list_r->execute(); 
	$Get_rs = $Get_list_r->fetch();  
    $DayCount = strlen($Get_rs[day]);  
    $Time = $Get_rs[time];    
    $StrReplactTime = str_replace("^","",$Time);
    $Vision = $Get_rs[vision];                             
    $Day =$Get_rs[day];                              // 0
    $DebugDay = BugIdea($Day);
    $arr = preg_split('//', $Time, -1, PREG_SPLIT_NO_EMPTY);
    $Counts = count($arr);

    /* 退費時段演算法 */ 
    include('RefundTimeStatus.php');
    
?> 
<section  id="main" class="wrapper">
  <!-- <div class="rwd-box"></div><br><br> -->
  
  <h2 style="margin-top: -30px;" align="center">
    <?php 

    if($DayCount > 1){ 

      switch ($DayCount) {
        case 4:
            $arr = preg_split('//', $Get_rs[day], -1, PREG_SPLIT_NO_EMPTY);
            $m = $arr[0].$arr[1];
            $md = $arr[2].$arr[3];
            echo  $m."月".$md."日";
          break;

        case 3:
            $arr = preg_split('//', $Get_rs[day], -1, PREG_SPLIT_NO_EMPTY);
            $m = $arr[0].$arr[1];
            $md = $arr[2].$arr[3];

            if( (strlen($m) == 1) && (strlen($md) == 2) ){ 
            
             echo $arr[0]."月".str_replace("0","",$arr[1]).str_replace("0","",$arr[2])."日"; 

            } elseif( (strlen($m) == 2) && (strlen($md) == 1) ) { 

             echo $arr[0]."月".str_replace("0","",$arr[1]).str_replace("0","",$arr[2])."日";                               

            } else {

             echo $m."月".$md."日";

            }

          break;

        case 2:
            $arr = preg_split('//', $Get_rs[day], -1, PREG_SPLIT_NO_EMPTY);
            $m = $arr[0].$arr[1];
            $md = $arr[2].$arr[3];
            echo   $m."月".$md."日";
          break;
      }

    } else {
    
      echo  $RefundTimeTitle[$DebugDay]; 

    }

    ?> - 設定啟用狀態
  </h2>
  <div class="col-12"><a href='RefundTimeControl.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a></div>
  
  
  <div class="row">
    <?php if($_GET[success]){ ?>
    <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
      <strong>【success】成功設置!!</strong>
    </div>
    <?php } elseif ($_GET[error] == 1) { ?>
    <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
      <strong>【error】使用者操作錯誤，結束時間大於開始時間，請重新設定!</strong>
    </div>
    <?php } elseif ($_GET[error] == 2) { ?>
    <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
      <strong>【error】使用者操作錯誤，時間不得為空!</strong>
    </div>   
    <?php } ?>
  </div>
  <br>
    <div class="inner">       
    <div class="row">
        <!-- <a href='RefundTimeControl.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a><br>    -->
          <form id='mform' action="RefundTimeControlUpd.php" method="post">
            <input type="hidden" name="act" value="refund_edit">
            <input type="hidden" name="_id" value="<?php echo $_GET[Id]; ?>">
            <input type="hidden" name="_vision" value="<?php echo $Vision; ?>">
            <input type="hidden" name="_day" value="<?php echo $DebugDay; ?>">
            <input type="hidden" name="_daycount" value="<?php echo $DayCount; ?>">
            <div class="col-12">
              <div class="card"> 
                <div class="row">          

                  <!-- 排程1 -->              
                  <div class="col-12"><br> 

                      <div class="form-check">  
                          <label for='exampleInputEmail1'> 排程1：</label><br> 

                          <?php if( ($r1 == "^") ){ ?>
                          <div class='form-check'> 
                             <input type='checkbox' value='^' checked name='status_open1[]' class='form-check-input' id='exampleCheck1'>
                             <label class='form-check-label' for='exampleCheck1'>不啟用</label>
                          </div>         
                          <?php } else { ?>
                          <div class='form-check'> 
                             <input type='checkbox' value='^' name='status_open1[]' class='form-check-input' id='exampleCheck1'>
                             <label class='form-check-label' for='exampleCheck1'>不啟用</label>
                          </div> 
                          <?php } ?>

                      </div> 

                   </div>
                   <div class="select_power_startdate col-6">  
                      <?php echo $lang->line("index.start_time"); ?> <input class='select_power_startdate form-control' type='time' name='one_time_a' value="<?php echo $a; ?>" placeholder="hrs:mins">
                   </div>
                   <div class="select_power_enddate col-6">
                      <?php echo $lang->line("index.end_time"); ?> <input class='select_power_enddate form-control' type='time' name='one_time_b' value="<?php echo $b; ?>">
                   </div> 
                  <!-- 排程2 -->
                  <div class="col-12"><br> 
                    <div class="form-check">  

                      <label for='exampleInputEmail1'> 排程2：</label><br> 

                          <?php if( ($r2 == "^") ){ ?>
                          <div class='form-check'> 
                             <input type='checkbox' value='^' checked name='status_open2[]' class='form-check-input' id='exampleCheck2'>
                             <label class='form-check-label' for='exampleCheck2'>不啟用</label>
                          </div>   
                          <?php } else { ?>
                          <div class='form-check'> 
                             <input type='checkbox' value='^' name='status_open2[]' class='form-check-input' id='exampleCheck2'>
                             <label class='form-check-label' for='exampleCheck2'>不啟用</label>
                          </div>  
                          <?php } ?>      

                      </div> 
                  </div>
                  <div class="select_power_startdate col-6">  
                      <?php echo $lang->line("index.start_time"); ?> <input class='select_power_startdate form-control' type='time' name='two_time_c' value="<?php echo $c; ?>" placeholder="hrs:mins">
                  </div>
                  <div class="select_power_enddate col-6">
                      <?php echo $lang->line("index.end_time"); ?> <input class='select_power_enddate form-control' type='time' name='two_time_d' value="<?php echo $d; ?>">
                  </div>

                  <?php if( ($DebugDay == 7) || ($DebugDay == 6) ){ ?>
                  <div class="col-12"><br> 
                    <div class="form-check">  

                      <label for='exampleInputEmail1'> 排程3：</label><br> 

                          <?php 
						  if( ($r3 == "^") ){ ?>
                          <div class='form-check'> 
                             <input type='checkbox' value='^' checked name='status_open3[]' class='form-check-input' id='exampleCheck3'>
                             <label class='form-check-label' for='exampleCheck3'>不啟用</label>
                          </div>   
                          <?php } else { ?>
                          <div class='form-check'> 
                             <input type='checkbox' value='^' name='status_open3[]' class='form-check-input' id='exampleCheck3'>
                             <label class='form-check-label' for='exampleCheck3'>不啟用</label>
                          </div>  
                          <?php } ?>      

                      </div> 
                  </div>
                  <div class="select_power_startdate col-6">  
                      <?php echo $lang->line("index.start_time"); ?> <input class='select_power_startdate form-control' type='time' name='two_time_e' value="<?php echo $e; ?>" placeholder="hrs:mins">
                  </div>
                  <div class="select_power_enddate col-6">
                      <?php echo $lang->line("index.end_time"); ?> <input class='select_power_enddate form-control' type='time' name='two_time_f' value="<?php echo $f; ?>">
                  </div>
                  <?php } ?>

                  <div style="text-align: center;" class="col-12"><br>
                      <input type="submit" class="btn btn-primary" value="<?php echo $lang->line("index.confirm_update"); ?>">
                  </div>

                </div>
              </div>
            </div>
          </form>
          </div>
  </div>
</section>
<script>
//回上一頁
function backs()
{
  history.go(-1);
}
$(document).ready(function(){
    var ppp =  $(".get_price").val();
    if(ppp == '1'){
        $(".select_price").css("display","inline");
    } else {
        $(".select_price").css("display","none");
    }

    $(".get_price").change(function(){
        var get_price = $(this).val();

        if(get_price == '1'){
            $(".select_price").css("display","inline");
        } else {
            $(".select_price").css("display","none");
        }

        console.log(get_price); //除錯
    });

    //開機&關機
    $(".userroomBoxPowerTypeChange").change(function(){

       if($(".userroomBoxPowerTypeChange").prop("checked")) {
             $(".select_power_type").show();
             $(".select_power_startdate").show();
             $(".select_power_enddate").show();
             
         //$(".user_room_two").show();
         // $("input[name='user_room_box[]']").each(function() {
         //     $(this).prop("checked", true);
         // });
       } else {
             $(".select_power_type").hide();
             $(".select_power_startdate").hide();
             $(".select_power_enddate").hide();
         //$(".user_room_two").hide();

         // $("input[name='user_room_box[]']").each(function() {
         //     $(this).prop("checked", false);
         // });
       }

    });
	
	$(document).on('submit', $('#mform'), function() {
		
		var check1 = $('#exampleCheck1').prop('checked');
		var check2 = $('#exampleCheck2').prop('checked');
		var check3 = $('#exampleCheck3').prop('checked');
		
		if(check1 !== check2) {
			if( ($('input[type=time][name=two_time_c]').val() >= $('input[type=time][name=one_time_a]').val()) &	
				($('input[type=time][name=two_time_c]').val() <  $('input[type=time][name=one_time_b]').val())
			) {
				alert('排程時間重疊');
				return false;
			}
			
			if( ($('input[type=time][name=two_time_d]').val() >= $('input[type=time][name=one_time_a]').val()) &	
				($('input[type=time][name=two_time_d]').val() <  $('input[type=time][name=one_time_b]').val())
			) {
				alert('排程時間重疊');
				return false;
			}			
		}
		
		if(check3 !== 'undefined') 
		{	
	
			if(check3 !== check1) 
			{	
				if( ($('input[type=time][name=two_time_e]').val() >= $('input[type=time][name=one_time_a]').val()) &	
					($('input[type=time][name=two_time_e]').val() <  $('input[type=time][name=one_time_b]').val())
				) {
					alert('排程時間重疊');
					return false;
				}
				
				if( ($('input[type=time][name=two_time_f]').val() >= $('input[type=time][name=one_time_a]').val()) &	
					($('input[type=time][name=two_time_f]').val() <  $('input[type=time][name=one_time_b]').val())
				) {
					alert('排程時間重疊');
					return false;
				}			
			}
			
			if(check3 !== check2) 
			{					
				if( ($('input[type=time][name=two_time_e]').val() >= $('input[type=time][name=two_time_c]').val()) &	
					($('input[type=time][name=two_time_e]').val() <  $('input[type=time][name=two_time_d]').val())
				) {
					alert('排程時間重疊');
					return false;
				}
				
				if( ($('input[type=time][name=two_time_f]').val() >= $('input[type=time][name=two_time_c]').val()) &	
					($('input[type=time][name=two_time_f]').val() <  $('input[type=time][name=two_time_d]').val())
				) {
					alert('排程時間重疊');
					return false;
				}			
			}
		}
		
	});
	
});
</script>
<?php include('footer_layout.php'); ?>