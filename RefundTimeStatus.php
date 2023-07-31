<?php 

    /* 長度計算： 一個 13

      二排程
      26:
      27: ^
      28: ^^

      三排程
      39:
      40: ^
      41: ^^
      42: ^^^

    */

	switch ($Counts){ 

		     case '26':
		         $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
		         $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];  
		         $a_count = strlen($a);                                 //6
		         $c = $arr[14].$arr[15].$arr[16].$arr[17].$arr[18]; 
		         $d = $arr[20].$arr[21].$arr[22].$arr[23].$arr[24]; 
		         $b_count = strlen($c);                                 //6
		         $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}"; 
		         break;

		     case '27':
		          $r1 = $arr[1];
		          $r2 = $arr[14];

		          if($r1 === "^"){
		            $a = $arr[2].$arr[3].$arr[4].$arr[5].$arr[6];                           
		            $b = $arr[8].$arr[9].$arr[10].$arr[11].$arr[12];  
		            $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19]; 
		            $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];
		          } elseif($r2 === "^") {
		            $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
		            $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];
		            $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19]; 
		            $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];
		          } else {
		            $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
		            $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];  
		            $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19]; 
		            $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];
		          }

		          $a_count = strlen($a);                                 //6
		          $b_count = strlen($c);                                 //6 
		          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}"; 
		         break;

		     case '28':
		          $r1 = $arr[1];
		          $r2 = $arr[15];   

		          $a = $arr[2].$arr[3].$arr[4].$arr[5].$arr[6];                           
		          $b = $arr[8].$arr[9].$arr[10].$arr[11].$arr[12];  
		          $a_count = strlen($a);          
		          
		          $c = $arr[16].$arr[17].$arr[18].$arr[19].$arr[20]; 
		          $d = $arr[22].$arr[23].$arr[24].$arr[25].$arr[26];
		          $b_count = strlen($b);                                 //6 
		          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}"; 
		         break;
				 
			// 20200115 -- 改寫
			case '39':
			case '40':
			case '41':
			case '42':

				$str_time = '';

				$r1 = ''; $r2 = ''; $r3 = '';
				
				$a; $b; $c; $d; $e; $f;
				
				foreach($arr as $v) {
					$str_time .= $v;
				}
				
				$tmp = explode('}', $str_time);
				
				// Array ( [0] => {^02:00~06:00 [1] => {^08:00~14:00 [2] => {15:00~21:00 [3] => )
				
				foreach($tmp as $k => $v) {
					
					if(!isset($v)) { continue; }
					
					$ttt = explode('{', $v)[1];
					$pos = strpos($ttt, '^');

					switch($k) {
						
						case 0:
						
							$r1 = ($pos === 0) ? "^" : '';
							$z  = explode('~', $ttt);
							$a  = str_replace('^', '', $z[0]);
							$b  = $z[1];

							break;
						case 1:
						
							$r2 = ($pos === 0) ? "^" : '';
							$z  = explode('~', $ttt);
							$c  = str_replace('^', '', $z[0]);
							$d  = $z[1];
							
							break;						
						case 2:
						
							$r3 = ($pos === 0) ? "^" : '';
							$z  = explode('~', $ttt);
							$e  = str_replace('^', '', $z[0]);
							$f  = $z[1];
							
							break;
						default:
							break;
					}
				}
				
				$FitTimeTotal = str_replace('^', '', $str_time);
				
				break;
				 
/*
		       case '39':
	             $r1 = ''; 
	             $r2 = '';
	             $r3 = '';
	          	 $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
			     $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];    
		         $c = $arr[14].$arr[15].$arr[16].$arr[17].$arr[18];                           
			     $d = $arr[20].$arr[21].$arr[22].$arr[23].$arr[24];  
		         $e = $arr[27].$arr[28].$arr[29].$arr[30].$arr[31];
			     $f = $arr[33].$arr[34].$arr[35].$arr[36].$arr[37];    
		         $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
		         break;

		        case '40':
		          $r1 = $arr[1];
		          $r2 = $arr[14];
		          $r3 = $arr[27];
		          	if( ($r1 == "^") ){
			          $a = $arr[2].$arr[3].$arr[4].$arr[5].$arr[6];                           
			          $b = $arr[8].$arr[9].$arr[10].$arr[11].$arr[12];  
			          $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19];                           
			          $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];  
			          $e = $arr[28].$arr[29].$arr[30].$arr[31].$arr[32];
			          $f = $arr[34].$arr[35].$arr[36].$arr[37].$arr[38];  
			          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
			        } elseif( ($r2 == "^") ) {
			          $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
			          $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];  
			          $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19];                           
			          $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];  
			          $e = $arr[28].$arr[29].$arr[30].$arr[31].$arr[32];
			          $f = $arr[34].$arr[35].$arr[36].$arr[37].$arr[38];  
			          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
			        } elseif( ($r3 == "^") ) {
			          $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
			          $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];  
			          $c = $arr[14].$arr[15].$arr[16].$arr[17].$arr[18];                           
			          $d = $arr[20].$arr[21].$arr[22].$arr[23].$arr[24];  
			          $e = $arr[28].$arr[29].$arr[30].$arr[31].$arr[32];                     
			          $f = $arr[34].$arr[35].$arr[36].$arr[37].$arr[38];
			          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
					}
		        break;

		        case '41':
		          $r1 = $arr[1];
		          $r2 = $arr[14];
		          //$r3 = $arr[14];	
		          $r3 = $arr[28];
		          if( ($r1 == "^") && ($r2 == "^") ){
			          $a = $arr[2].$arr[3].$arr[4].$arr[5].$arr[6];                           
			          $b = $arr[8].$arr[9].$arr[10].$arr[11].$arr[12];  
			          $c = $arr[16].$arr[17].$arr[18].$arr[19].$arr[20];                           
			          $d = $arr[22].$arr[23].$arr[24].$arr[25].$arr[26];  
			          $e = $arr[29].$arr[30].$arr[31].$arr[32].$arr[33];
			          $f = $arr[35].$arr[36].$arr[37].$arr[38].$arr[39]; 
			          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
			        } elseif( ($r2 == "^") && ($r3 == "^") ) {
			          $a = $arr[1].$arr[2].$arr[3].$arr[4].$arr[5];                           
			          $b = $arr[7].$arr[8].$arr[9].$arr[10].$arr[11];  
			          $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19];                           
			          $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];  
			          $e = $arr[29].$arr[30].$arr[31].$arr[32].$arr[33];
			          $f = $arr[35].$arr[36].$arr[37].$arr[38].$arr[39];  
			          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
			        } elseif( ($r1 == "^") && ($r3 == "^") ) {
			          $a = $arr[2].$arr[3].$arr[4].$arr[5].$arr[6];                           
			          $b = $arr[8].$arr[9].$arr[10].$arr[11].$arr[12];  
			          $c = $arr[15].$arr[16].$arr[17].$arr[18].$arr[19];                           
			          $d = $arr[21].$arr[22].$arr[23].$arr[24].$arr[25];   
			          $e = $arr[29].$arr[30].$arr[31].$arr[32].$arr[33];
			          $f = $arr[35].$arr[36].$arr[37].$arr[38].$arr[39];  
			          $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
					}
		        break;

		     	case '42':
		            $r1 = $arr[1];
		            $r2 = $arr[15];
		            $r3 = $arr[29];
		          	$a = $arr[2].$arr[3].$arr[4].$arr[5].$arr[6];                           
			        $b = $arr[8].$arr[9].$arr[10].$arr[11].$arr[12];  
			        $c = $arr[16].$arr[17].$arr[18].$arr[19].$arr[20];                           
			        $d = $arr[22].$arr[23].$arr[24].$arr[25].$arr[26];  
			        $e = $arr[30].$arr[31].$arr[32].$arr[33].$arr[34];                     
			        $f = $arr[36].$arr[37].$arr[38].$arr[39].$arr[40]; 
			        $FitTimeTotal = "{".$a."~".$b."}{".$c."~".$d."}{".$e."~".$f."}";
		        break;
*/

    }

?>