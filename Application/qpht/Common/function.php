<?php
/*验证码*/
function check_verify($code, $id = ""){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string 
 */
function think_ucenter_md5($str, $key = 'ThinkUCenter'){
	return '' === $str ? '' : md5(sha1($str) . $key);
}

function getYesterdar(){
	$now=time();
	$yesterday=getdate($now)['mday']-1;
	$year=getdate($now)['year'];
	$month=getdate($now)['mon'];
	if($yesterday<10){
		$yesterday='0'.$yesterday;
	}
	if($month<10){
		$month='0'.$month;
	}
	return $year.'-'.$month.'-'.$yesterday;
}

function formatDay1($str){
	$temp=explode('-', $str);
	if($temp[1]<10){
		$temp[1]='0'.$temp[1];
	}
	if($temp[2]<10){
		$temp[2]='0'.$temp[2];	
	}
	return $temp[0].'-'.$temp[1].'-'.$temp[2]; 
}

function dayConditionFormat($dayarr){
	$res=array();
	$res[0]=formatDay1($dayarr[0]);
	$res[1]=date("Y-m-d",strtotime($dayarr[count($dayarr)-1]." -1 day"));
	//$res[1]=$dayarr[count($dayarr)-1];
	return $res;
}

//两个时间相差的天数
function diffBetweenTwoDays ($day1, $day2)
{
  $second1 = strtotime($day1);
  $second2 = strtotime($day2);
    
  if ($second1 < $second2) {
    $tmp = $second2;
    $second2 = $second1;
    $second1 = $tmp;
  }
  return ($second1 - $second2) / 86400;
}
function exportToExcel($filename, $tileArray=[], $dataArray=[]){  
    ini_set('memory_limit','512M');  
    ini_set('max_execution_time',0);  
    ob_end_clean();  
    ob_start();  
    header("Content-Type: text/csv");  
    header("Content-Disposition:filename=".$filename);  
    $fp=fopen('php://output','w');  
    fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));//转码 防止乱码(比如微信昵称(乱七八糟的))  
    fputcsv($fp,$tileArray);  
    $index = 0;  
    foreach ($dataArray as $item) {  
        if($index==1000){  
            $index=0;  
            ob_flush();  
            flush();  
        }  
        $index++;  
        fputcsv($fp,$item);  
    }  

    ob_flush();  
    flush();  
    ob_end_clean();  
}  