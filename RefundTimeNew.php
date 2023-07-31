<?php include('header_layout.php'); ?>
<?php include('nav.php'); ?>
<?php include('chk_log_in.php'); ?>
<section  id="main" class="wrapper">
  <!-- <div class="rwd-box"></div><br><br> -->
	<h2 style="margin-top: -30px;" align="center">指定時段新增</h2>
	<div class="col-12"><a href='normal_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a></div>
	
      <div class="row">
      <?php if($_GET[success]){ ?>
        <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
          <strong><?php echo $lang->line("index.success_lilumination_system_settings"); ?>!!</strong>
        </div>
      <?php } elseif ($_GET[error] == 1) { ?>
        <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
          <strong>【error】，使用者操作錯誤，結束時間大於開始時間，請重新設定!</strong>
        </div>
        <?php } elseif ($_GET[error] == 2) { ?>
        <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
          <strong>【error】使用者操作錯誤，<?php echo $_GET[GetDay]; ?>排程已存在，請重新設定!</strong>
        </div>    
        <?php } elseif ($_GET[error] == 3) { ?>
            <div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-success" role="alert">
          <strong>【error】球場時段重複建立!</strong>
        </div>      
      <?php } ?>
      </div>
    <div class="inner">       
    <div class="row">
        <!-- <a href='normal_administration.php'><i class='fas fa-chevron-circle-left fa-3x'></i></a><br>    -->
          <form action="RefundTimeControlUpd.php" method="post">
            <input type="hidden" name="act" value="refund_new">
            <div class="col-12">
              <div class="card"> 
                <div class="row">          

                   <div class="col-6">  
                      月：
                      <select class='form-control' name="one_month">
                        <?php for ($i=1; $i <= 12; $i++) { ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?>月</option>
                        <?php } ?>
                      </select>
                   </div>
                   <div class="col-6">  
                      日：<input required="required" class='form-control' type='number' min="1"  name='one_day'>
                   </div><br><br>

                  <!-- 排程1 -->              
                  <div class="col-12"><br> 

                      <div class="form-check">  
                          <label for='exampleInputEmail1'> 排程1：</label><br> 

                          <div class='form-check'> 
                             <input type='checkbox' value='^' name='status_open1[]' class='form-check-input' id='exampleCheck1'>
                             <label class='form-check-label' for='exampleCheck1'>不啟用</label>
                          </div>         

                      </div> 

                   </div>

                   <div class="select_power_startdate col-12">  
                      <?php echo $lang->line("index.start_time"); ?> 
                      <input class='select_power_startdate form-control' type='time' name='one_time_a' placeholder="hrs:mins" value="06:00">
                   </div>
                   <div class="select_power_enddate col-12">
                      <?php echo $lang->line("index.end_time"); ?> 
                      <input class='select_power_enddate form-control' type='time' name='one_time_b' placeholder="hrs:mins" value="11:00">
                   </div>

                  <!-- 排程2 -->
                  <div class="col-12"><br> 
                    <div class="form-check">  

                      <label for='exampleInputEmail1'> 排程2：</label><br> 

                          <div class='form-check'> 
                             <input type='checkbox' value='^'  name='status_open2[]' class='form-check-input' id='exampleCheck2'>
                             <label class='form-check-label' for='exampleCheck2'>不啟用</label>
                          </div>   

                      </div> 
                  </div>
                  <div class="select_power_startdate col-12">  
                      <?php echo $lang->line("index.start_time"); ?> <input class='select_power_startdate form-control' type='time' name='two_time_c' placeholder="hrs:mins" value="15:00">
                  </div>
                  <div class="select_power_enddate col-12">
                      <?php echo $lang->line("index.end_time"); ?> <input class='select_power_enddate form-control' type='time' name='two_time_d' placeholder="hrs:mins" value="21:00">
                  </div>

                  <div style="text-align: center;" class="col-12"><br>
                      <input  type="submit" class="btn btn-primary" value="確認新增">
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

});
</script>
<?php include('footer_layout.php'); ?>