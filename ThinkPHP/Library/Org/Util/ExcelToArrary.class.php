<?php
namespace Org\Util;
class ExcelToArrary{
       public function __construct() {
               import('Org.Util.PHPExcel');     
       }
      // public function cc(){
      //    $excel=new \PHPExcel();;
      // }
    /* 导出excel函数*/
    public function push1($data,$name='Excel'){
         error_reporting(E_ALL);
         date_default_timezone_set('Europe/London');
         $objPHPExcel = new \PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
         $objPHPExcel->getProperties()->setCreator("ed")
                               ->setLastModifiedBy("ed")
                               ->setTitle("数据EXCEL导出")
                               ->setSubject("数据EXCEL导出")
                               ->setDescription("备份数据")
                               ->setKeywords("excel")
                               ->setCategory("result file");
         /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
         $objPHPExcel->setActiveSheetIndex(0)  
            ->setCellValue('A1','ID')  
            ->setCellValue('B1','昵称')  
            ->setCellValue('C1','登录方式')  
            ->setCellValue('D1','平台')  
            ->setCellValue('E1','性别')  
            ->setCellValue('F1','创建时间')  
            ->setCellValue('G1','最后登录时间')  
            ->setCellValue('H1','金币')  
            ->setCellValue('I1','钻石')  
            ->setCellValue('J1','福卡')  
            ->setCellValue('K1','兑换券')  
            ->setCellValue('L1','在线时长')  
            ->setCellValue('M1','游戏局数')  
            ->setCellValue('N1','初级场')  
            ->setCellValue('O1','中级场')  
            ->setCellValue('P1','高级场');  
        foreach($data as $k => $v){
             $num=$k+1;
             $objPHPExcel->setActiveSheetIndex(0)
                         //Excel的第A列，uid是你查出数组的键值，下面以此类推
                          ->setCellValue('A'.$num, $v['userid'])    
                          ->setCellValue('B'.$num, $v['nickname'])
                          ->setCellValue('C'.$num, $v['channel'])
                          ->setCellValue('D'.$num, $v['platform'])
                          ->setCellValue('E'.$num, $v['sex'])
                          ->setCellValue('F'.$num, $v['create_time'])
                          ->setCellValue('G'.$num, $v['login_time'])
                          ->setCellValue('H'.$num, $v['gold'])
                          ->setCellValue('I'.$num, $v['diamond'])
                          ->setCellValue('J'.$num, $v['luck_tick'])
                          ->setCellValue('K'.$num, $v['convert_tick'])
                          ->setCellValue('L'.$num, '')
                          ->setCellValue('L'.$num, $v['rall'])
                          ->setCellValue('M'.$num, $v['r1'])
                          ->setCellValue('N'.$num, $v['r2'])
                          ->setCellValue('N'.$num, $v['r3']);
            }
             $objPHPExcel->getActiveSheet()->setTitle('game');
             // $objPHPExcel->setActiveSheetIndex(0);
             // header('Content-Type: application/vnd.ms-excel');
             // header('Content-Disposition: attachment;filename="'.$name.'.xls"');
             // header('Cache-Control: max-age=0');
             $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
             var_dump($objPHPExcel);
             exit();
             $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
             $filePath = '/tmp/'.$filename.'.xlsx';  
             $url=$objWriter->save($filePath); 
             $response = array(  
                 'success' => true,  
                 'url' => $url
             );  
             return $response;
             // $objWriter->save('php://output');
             exit;
      }
    }