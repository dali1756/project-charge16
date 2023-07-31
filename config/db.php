<?php


$web_title = "【AOTECH】智慧充電計費系統";
$get_path  = get_web();
$web       = $get_path["web"];
// $s_path= "home/andy/www/ndhu/"; // $get_path["s_path"];
// $db_name="ndhu";//資料庫名稱
$upload_set = false;//設定fckedit 是否可以上傳檔案true,false
$today     = date("Y-m-d");

date_default_timezone_set("Asia/Taipei");   					    //時區(亞洲/台北)
session_cache_expire(28800);											        //session逾時設定; 
ini_set('session.gc_probability',100); 	
session_start();
ob_start();								    					                  //可以解決header有先送出東西的問題
ob_end_clean();							    					                //先ob_start 再進行一次ob_end_clean
header("Cache-Control:no-cache,must-revalidate");   			//強迫更新
header("P3P: CP=".$_SERVER["HTTP_HOST"]."");        			//解決在frame中session不能使用的問題，可填ip或是domain
header('Content-type: text/html; charset=utf-8');				  //指定utf8編碼 
header('Vary: Accept-Language');
ini_set("display_errors",false); 								          //顯示 Error, true => 開, false => 關
//set_time_limit(30);
//ini_set("error_reporting",E_ALL & ~E_NOTICE);
//error_reporting(E_ALL ^ E_NOTICE);  
//error_reporting(0);不輸出所有的erro
//php5.1.1 for win 要指定時區，不然可能會錯

$PDOLink = db_conn();

// 暫改 -- 20200107
function db_conn() 
{
	
	$PDOLink;
	$PDOHostVar       = '172.104.120.19:3306';
	$PDODBnameVar     = 'charge16';
	$PDODBuserVar     = 'barry';
	$PDODBpasswordVar = 'su631811';

	// 正規PDO連接
	try {   
		$PDOLink = new PDO("mysql:host={$PDOHostVar};dbname={$PDODBnameVar}",$PDODBuserVar,$PDODBpasswordVar);  
		$PDOLink->query("SET NAMES 'utf8'");
		$PDOLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		print "連線失敗" . $e->getMessage(); 
	}
	
	return $PDOLink;
}

/* 優化SQL select v1.0版 */
function SQLSelectCode($SelectCol,$Table,$Condition,$Value,$OrderBy,$Limit)
{
   if($Value == '')$Value='1 ';
   $sql = "select $SelectCol from $Table $Condition $Value $OrderBy $Limit";
   return $sql;
}

function get_refund($ref) 
{
	$ref_arr = array(
		'0' => '非同卡退費',
		'1' => '同卡退費',
	);
	
	return $ref_arr[$ref];
}

function get_mode($mode)
{
	// 1 (計費)  3 (免費) 4 (停用)
	$mode_arr = array(
		'1' => '計費',
		'3' => '免費',
		'4' => '停用',
	);
	
	return $mode_arr[$mode];
}

function get_status($mode) 
{
	
	$status_arr = array(
		'1' => '1', // 再抓 use_status -> power 判斷使用中、待機中
		'3' => '使用中', 
		'4' => '強制關閉',
	);
	
	return $status_arr[$mode];
}

function get_power($power) 
{
	
	$power_arr = array(
		'0' => '待機中',
		'1' => '使用中'
	);
	
	return $power_arr[$power];
}

function get_weekday($weekday)
{
	return [
		'星期日', '星期一',
		'星期二', '星期三',
		'星期四', '星期五',
		'星期六',][$weekday];
}

function PayValueZero($PayValue)
{
	if($PayValue < 1){
		return 0; 
	} else {
		return $PayValue;
	}
}
function get_log_list($content) {
	/* 使用資料庫 */
	$PDOLink = db_conn();
    $PDOLink->query("SET NAMES 'utf8'");
    /* insert log history */
    $date = date("Y-m-d H:i"); 
    /* error_log php function */
    error_log($content."\n", 3, "/var/tmp/my-errors.log","andy952737@gmail.com");
        /* log db save */
        try {
            $col="`content`, `data_type`, `add_date`";
            $col_data="'".$content."','1','".$date."' ";
            $ins_q="insert into log_list (".$col.") values (".$col_data.") ";
            $PDOLink->exec($ins_q); 
        }
        catch(PDOException $e){
           echo $ins_q . "<br>" . $e->getMessage();
        }
}

function get_room_id($sn,$db_name,$db_value)
{
	// function內的database load
    // $pdodb = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018');
	$pdodb = db_conn();
    $pdodb->query("SET NAMES 'utf8'");
    
    $sql = "select * from ".$db_name." where 1 and id='".$sn."' ";
    $list_r = $pdodb->prepare($sql); 
    $list_r->execute();
    $row = $list_r->fetch();         
	
	return $row[$db_value];
}
function get_id($sn,$db_name,$db_value)
{
	// function內的database load
    // $pdodb = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018');  
	$pdodb = db_conn();
    $pdodb->query("SET NAMES 'utf8'");
    
    $sql = "select * from ".$db_name." where 1 and id='".$sn."' ";
    $list_r = $pdodb->prepare($sql); 
    $list_r->execute();
    $row = $list_r->fetch();         
	
	return $row[$db_value];
}
function get_card($sn,$db_name,$db_value)
{
	// function內的database load
    // $pdodb = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018'); 
	$pdodb = db_conn();	
    $pdodb->query("SET NAMES 'utf8'");
    
    $sql = "select * from ".$db_name." where 1 and id_card='".$sn."' ";
    $list_r = $pdodb->prepare($sql); 
    $list_r->execute();
    $row = $list_r->fetch();         
	
	return $row[$db_value];
}
function get_admin_id($sn,$db_name,$db_value)
{
	// function內的database load
    // $pdodb = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018');  
	$pdodb = db_conn();
    $pdodb->query("SET NAMES 'utf8'");
    
    $sql = "select * from ".$db_name." where 1 and sn='".$sn."' ";
    $list_r = $pdodb->prepare($sql); 
    $list_r->execute();
    $row = $list_r->fetch();         
	
	return $row[$db_value];
}
function get_phpfunction($Value){ 
	/* 驗證時間是否為正規值 */ 
	$EndDate = $Value;
	$NewDate = preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $EndDate);
	if($NewDate){
		
		/* 3600 -> hour, 60 -> minute, second */
		$NowDate = date("Y-m-d H:i:s");
		//$spantime = $EndDate - $NowDate/1000;		     		
		//$EndDayDate = $spantime / (24 * 3600);       						  //剩餘 天	                         
		$EndHourDate = (strtotime($EndDate)-strtotime($NowDate))/3600;        //剩餘 小時
		$EndMinuteDate = (strtotime($EndDate)-strtotime($NowDate))%3600/60;   //剩餘 分鐘
		$EndSecondDate = (strtotime($EndDate)-strtotime($NowDate))%60;        //剩餘 秒 

		if($EndSecondDate < 0){
			
			return " 你的時間到了唷!!";                 

		} else {         

			if($EndHourDate <= 1){                 
								
				return "您剩下：<b  style='color:blue;'>".round($EndMinuteDate,0)."分</b>"; // ".$EndSecondDate."秒

			} elseif($EndMinuteDate <= 1) {           

				return "您剩下：<b  style='color:blue;'>".$EndSecondDate."秒</b>";    

			} else {  
				
				return "您剩下：<b  style='color:blue;'>".round($EndHourDate,0)."小時".round($EndMinuteDate,0)."分</b>"; // ".$EndSecondDate."秒
				
			}
		}	 

	} else {

		return "Error，格式錯誤!!";  
	
	}
} 
function chk_src()
{
	$src_url=dirname($_SERVER[HTTP_REFERER]);
	if(!instr($src_url,"happinesspetcuisine.com"))
	exit("<script>top.location.href='index.php';</script>");
}

function test_function_value($CountTime)
{
	 if($CountTime == 15) {
	    /* 月 */
		$TimeMonthSplit = str_split($Time,7);
		echo $StartTime = $TimeMonthSplit[0];
		echo "至";
		echo $EndTime = $TimeMonthSplit[1];
	  } elseif($CountTime == 16) {
        $TimeMonthSplit = str_split($Time,7);
        echo $StartTime = $TimeMonthSplit[0];
        echo "至";
        echo $EndTime = $TimeMonthSplit[1];
      } elseif($CountTime == 17) {
        $TimeMonthSplit = str_split($Time,7);
        echo $StartTime = $TimeMonthSplit[0];
        echo "至";
        echo $EndTime = $TimeMonthSplit[1];      
      } elseif($CountTime == 18) {
        $TimeMonthSplit = str_split($Time,7);
        echo $StartTime = $TimeMonthSplit[0];
        echo "至";
        echo $EndTime = $TimeMonthSplit[1];
      } elseif ($CountTime == 21) {
		/* 日 */
		$TimeDaySplit = str_split($Time,10);
		echo $StartTime = $TimeDaySplit[0];
		echo "至";
		echo $EndTime = $TimeDaySplit[1];
	  } elseif($CountTime == 22){
        $TimeDaySplit = str_split($Time,10);
        echo $StartTime = $TimeDaySplit[0];
        echo "至";
        echo $EndTime = $TimeDaySplit[1];
      } elseif($CountTime == 23){
        $TimeDaySplit = str_split($Time,10);
        echo $StartTime = $TimeDaySplit[0];
        echo "至";
        echo $EndTime = $TimeDaySplit[1];
      } elseif($CountTime == 24){
        $TimeDaySplit = str_split($Time,10);
        echo $StartTime = $TimeDaySplit[0];
        echo "至";
        echo $EndTime = $TimeDaySplit[1];
      }
}
function admin_chk($uid,$upwd)
{
	// $PDOLink = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018');  
	$PDOLink = db_conn();
	$PDOLink->query('SET NAMES "utf8"'); 
	$upwd=str_replace(" ","",$upwd);
	$uid=str_replace(" ","",$uid);
	if($upwd and $uid)
	{
		$mb_chk_q="select * from admin where id='".$uid."' and pwd=password('".$upwd."') and status='Y' ";
		$stmt = $PDOLink->prepare($mb_chk_q); 
		$stmt->execute(array($uid, $upwd));
		$result = $stmt->fetch(); 
		if($result)
		{
			$_SESSION[admin][id]=$result[id];
			$_SESSION[admin][pwd]=$result[pwd];
			$_SESSION[admin][sn]=$result[sn];
			$_SESSION[admin][cname]=$result[cname];
			$_SESSION[admin][power]=$result[data_type];
			$_SESSION[admin][data_type]=$result[data_type];
			header("Location:index.php");
		}
		else
		{
			print "<script>alert('帳號或密碼不正確請重新再試一次');</script>";
		}
		$PDOLink = NULL;
	}
}
function logout()
{
	unset($_SESSION[admin]);
	unset($_SESSION[user]);
}
function fix_url($string)
{
	$string=str_replace("http://","",$string);
	if($string)$string="http://".$string;
	return $string;
}
function fix_xls_date($string)
{
	$string=str_replace("-","/",$string);
	$got_birth=explode("/",$string);
	if($got_birth[0]<1900)
	{
		$new_date=$got_birth[2]."/".$got_birth[1]."/".$got_birth[0];
		$new_date=strtotime("-1 days",strtotime($new_date));//從excel抓日期格式會多一日
		$birth=date("Y",$new_date)."-".date("m",$new_date)."-".date("d",$new_date);
	}
	else
	{
		$birth=$got_birth[0]."-".$got_birth[1]."-".$got_birth[2];
	}
	return $birth;
}
/* r_size 準備棄用 */
function r_size($list_r)
{
	$result=mysql_num_rows($list_r);
	return $result;
}
function q_num()
{
	$result=mysql_affected_rows();
	return $result;
}
function last_id()
{
	$result=mysql_insert_id();
	return $result;
}
function col_size($list_r)
{
	$result=mysql_num_fields($list_r);
	return $result;
}
//分割中文字串變成陣列
function split_txt($str)
{
	$max_num=mb_strlen($str,"UTF-8");
	for($j=0;$j<$max_num;$j++)
	{
		$new_txt[$j]=bk_string($str,$j,2,"");
		if(ord($new_txt[$j])<=122)$new_txt[$j]=bk_string($str,$j,1,"");
	}
	return $new_txt;
}
function fix_num($str)
{
	$str=str_replace("分機","#",$str);
	$mobile=split_txt($str);
	unset($n_w);
	for($i=0;$i<count($mobile);$i++)
	{
		if(ord($mobile[$i])<128)
		{
			$n_w[]=$mobile[$i];
		}
	}
	return implode("",$n_w);
}
function big5_2_utf8($big5str) {
	
	$blen = strlen($big5str);
	$utf8str = "";
	for($i=0; $i<$blen; $i++) 
	{
		$sbit = ord(substr($big5str, $i, 1));
		if ($sbit < 129) 
		{
			$utf8str.=substr($big5str,$i,1);
		}
		elseif ($sbit > 128 && $sbit < 255) 
		{
			$new_word = iconv("BIG5", "UTF-8", substr($big5str,$i,2));
			$utf8str.=($new_word=="")?"?":$new_word;
			$i++;
		}
	}
return $utf8str;
}
function get_content($url,$method)
{
	//一般情況後面四個參數取消,$proxy,$port,$ip,$domain
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    if($ip and $domain)
    {
		$str  = array("X-Forward-For:".$ip."");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $str);
		curl_setopt($ch, CURLOPT_REFERER, $domain);
	}
	$curl_m="CURLOPT_".$method;
	curl_setopt($ch,$curl_m,1);
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.01; Windows NT 5.0)"); 
    if($proxy)
		curl_setopt($ch, CURLOPT_PROXY, $proxy); 
	if($port)
		curl_setopt($ch, CURLOPT_PROXYPORT,$port); 
    ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;     
}
function uc2ut($text)
{
	//先用file_get_contents("abc.txt",0);
	$cache = array() ;
	$text = preg_replace("/^\\xFF\\xFE/","",$text) ;
	for ($i = 0 ; $i < strlen($text) ;) 
	{
	     $dec = ord(substr($text,$i + 1,1)) * 256 + ord(substr($text,$i,1)) ;
	     $i += 2 ;
	     if (!array_key_exists($dec,$cache)) {
	          if ($dec < 256) $cache[$dec] = iconv('ISO-8859-1','UTF-8',chr($dec)) ;
	          else if ($dec < 2048) $cache[$dec] = chr(192 + (($dec - ($dec % 64)) / 64)) . chr(128 + ($dec % 64)) ;
	          else $cache[$dec] = chr(224 + (($dec - ($dec % 4096)) / 4096)) . chr(128 + ((($dec % 4096) - ($dec % 64)) / 64)) . chr(128 + ($dec % 64)) ; 
	     }
	     $string .= $cache[$dec] ;
	}
	return $string;
}
function  utf8_2_big5($utf8_str)  
{
		//如果寄信的時候轉碼出現怪字，把"■"變成 ""，即可
        $i=0;
        $len  =  strlen($utf8_str);
        $big5_str="";
        for  ($i=0;$i<$len;$i++)  {
                $sbit  =  ord(substr($utf8_str,$i,1));
                if  ($sbit  <  128)  {
                        $big5_str.=substr($utf8_str,$i,1);
                }  else  if($sbit  >  191  &&  $sbit  <  224)  {
                        $new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,2));
                        $big5_str.=($new_word=="")?"■":$new_word;
                        $i++;
                }  else  if($sbit  >  223  &&  $sbit  <  240)  {
                        $new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,3));
                        $big5_str.=($new_word=="")?"■":$new_word;
                        $i+=2;
                }  else  if($sbit  >  239  &&  $sbit  <  248)  {
                        $new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,4));
                        $big5_str.=($new_word=="")?"■":$new_word;
                        $i+=3;
                }
        }
        return  $big5_str;
}
function save_pic($pic_w,$pic_h,$src_pic,$des_pic,$imgcomp)
{
	//要顯示縮圖，必須以單一檔案處理，將$des_pic設為空值則，檔案編碼為utf8的時候輸出圖片會變亂碼，需放在ansi編碼的檔案
	if(!$imgcomp)$imgcomp=80;
	//$imgcomp=100-$imgcomp;
	if(file_exists($src_pic))
	{
		$g_is=getimagesize($src_pic);
		if($g_is[0]<$pic_w)$pic_w=$g_is[0];//如果指定的大小超過原圖大小則不縮小
		if($g_is[1]<$pic_h)$pic_h=$g_is[1];//如果指定的大小超過原圖大小則不縮小
		if(($g_is[0]-$pic_w)>=($g_is[1]-$pic_h))
		{
		   $g_iw=$pic_w;
		   $g_ih=($pic_w/$g_is[0])*$g_is[1];
		}
		else
		{
		   $g_ih=$pic_h;
		   $g_iw=($g_ih/$g_is[1])*$g_is[0];    
		}
		if($g_is[2]==1)$img_src=imagecreatefromgif($src_pic);
		if($g_is[2]==2)$img_src=imagecreatefromjpeg($src_pic);
		if($g_is[2]==3)$img_src=imagecreatefrompng($src_pic);
		$img_dst=imagecreatetruecolor($g_iw,$g_ih);
		imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);
		if($g_is[2]==1)imagegif($img_dst,$des_pic);
		if($g_is[2]==2)imagejpeg($img_dst,$des_pic,$imgcomp);
		if($g_is[2]==3)imagepng($img_dst,$des_pic,0);
		imagedestroy($img_dst);
		return true;
	}
	return false;
}
function save_pic2($pic_w,$pic_h,$src_pic,$des_pic,$imgcomp)
{
	//magickwand lib
	if(!$imgcomp)$imgcomp=90;
	$magick_wand = NewMagickWand();
	//MagickSetWandSize($magick_wand,$pic_w,$pic_h);
	MagickReadImage($magick_wand,$src_pic);
	//MagickFlattenImages($magick_wand);//遇到 psd 會死翹翹
	$src_w=MagickGetImageWidth($magick_wand); 
	$src_h=MagickGetImageHeight($magick_wand);
	if($pic_w>$src_w)$pic_w=$src_w;
	if($pic_h>$src_h)$pic_h=$src_h;
	if($src_w-$pic_w>=$src_h-$pic_h)
	{
		$pic_h=($pic_w/$src_w)*$src_h;
	}
	else
	{
		$pic_w=($pic_h/$src_h)*$src_w;
	}
	MagickSetImageCompressionQuality($magick_wand,$imgcomp);
	MagickThumbnailImage($magick_wand,$pic_w,$pic_h);// 完全依指定大小變化
	MagickWriteImage($magick_wand,$des_pic);
}

function ttf_pic($pic_w,$pic_h,$des_pic,$str)
{
	//ttf_pic(300,280,"123.jpg",$txt);//寬、高、指定存檔名稱、要秀出來的字
	if($str)
	{
		$imgcomp=90;
		$get_txt=explode("`",$str);
		//$str= iconv('big5','utf-8',$str);//如果是中文字，要先轉成utf8如果是本來就是utf8編碼的就不需要再轉換
		$im = imagecreatetruecolor($pic_w,$pic_h);
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, $pic_w, $pic_h, $white);
		//imagettftext($im, 字體大小, 角度,開始的x, 開始的y, 字的顏色, 字型, 字串);
		$font ="manchu2.ttf";//滿文字字型
		$i=0;
		$right=$pic_w-40;//由右至左
		while($get_txt[$i])
		{
			imagettftext($im, 24, -90, $right, 10, $black, $font, $get_txt[$i]);
			$right-=50;
			$i++;
		}
		imagejpeg($im,$des_pic,$imgcomp);
		imagedestroy($im);
		return true;
	}
}
function bk_string($string,$x,$y,$z)
{
	$string = strip_tags($string);
	$string = mb_strimwidth($string, $x, $y, $z, 'UTF-8');
	return $string;
}
function chk_date($string)
{
	//php5.1以後支援1970年以前的
	$string=str_replace("/","-",$string);
	$got_date=explode("-",$string);
	$chk_date=explode("-",date("Y-m-d",strtotime($string)));
	if($got_date[0]!=$chk_date[0] or $got_date[1]!=$chk_date[1] or $got_date[2]!=$chk_date[2])
		$result=0;
	else
		$result=1;
	return $result;
}
function get_age($birth,$end_date,$half)
{
	//php5.1以後支援1970年以前的
	//get_age($birth,$end_date,$half)//生日,結束日期,滿六個月加一步(保險年齡)
	$chk_b=chk_date($birth);
	$birth=strtotime($birth);
	if($end_date)
	{
		$chk_e=chk_date($end_date);
		$today=$end_date;
	}
	else
	{
		$today=date("Y-m-d");
		$chk_e=1;
	}
	if($chk_b and $chk_e)
	{
		$age=((strtotime($today)-$birth)/(24*60*60)/365);
		if($half)
		{
			$age2=floor($age)+0.6;
			if($age>$age2)
				$age=$age2+0.4;
			else
				$age=floor($age);
		}
		$age=(int)$age;	
	}
	else
		$age="";
	return $age;
}
function rev_count_day($start_date,$end_date)
{
	//倒數用的
	$start_date=strtotime($start_date);
	$end_date=strtotime($end_date);
	$days=$end_date-$start_date;
	$one_day=24*60*60;
	$one_h=60*60;
	$days[0]=(int)($days/$one_day);//日
	$d2=($days%$one_day);
	$days[1]=(int)($d2/$one_h);//時
	$h2=($d2%$one_h);
	$days[2]=($h2/(60))%60;//分
	return $days;
}
function count_day($start_date,$end_date)
{
	$start_date=strtotime($start_date);
	$end_date=strtotime($end_date);
	$days=(int)(($end_date-$start_date)/(24*60*60));
	return $days;
}
function get_num($string,$num)
{
	$string=str_repeat("0",($num+ -1 - floor(log10($string)))).$string;
	return $string;
}
function get_edit($var_name,$fck_path,$tool,$w,$h,$var_value)
{
	//線上編輯器
	//呼叫方式呼叫時請獨立包起來，不然位置會亂跑
	//get_edit("content",$fck_path,"songo_simple",640,400,$content);
	include_once("../fckeditor/fckeditor.php");
	$sBasePath = $_SERVER["PHP_SELF"] ;
	$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
	$oFCKeditor = new FCKeditor("$var_name") ;
	$oFCKeditor->BasePath = $fck_path ;
	$oFCKeditor->ToolbarSet = "$tool" ;
	$oFCKeditor->Height = "$h";
	$oFCKeditor->Width = "$w";
	$oFCKeditor->Value = "$var_value" ;
	$oFCKeditor->Create() ;
}
// function get_edit2($var_name,$fck_path,$tool,$w,$h,$var_value)
// {
// 	//線上編輯器
// 	//呼叫方式呼叫時請獨立包起來，不然位置會亂跑
// 	//get_edit("content",$fck_path,"songo_simple",640,400,$content);
// 	include_once("../ckeditor/ckeditor.php");
// 	$CKEditor = new CKEditor();
// 	$CKEditor->BasePath =$fck_path;
// 	$CKEditor->config['width']=$w;
// 	$CKEditor->config['height']=$h;
// 	$CKEditor->config['toolbar'] = array(
// 		array('Source','Preview','PasteText','PasteFromWord','Bold','Italic','Underline','Strike','RemoveFormat'),
// 		array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Image','Flash','Table','HorizontalRule','Link','Unlink','Anchor',),
// 		array('Styles','Format','Font','FontSize','TextColor','BGColor','Maximize')
// 	);
// 	//$config['skin'] = 'v2';
// 	$CKEditor->editor($var_name,$var_value);
// }
function get_edit2($var_name,$var_value,$w,$h)
{
	print "<script src='./ckeditor/ckeditor.js'></script>";
	//print "<div id='".$var_name."' contenteditable='true'>".$var_value."</div>";
	echo input_area($var_name,$var_value,$w,$h);
	//print "<script>CKEDITOR.inline('".$var_name."');</script>";
	print "<script>CKEDITOR.replace('".$var_name."');</script>";
}
function deltree($dir) { 
    $files = glob( $dir."*", GLOB_MARK ); 
    foreach( $files as $file ){ 
        if(is_dir($file)) 
            delTree($file); 
        else 
            unlink($file); 
    } 
    if (is_dir($dir)) rmdir( $dir ); 
} 

function get_code3($max,$num)
{
	//if($_SESSION[user][pass_code]==$pwd_code)
	$del_string="B,L,Q,O,U,V,Z,I,0,S,1,2,5,8";
	if($max<$num)$max=$num+2;
	for($i=1,$k=1,$passwd="";$i<=$max;$i++)
	{
		srand();
		$ch=1;
		if($ch and $k<=$num)
		{
			$b=chr(rand(65,90));
			while(($b=="L" or $b=="Q" or $b=="O" or $b=="U" or $b=="V" or $b=="Z" or $b=="I" or $b=="S" or $b=="B"))
			{
				$b=chr(rand(65,90));
			}
			$passwd.=$b;
			$k++;
		}
		else
		{
			$a=rand(3,9);
			while(($a=="5" or $a=="8"))
			{
				$a=rand(3,9);
			}
			$passwd.=$a;
		}
	}
	$pass_code=$passwd;
	return $pass_code;
}
function get_code($max,$num,$func='')
{
	//if($_SESSION[user][pass_code]==$pwd_code)
	$del_string="B,L,Q,O,U,V,Z,I,0,S,1,2,5,8";
	if($max<$num)$max=$num+2;
	for($i=1,$k=1,$passwd="";$i<=$max;$i++)
	{
		srand();
		$ch=rand(0,1);
		if($ch and $k<=$num)
		{
			$b=chr(rand(65,90));
			while(($b=="L" or $b=="Q" or $b=="O" or $b=="U" or $b=="V" or $b=="Z" or $b=="I" or $b=="S" or $b=="B"))
			{
				$b=chr(rand(65,90));
			}
			$passwd.=$b;
			$k++;
		}
		else
		{
			$a=rand(3,9);
			while(($a=="5" or $a=="8"))
			{
				$a=rand(3,9);
			}
			$passwd.=$a;
		}
	}
	$pass_code=$passwd;
	$_SESSION[user][pass_code]=$pass_code;
	if(file_exists("show_code.jpg") and file_exists("show_code.php"))
		return "<img ".$func." src='show_code.php?id=".date("YmdHIS").rand(1000,100000)."' border=0>";
	else
		return $pass_code;
}

function chk_power($power)
{
	$apower=$_SESSION[admin][power];
	if($apower!=$power)
	{
	  $chk_power=strpos($power,$apower);
	  if(is_bool($chk_power) and !$chk_power)
		$chk_power=0; 
	  else 
		$chk_power=1;
	}
	else
		$chk_power=$power;
	if(!$chk_power)
	{
		print "<script>alert('您沒有管理本項資料的權限');</script>";
		header("Location: no_power.php");
		die("您沒有管理本項資料的權限");
	}
}
function chk_selected($str)
{
	$get_file=str_replace(".php","",basename($_SERVER["PHP_SELF"]));
	$chk_str=explode(",",$str);
	$chk_num=count($chk_str);
	$pass=0;
	for($i=0;$i<$chk_num;$i++)
	{
		if(instr($get_file,$chk_str[$i]))$pass++;
	}
	if($pass)
		return 1;
	else
		return 0;
}
function instr($str,$chk_str)
{
  $chk_status=strpos($str,$chk_str);
  if(is_bool($chk_status) and !$chk_status)
	return 0; 
  else 
	return 1;
}
function repeat_str($str,$x,$first,$end)
{
	//repeat_str("字串","重複的字元","從第幾字開始","後面留的字元數")
	$mid=$first+$end;
	$str=substr($str,0,$first).str_repeat($x,strlen($str)-$mid).substr($str,strlen($str)-$end,$end);
	return $str;
}
function repeat_str2($str,$add_str,$max_num)
{
	str_repeat($add_str,($max_num + -1 - floor(log10($str))));
}
function fix_str($str,$num)
{
	return $str=substr($str,0,strlen($str)-$num);
}
function unzip($zipFile = '', $dirFromZip = '' ,$target_path)
{    
	//unzip($s_path."/test.zip(zip來源檔)","(指定符合路徑的條件)",$s_path."/upload/book/"(指定解放到特定目錄));
    define(DIRECTORY_SEPARATOR, '/');
    $zipDir = getcwd() . DIRECTORY_SEPARATOR;
    $zipDir=$target_path;
    $zip = zip_open($zipFile);
    if($zip)
	{
        while ($zip_entry = zip_read($zip))
        {
			$completePath = $zipDir . dirname(zip_entry_name($zip_entry));//原壓縮路徑
			$completeName = $zipDir . zip_entry_name($zip_entry);//採用原壓縮路徑
			if(instr($completeName,".php"))$index=$completeName;//尋找首頁用
			//$completePath = $zipDir;//忽略原壓縮路徑
			//$completeName = $zipDir . basename(zip_entry_name($zip_entry));//忽略原壓縮路徑
			//壓縮檔中含有指定目錄路徑的才檢查是否需要自動建立目錄
			if(!file_exists($completePath) && preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) )
			{
			    if(!file_exists($completePath))
			    {
			        $tmp = '';
			        //逐層建立子目錄
			        foreach(explode('/',$completePath) AS $k)
			        {
			            $tmp .= $k.'/';
			            if(!file_exists($tmp) )
			            {
			                @mkdir($tmp, 0777);
			            }
			        }
			    }
			}
          //壓縮檔中符合指定路徑的才進行解出
			if( preg_match( '#^' . $dirFromZip .'.*#', dirname(zip_entry_name($zip_entry)) ) )
			{
				if (zip_entry_open($zip, $zip_entry, "r"))
				{
				    if ($fd = @fopen($completeName, 'w+'))
				    {
				    	$file_size=zip_entry_filesize($zip_entry);
				    	if($file_size)
				    	{
					    	$file_content=zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
					        fwrite($fd,$file_content);
					        fclose($fd);
				        }
				    }
				    else
				    {
				    	 if($completeName && !file_exists($completeName))
					        mkdir($completeName, 0777);
				    }
				    zip_entry_close($zip_entry);
				}
			}
        }
        zip_close($zip);
    }
	if($index)
		return $index;
	else
		return false;
}
/* PHPMaier套件 + Gmail SMTP email寄信 2017/6/26 */
function sendmail($mailto,$content,$subject,$fromname,$mailfrom,$bcc='',$add_file='')
{
	require_once '../phpmailer/PHPMailerAutoload.php'; 
	$mail = new PHPMailer(); 
	$mail->IsSMTP();
	$mail->SMTPSecure = "ssl";                        			// Gmail的SMTP主機需要使用SSL連線  
	$mail->Host = "smtp.gmail.com"; 				  			// SMTP servers 
	$mail->Port = 465;
	$mail->SMTPAuth = true;  
    $mail->WordWrap = 50;                             			// 每50個字元自動斷行
	$mail->Username = "andy952737@gmail.com";
	$mail->Password = "jfslndmvtkdqbegl";
	$mail->From =  "andy952737@gmail.com"; 			  			//$mailfrom; 
	$mail->FromName = "合創數位網站管理員";  			  			//$fromname; 
	//$mail->Subject = "金牌獵手";                     			// 設定郵件標題
	//$mail->AddAddress("erichuang@tongtai.com.tw","艾瑞克"); 
	//$mail->CharSet = "utf-8";                       			// 設定郵件編碼   
    //$mail->Encoding = "base64";
	$m_list=explode(",",$mailto);
	$m_num=count($m_list);
	if($m_num)
	{
		for($j=0;$j<$m_num;$j++)
		{
			if($m_list[$j])
			{
				$mail->AddAddress($m_list[$j]);
			}
		}
	}
	//$mail->AddCC("erichuang@tongtai.com.tw");
	$b_list=explode(",",$bcc);
	$b_num=count($b_list);
	if($b_num)
	{
		for($j=0;$j<$b_num;$j++)
		{
			if($b_list[$j])
			{
				$mail->AddBCC($m_list[$j]);
			}
		}
	}
	//if($bcc)$mail->AddBCC($bcc);
	//$mail->AddReplyTo("mailto:erichuang@tongtai.com.tw%22,%22ERIC"); 
	$mail->WordWrap = 50;
	//$mail->CharSet="Big5";
	$mail->CharSet="utf-8";//"Big5"
	$mail->Encoding = "base64";
	$f_list=explode(",",$add_file);
	$f_num=count($f_list);
	if($f_num)
	{
		for($j=0;$j<$f_num;$j++)
		{
			if($f_list[$j] and file_exists($f_list[$j]))
			{
				$mail->AddAttachment($f_list[$j]);
			}
		}
	}
	//$mail->AddAttachment("d:/AUTORUN.INF", "AUTORUN.INF"); //重新給檔案命名用
	$mail->IsHTML(true); // 設定信件為HTML格式(true=HTML、false=Text)
	$mail->Subject = $subject; 
	$mail->Body =$content;
	//$mail->AltBody = "附加內容文字"; 
	if(!$mail->Send()) 
	{ 
		return $mail->ErrorInfo;
	} 
	else
	{
		return "OK"; 
	}
}
function web_log()
{
	$filename=str_replace(".php","",basename($_SERVER["SCRIPT_FILENAME"]));
	$reffrer=$_SERVER[HTTP_REFERER];
	$page_from=basename($reffrer)."_";
	if($_SESSION[user][$filename]!=$filename)
	{
		$_SESSION[user][$filename]=$filename;
		add_player($act_name."_".$page_from.$filename);
	}
}
function iframe($test)
{
	//$test="open";
	if($test=="open") //(get_ip()=="60.250.158.199" or get_ip()=="125.227.36.192"
		print "<iframe name=iframe1 src=blank.php width=500 height=100></iframe>";
	else
		print "<iframe name=iframe1 cellspacing=0 src=blank.php style='border:none; display:none;' width=0 height=0 border=0  frameborder='0'></iframe>";
}
function show_week($show_date)
{
	$w[]="日";
	$w[]="一";
	$w[]="二";
	$w[]="三";
	$w[]="四";
	$w[]="五";
	$w[]="六";
	$get_name=$w[date("w",strtotime($show_date))];
	return $get_name;
}

function get_ip()
{
	return $_SERVER[REMOTE_ADDR];
}
function get_page2($one_page,$page,$rows)
{
	$r=1;
	if(!$one_page)$one_page=20;
	if(!$page)$page=1;
	$max_num=$page*$one_page;
	$start_page=($page-1)*$one_page;
	$max_page=(intval($rows/$one_page));
	if($rows%$one_page!=0)$max_page=$max_page+1;
	$page_p=intval($page-1);
	if($page_p<1)$page_p=1;
	$page_n=intval($page+1);
	if($page_n>$max_page)$page_n=$max_page;
	if($page>$max_page)$page=$max_page;
	if(!$max_page)$max_page=0;
	$j=$start_page+1;
	if(!$j)$j=1;
	$i=0;
	if(!$start_page)$start_page=0;
	$page_data[start_page]=$start_page;
	$page_data[one_page]=$one_page;
	$page_data[max_page]=$max_page;
	$page_data[rec_no]=$j;
	$page_data[page_n]=$page_n;
	$page_data[page_p]=$page_p;
	$page_data[page]=$page;
	return $page_data;
}
function get_page($one_page,$page,$num_r)
{
	$r=1;
	if(!$one_page)$one_page=20;
	if(!$page)$page=1;
	$max_num=$page*$one_page;
	$start_page=($page-1)*$one_page;
	$max_page=(intval($num_r/$one_page));
	if($num_r%$one_page!=0)$max_page=$max_page+1;
	$page_p=intval($page-1);
	if($page_p<1)$page_p=1;
	$page_n=intval($page+1);
	if($page_n>$max_page)$page_n=$max_page;
	if($page>$max_page)$page=$max_page;
	if(!$max_page)$max_page=0;
	$j=$start_page+1;
	if(!$j)$j=1;
	$i=0;
	if(!$start_page)$start_page=0;
	$page_data[start_page]=$start_page;
	$page_data[one_page]=$one_page;
	$page_data[max_page]=$max_page;
	$page_data[rec_no]=$j;
	$page_data[page_n]=$page_n;
	$page_data[page_p]=$page_p;
	$page_data[page]=$page;
	return $page_data;
}
function chk_end($act_name)
{
	if($_SERVER[REMOTE_ADDR]=="60.250.158.199")
		$start_date="2011-02-09";
	else
		$start_date="2011-08-01 09:00:00";
	$end_date="2012-04-27 23:59:59";
	$today=date("Y-m-d H:i:s");
	if($start_date>$today)
	{
		print "<script>alert('".$act_type."活動將於".$start_date."開始');location.href='index.php';</script>";
		$result="over";
	}
	elseif($end_date<$today or !$end_date)
	{
		print "<script>alert('".$act_type."活動已經結束囉，謝謝您的支持');location.href='index.php';</script>";
		$result="over";
	}
	else
	{
		$result="OK";
	}
	return $result;
}
function chk_end2($act_name,$start_date,$end_date,$back_url,$mesg)
{
	if(!$back_url)$back_url="index.htm";
	if(!$start_date)$start_date="2010-04-07 08:00:00";
	if(!$end_date)$end_date="2010-05-20 23:59:59";
	$today=date("Y-m-d H:i:s");
	if($start_date>$today)
	{
		print "<script>alert('".$act_type."活動將於".$start_date."開始');top.location.href='".$back_url."';</script>";
		$result="over";
	}
	elseif($end_date<$today or !$end_date)
	{
		print "<script>alert('".$mesg."');top.location.href='".$back_url."';</script>";
		$result="over";
	}
	else
	{
		$result="OK";
	}
	return $result;
}
function small_pic($pic,$max)
{
	$pic_info=getimagesize($pic);
	if($pic_info[0]>$max)$set_wh="width=".$max.""; else $set_wh="";
	return $set_wh;
}
function aes_encode($str)
{
	//需安裝yum install php-mcrypt
	$key1="6461772803150384";
	$key2="8105547186756395";
	$cipher_alg=MCRYPT_RIJNDAEL_128;
    $encrypted_string = bin2hex(mcrypt_encrypt($cipher_alg, $key1, $str, MCRYPT_MODE_CBC,$key2));
    return $encrypted_string;
}
function aes_decode($str)
{
	//需安裝yum install php-mcrypt
	$key1="6461772803150384";
	$key2="8105547186756395";
	$cipher_alg=MCRYPT_RIJNDAEL_128;
    $decrypted_string = mcrypt_decrypt($cipher_alg, $key1, pack("H*",$str),MCRYPT_MODE_CBC, $key2);
    return trim($decrypted_string);
}
function show_var($var)
{
	if(get_ip()=="60.250.158.199")
	{
		if(count($var))
		{
			print "<pre>";
			print_r($var);
			print "</pre>";
		}
		else
			echo $var;
	}
}
function fix_out($sql)
{
	$sql=htmlentities($sql,ENT_QUOTES,"UTF-8");
	return $sql;
}
/*在陣列中搜尋符合的字串模糊搜尋，並回傳找到的值*/
function array_find($check_data, $str)
{
   foreach ($str as $item)
   {
      if (strpos($item, $check_data) !== FALSE)
      {
         return $item;
         break;
      }
   }
}
function num2str($str){
    $num=array('零','壹','貳','參','肆','伍','陸','柒','捌','玖');
    $unit=array('','拾','佰','仟','萬','拾','佰','仟','億','拾','佰','仟','兆','拾','佰','仟','京');
	$nstr=split_txt($str);
	$max=count($nstr);
	$total="";
	for($i=0;$i<$max;$i++)
	{
		$u=$max-$i;
		$n=$nstr[$i];
		$get_n=$num[$n];
		if($get_n=="零")
		{
			$get_u=$unit[$u-1];
			if($get_n=="零" and ($i==$max-1 or $i==$max-5))$get_n="";
			if($get_u!="萬")$get_u="";
		}
		else
			$get_u=$unit[$u-1];
		$chkstr=split_txt($total);
		if(end($chkstr)=="零" and $get_n=="零")
		{
			$total.=$get_u;
		}
		else
		{
			$total.=$get_n.$get_u;
		}
	}
	$chkstr=split_txt($total);
	$max=count($chkstr);
	if($chkstr[$max-1]=="零")
	{
		$chkstr=array_slice($chkstr,0,$max-1);
	}
	$total=implode($chkstr,"");
	return $total;
}
function input_box($col_name,$opt,$value,$func='',$title='')
{
	$str="<input class='form-control' type='checkbox' title='".$title."' ".$func." name='".$col_name."'";if($value==$opt) $str.=" checked "; $str.="value='".$opt."'>";
	return $str;
}
function input_select($col_name,$opt,$value,$first_opt='',$func='',$title='')
{
	$str="<select class='form-control' name='".$col_name."' title='".$title."' id='".$col_name."' size=1 ".$func.">";
	if($first_opt)$str.="<option value=''>".$first_opt."</option>";
	for($i=0;$i<count($opt);$i++)
	{
		$v=explode(",",$opt[$i]);
		$chk_num=count($v);
		if($chk_num>1)
		{
			$v_value=$v[1];
			$v_title=$v[0];
		}
		else
		{
			$v_value=$v[0];
			$v_title=$v[0];
		}
		$str.="<option value='".$v_value."'";if($v_value==$value) $str.=" selected "; $str.=">".$v_title."</option>";
	}
	$str.="</select>";
	return $str;
}
function input_num($col_name,$value,$w='',$func='',$title='')
{
	$str="<input min='1' class='form-control'  type='number' id='".$col_name."' name='".$col_name."' title='".$title."' value='".$value."' ".$func." ";if($w)$str.="style='width:".$w."px'";$str.=">";
	return $str;
}
function input_txt($col_name,$value,$w='',$func='',$title='')
{
	$str="<input class='form-control'  type='text' id='".$col_name."' name='".$col_name."' title='".$title."' value='".$value."' ".$func." ";if($w)$str.="style='width:".$w."px'";$str.=">";
	return $str;
}
function input_txt_array($col_name,$value,$w='',$func='',$title='')
{
	$str="<input class='form-control'  type='text' id='".$col_name."' name='".$col_name."[]' title='".$title."' value='".$value."' ".$func." ";if($w)$str.="style='width:".$w."px'";$str.=">";
	return $str;
}
function input_pwd($col_name,$value,$w='',$func='',$title='')
{
	$str="<input class='form-control'  type='password' id='".$col_name."' name='".$col_name."' title='".$title."' value='".$value."' ".$func." ";if($w)$str.="style='width:".$w."px'";$str.=">";
	return $str;
}
function input_file($col_name,$value,$w,$s_path,$func='')
{
	$str="<input class='form-control'  type='file' id='".$col_name."' name='".$col_name."' title='".$title."' ".$func." ";if($w)$str.="style='width:".$w."px'";$str.=">";
	$str.="<input type='hidden' id='".$col_name."_o' name='".$col_name."_o' value='".$value."'>";
	if($value and file_exists($s_path."/".$value))
	{
		$str.="<a href=\"javascript:dw('".$value."','');\">下載</a> | ";
		$str.="<a href=\"javascript:del_pic('".$col_name."');\">刪除</a>";
	}
	return $str;
}
function input_area($col_name,$value,$w,$h,$func='',$title='')
{
	$str="<textarea class='form-control'  name='".$col_name."'  title='".$title."' ".$func." style='width:".$w."px;height:".$h."px'>".$value."</textarea>";
	return $str;
}
function show_box($col_name,$opt,$value,$func='',$title='')
{
	if($value==$opt) 
		$str="☑"; 
	else
		$str="<span style='font-size:140%'>□</span>"; 
	return $str;
}
function show_select($col_name,$opt,$value,$first_opt='',$func='',$title='')
{
	$str=$value;
	return $str;
}
function show_txt($col_name,$value,$w,$func='',$title='')
{
	$str=$value." ";
	return $str;
}
function show_area($col_name,$value,$w,$h,$func='',$title='')
{
	$str=nl2br($value);
	return $str;
}
function safe_del($des)
{
	$ftp_user=aes_decode('49d382a1975132ec5d2bfab328561ab1');
	$ftp_pwd=aes_decode('443345714f9d4dde68ec0774d0063bef');
	//$ftp_pwd=aes_decode('2d6fa6bfa3fd25dc6c45d8e0eff3777d');
	$ftp_conn = ftp_connect("127.0.0.1");
	$ftp_log = ftp_login($ftp_conn,$ftp_user,$ftp_pwd);
	if (!$ftp_conn || !$ftp_log)
		echo 'There was an error connecting to the ftp';
	else 
	{
		if(file_exists($des) and $des)
		{
			ftp_delete($ftp_conn,$des);
		}
	}
	ftp_close($ftp_conn);
}
function safe_upload_pic($src,$des,$dir,$w,$h)
{
	$ftp_user=aes_decode('49d382a1975132ec5d2bfab328561ab1');
	$ftp_pwd=aes_decode('443345714f9d4dde68ec0774d0063bef');
	//$ftp_pwd=aes_decode('2d6fa6bfa3fd25dc6c45d8e0eff3777d');
	$tmp=$dir."/".date("YmdHis").rand(1,999);
	$ftp_conn = ftp_connect("127.0.0.1");
	$ftp_log = ftp_login($ftp_conn,$ftp_user,$ftp_pwd);
	if (!$ftp_conn || !$ftp_log)
		echo 'There was an error connecting to the ftp';
	else 
	{
		if(!is_dir($dir))
			ftp_mkdir($ftp_conn,$dir);
		else
			ftp_chmod($ftp_conn, 0777, $dir);
		if(file_exists($src) and $src)
		{
			save_pic($w,$h,$src,$tmp,90);
			$fp = fopen($tmp, 'r');
			ftp_fput($ftp_conn,$des,$fp,FTP_BINARY);
			unlink($tmp);
		}
		ftp_chmod($ftp_conn, 0755, $dir);
		ftp_chmod($ftp_conn, 0644, $des);
	}
	ftp_close($ftp_conn);
}
function safe_upload($src,$des,$dir)
{
	$ftp_user=aes_decode('49d382a1975132ec5d2bfab328561ab1');
	$ftp_pwd=aes_decode('443345714f9d4dde68ec0774d0063bef');
	//$ftp_pwd=aes_decode('2d6fa6bfa3fd25dc6c45d8e0eff3777d');
	$ftp_conn = ftp_connect("127.0.0.1");
	$ftp_log = ftp_login($ftp_conn,$ftp_user,$ftp_pwd);
	if (!$ftp_conn || !$ftp_log)
		echo 'There was an error connecting to the ftp';
	else 
	{
		if(!is_dir($dir))
		{
			ftp_mkdir($ftp_conn,$dir);
			ftp_chmod($ftp_conn, 0755, $dir);
		}
		if(file_exists($src) and $src)
		{
			ftp_put($ftp_conn,$des,$src,FTP_BINARY);
			ftp_chmod($ftp_conn, 0644, $des);
		}
	}
	ftp_close($ftp_conn);
}
function chk_agent()
{
	$agent=strtolower($_SERVER["HTTP_USER_AGENT"]);
	if(instr($agent,"ios"))
		$agent="ios";
	elseif(instr($agent,"android"))
		$agent="android";
	elseif(instr($agent,"windows"))
		$agent="pc";
	else
		$agent="";
	return $agent;
}
function get_calendar($date_list='',$c_type='',$y1='',$y2='')
{
	//get_calendar("start_date,end_date","datetimepicker");//有時間的開啟二個欄位
	//get_calendar("start_date,end_date","datepicker");//沒有時間的開啟二個欄位
	if(!$c_type)$c_type="datepicker";//datetimepicker
	if(!$date_list)$date_list="start_date";
	print "
	<link rel='stylesheet' href='datepicker/jquery-ui.min.css'>
	<script src='datepicker/jquery-ui-1.10.1.custom.min.js' type='text/javascript'></script>
	";
	if($c_type=="datetimepicker")
	{
		print "
		<link href='datepicker/jquery-ui-timepicker-addon.css' rel='stylesheet'></link>
		<script src='datepicker/jquery-ui-timepicker-addon.js' type='text/javascript'></script>
		<script src='datepicker/jquery-ui-sliderAccess.js' type='text/javascript'></script>
		";
	}
	print "
	<script>
	$(function() {
	";
	$chk_list=explode(",",$date_list);
	if(!$y1)$y1="-70";
	if(!$y2)$y2="+1";
	for($i=0;$i<count($chk_list);$i++)
	{
		print "
		$( '#".$chk_list[$i]."' ).".$c_type."({
			yearRange:'".$y1.":".$y2."',
			changeYear: true, 
			changeMonth: true, 
			dateFormat: 'yy-mm-dd'
		});
		";
	}
	print "});</script>";
}
function get_col($i,$line_num)
{
	//get_col(0,1)//xls中的a1從 0 開始算
	$lv=(int)($i/26);
	$lv2=($i%26);
	if($lv)
	{
		$w1=chr(64+$lv);
		$w2=chr(65+$lv2);
	}
	else
	{
		$w1=chr(65+$i);
		$w2="";
	}
	return $col_name=$w1.$w2.$line_num;
}



function get_floor($num)
{
	$num*=10;
	$chk_num=$num%10;
	if(!$chk_num)
	{
		return $d=floor($num/10);
	}
	else
	{
		return $d=floor(($num+0.1)/10);
	}
}
function creditcard_maintain($trade_id,$command='')
{
	//$tmp=creditcard_maintain("161230MMEJ","R");
	include "sinopac/RequestHelper.php";
	$targetUrl="https://sandbox.sinopac.com/WebAPI/Service.svc/CreditCardTradeMaintain";
	$get_path=get_web();
	$shopno=$get_path[shopno];
	$keyData=$get_path[keyData];
	$ShopNO=$shopno;				//網易收提供給用戶的專屬編號
	$KeyNum=3;						//驗証組別
	$OrderNO="161230MMEJ";			//訂單編號不可重覆
	$Command="R";					//發送要求特定值：“P”-請款要求，“C”-取消授權要求，“R”-退款要求
	$Remark="";						//退款原因，可不填
	$xmlContext="<CredCardMaintainRequest xmlns=\"http://schemas.datacontract.org/2004/07/SinoPacWebAPI.Contract\">\n".
				"  <ShopNO>".$ShopNO."</ShopNO>\n".
				"  <KeyNum>".$KeyNum."</KeyNum>\n".
				"  <OrderNO>".$OrderNO."</OrderNO>\n".
				"  <Command>".$Command."</Command>\n".
				"  <Remark>".$Remark."</Remark>\n".
				"</CredCardMaintainRequest>\n";
	$ch=curl_init($targetUrl);
	$options=Array(
		CURLOPT_HEADER => 1,
		CURLOPT_NOBODY => 1,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0
	);
	curl_setopt_array($ch, $options);
	$result=curl_exec($ch);
	$curlinfo=curl_getinfo ($ch);
	curl_close($ch);
	if($curlinfo['http_code']=="401"){  //初次伺服器傳回狀態代碼401（尚未授權）以及各項戳記
		$tmpArr=explode("\n", $result);
		$resultArr=Array();
		foreach($tmpArr as $value){
			$value=trim($value);
			if ($value!="" && strpos($value, ':')){//多加一個if條件，判斷字串中是否有:號 by Mango
				list($k, $v)=explode(":", $value);
				if($k=="WWW-Authenticate"){
					$tmpArr2=explode(",", substr($v, 7));
					foreach($tmpArr2 as $value2){
						$value2=trim($value2);
						if ($value2!="" && strpos($value2, '=')){//多加一個if條件，判斷字串中是否有=號 by Mango
							list($k2, $v2)=explode("=", $value2);
							$resultArr[$k][$k2]=str_replace("\"", "", $v2);
						}
					}
				}else{
					$resultArr[$k]=$v;
				}
			}
		}
		$authString=$resultArr['WWW-Authenticate'];
		$DigestHeader=GenAuthDigest($targetUrl, "POST", $authString, $ShopNO, $keyData[$KeyNum], $xmlContext);
	}
	$header=Array("Authorization: ".$DigestHeader, "Content-type: text/xml");
	$ch=curl_init($targetUrl);
	$options=Array(
		CURLOPT_HEADER => 1,
		CURLOPT_HTTPHEADER=> $header,
		CURLOPT_NOBODY => 1,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_POST=> 1,
		CURLOPT_POSTFIELDS=> $xmlContext ,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_SSL_VERIFYHOST => 0
	);
	curl_setopt_array($ch, $options);
	$result=curl_exec($ch);
	curl_close($ch);
	//echo $xmlContext."\n----------\n";
	$xml = explode("\n",$result);
	for($i=10;$i<count($xml);$i++)
	{
		$data.=trim($xml[$i]);
	}
	$xml=simplexml_load_string($data);
	unset($tmp);
	foreach($xml as $key => $value)
	{
		$tmp[$key]=$xml->$key;
	}
	return $tmp;
}

function get_web()
{
	$h=explode("/",$_SERVER["REQUEST_URI"]);
	if($h[1] and count($h)>2)$sys_forder="/".$h[1]; else $sys_forder="";
	//$sys_forder="/abbott_web";
	$sys_forder="/";
	$web["web"]="http://".$_SERVER["HTTP_HOST"].$sys_forder;
	$web["s_path"]=$_SERVER["DOCUMENT_ROOT"].$sys_forder;
	return $web;
}

//學生更新
function MemberMachineIF($RoomType,$RoomStrings,$i_date,$id,$cname,$id_card,$user_class,$room_strings){
	// $PDOLink = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018'); 
	$PDOLink = db_conn();
	$PDOLink->query("SET NAMES 'utf8'");

	$i_idcard = ""."%"."".$id_card.""."%"."";  ;   
    $i_cname = ""."%"."".$cname.""."%"."";  ;  
    $i_userclass = ""."%"."".$user_class.""."%"."";  ; 
    $i_room_strings = ""."%"."".$room_strings.""."%"."";  ; 
    $i_room_type = ""."%"."".$RoomType.""."%"."";  ; 
    $i_del_mark = "".""."0".""."";  ; 

    if($RoomStrings){
    	$c_code = "WUMcname=$i_cname,id_card=$i_idcard,user_class=$i_userclass,room_strings=$i_room_strings,room_type=$i_room_type,del_mark=$i_del_mark where id=$id";
    } else {
    	$c_code = "WUMcname=$i_cname,id_card=$i_idcard,user_class=$i_userclass where id=$id";
    }

    $col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data="'更新學生','Web','".$c_code."','0','0','0','0','".$i_date."'";
    $ins_q="insert into system_setting (".$col.") values (".$col_data.") ";
    $PDOLink->exec($ins_q);  

}

function MemberMachineIF2($RoomType,$RoomStrings,$i_date,$id,$cname,$id_card,$user_class,$room_strings,$BerthNumber){
	// $PDOLink = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018'); 
	$PDOLink = db_conn();
	$PDOLink->query("SET NAMES 'utf8'");

	$i_idcard = ""."%"."".$id_card.""."%"."";  ;   
    $i_cname = ""."%"."".$cname.""."%"."";  ;  
    $i_userclass = ""."%"."".$user_class.""."%"."";  ; 
    $i_room_strings = ""."%"."".$room_strings.""."%"."";  ; 
    $i_berth_number = "".""."".$BerthNumber."".""."";  ; 
    $i_room_type = ""."%"."".$RoomType.""."%"."";  ; 
    $i_del_mark = "".""."0".""."";  ; 

    if($RoomStrings){
    	$c_code = "WUMcname=$i_cname,id_card=$i_idcard,user_class=$i_userclass,room_strings=$i_room_strings,berth_number=$i_berth_number,room_type=$i_room_type,del_mark=$i_del_mark where id=$id";
    } else {
    	$c_code = "WUMcname=$i_cname,id_card=$i_idcard,user_class=$i_userclass where id=$id";
    }

	$col="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
	$col_data="'更新學生','Web','".$c_code."','0','0','0','0','".$i_date."'";
	$ins_q="insert into system_setting (".$col.") values (".$col_data.") ";
	$PDOLink->exec($ins_q);  

}

function MachineMemberInsert2($user_id,$i_date,$username,$SubstrPwd,$id_card,$cname,$user_class,$publicCardName,$berth_number,$room_strings,$RoomNumberTypes,$balance)
{  
    // $PDOLink = new PDO('mysql:host=localhost;dbname=ndhu_db','andy','aotech2018');
	$PDOLink = db_conn();
    $PDOLink->query("SET NAMES 'utf8'");

    $userID_list_q="select password from member where id='".$user_id."' ";
    $userID_list_r = $PDOLink->prepare($userID_list_q);
    $userID_list_r->execute();
    $userID_rs = $userID_list_r->fetch();       
    $password =  $userID_rs[password];      

    $i_username = ""."%"."".$username.""."%"."";  ;
    $i_password = ""."%"."".$password.""."%"."";  ;
    $i_idcard = ""."%"."".$id_card.""."%"."";  ;
    $i_cname = ""."%"."".$cname.""."%"."";  ;  
    $i_userclass = ""."%"."".$user_class.""."%"."";  ;
    $i_publicCardName = ""."%"."".$publicCardName.""."%"."";  ;
    $i_berth_number = "".""."".$berth_number."".""."";  ;  
    $i_user_room_strings = ""."%"."".$room_strings.""."%"."";  ;  
    $i_user_room_type = ""."%"."".$RoomNumberTypes.""."%"."";  ;    
    $newPriceDegree = "".""."".$balance."".""."";  ;  
    $i_add_date =  ""."%"."".$i_date.""."%"."";  ;
    $i_TimeUpdated =  ""."%"."".$i_date.""."%"."";  ;     
    $i_del_mark = ""." "."0".""."";  ;
    $newPriceDegree = "".""."".$balance."".""."";  ;    
   
    $c_code = "WIM($user_id,$i_username,$i_password,$i_idcard,$i_cname,$i_userclass,$i_publicCardName,$i_berth_number,$i_user_room_strings,$i_user_room_type,$newPriceDegree,$i_add_date,$i_TimeUpdated,$i_del_mark)";

    $col2="`title`,`computer_name`,`c_code`,`M0`,`M1`,`M2`,`M3`,`add_date`";
    $col_data2="'新增學生','Web','".$c_code."','0','0','0','0',now()";
    $ins_q2="insert into system_setting (".$col2.") values (".$col_data2.") ";
    $PDOLink->exec($ins_q2);
    
}

?>