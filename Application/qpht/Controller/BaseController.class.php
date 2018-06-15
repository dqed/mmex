<?php
namespace qpht\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function jurCheck(){
        $tokenstr = $_COOKIE['token'];
        if($tokenstr==null){
            $this->success('请重新登录','/qpht.php/Publics/loginShow');
        }else{
            $userc=new UserController();
            $tokenarr=$userc->jwtdecode($tokenstr);
            $now=time();
            if($now-$tokenarr['stime']>C('TOKEN_OUT_TIME')){
                $this->success('状态超时，请重新登录','/qpht.php/Publics/loginShow');
            }else{
                $roles=$tokenarr['roleids'];
                $role=D('Role');
                $rolesarr=explode(',', $roles);
                $resarr['roleid']=$roles;
                $jurarr=array();
                for ($i=0; $i <count($rolesarr) ; $i++) {
                    $conds['id']=$rolesarr[$i];
                    $tempres=$role->where($conds)->getField('jurisdiction');
                    $temparr=explode(',', $tempres);
                    $jurarr=array_merge($jurarr, $temparr);
                }
                $resarr['user']=$tokenarr['user'];
                $resarr['jurarr']=$jurarr;
                return $resarr;
            }
        }
    }
	protected function getmenuInfo(){
        //菜单权限检测
        $jurmenu=$this->jurCheck();
        $username=$jurmenu['user'];
		    $module=D('Module');
        $menu=D('Menu');
        $gamemain=D('Gamemain');
        $gamemain->updatadb();//更新统计信息
        $mdurl=strtolower('/'.CONTROLLER_NAME.'/'.ACTION_NAME);
        $m1['menurl']=$mdurl;
        $menuid=$menu->where($m1)->getField('id');
        if($mdurl!='/index/index'){
          if($jurmenu['roleid']!='4'){
                if(!in_array($menuid, $jurmenu['jurarr'])){
                $this->success('暂无权限','/qpht.php/Index/index');
            } 
          } 
        }
        
            //获取模块信息
            $moduleinfo=$module->order('sortnum')->limit(10)->select();
            $moduleinfo1=array();
            for ($i=0; $i <count($moduleinfo) ; $i++) { 
                $moduleinfo1[$i]['id']=$moduleinfo[$i]['id'];
                $moduleinfo1[$i]['modulename']=$moduleinfo[$i]['modulename'];
            }
            
            //获取菜单信息 
            $menuinfo=$menu->limit(50)->select();
            $menuinfox=array();
            for ($x=0; $x <count($menuinfo) ; $x++) { 
                $mid=(string)$menuinfo[$x]['id'];
                if($jurmenu['roleid']=='4'){
                        array_push($menuinfox, $menuinfo[$x]);
                    }else{
                       if(in_array($mid, $jurmenu['jurarr'])){
                             array_push($menuinfox, $menuinfo[$x]);
                        } 
                    }  
            }
            $menuinfo1=array();
            for ($i=0; $i < count($moduleinfo1); $i++) {
                $temparray=array(); 
                for ($j=0; $j < count($menuinfox); $j++) { 
                    if($menuinfox[$j]['moduleid']==$moduleinfo1[$i]['id']){
                        array_push($temparray, $menuinfox[$j]);
                    }
                }
                $menuinfo1[$moduleinfo1[$i]['id']]=$temparray;
            }
            $mdname='';
            for ($i=0; $i < count($menuinfo); $i++) { 
                $menurl=strtolower($menuinfo[$i]['menurl']);
                if($menurl==$mdurl){
                    $mdname=$menuinfo[$i]['menuname'];
                    break;
                }
            }
            //获取游戏信息
            $gameinfo=D('Gameinfo');
            $gamedata=$gameinfo->where('state=1')->select();
            $actionname=CONTROLLER_NAME;
            $this->assign('modulename',MODULE_NAME);
            switch ($actionname) {
              case 'Index':
                $acid=2;
                break;
              case 'Gamemanage':
                $acid=2;
                break;
              case 'User':
                $acid=1;
                break;
              default:
                $acid=0;# code...
                break;
            }
            $this->assign('actionname',CONTROLLER_NAME );
            $this->assign('acid',$acid );
            $this->assign('actionname2',ACTION_NAME );
            $this->assign('username',$username);
            $this->assign('mdname',$mdname);
            $this->assign('moduleinfo1',$moduleinfo1);
            // var_dump($actionname);
            // var_dump($moduleinfo1);
            // exit();
            $this->assign('menuinfo1',$menuinfo1);
            $this->assign('gameinfo',$gamedata);
        }

        protected function exportExcel($expTitle,$expCellName,$expTableData){
           Vendor('PHPExcel.PHPExcel');
           $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
           $fileName = time();//导出excal 文件名称
           $cellNum = count($expCellName[0]);//有多少列
           $dataNum = count($expTableData);//有多少行
           Vendor('PHPExcel.PHPExcel');
           ini_set("memory_limit", "1024M");
           $objPHPExcel = new \PHPExcel();//实例化PHPExcel类库，相当于新建一个Excel表
           $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
                 'P','Q','R','S','T','U','V','W','X','Y','Z', 'AA','AB','AC','AD','AE',
                 'AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT',
                 'AU','AV','AW','AX','AY','AZ');
           //在第二行插入每列的标题
           for($i=0;$i<$cellNum;$i++){
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[0][$i]);
           }
           //从第三行开始插入数据
           for($i=0;$i<$dataNum;$i++){
              for($j=0;$j<$cellNum;$j++){
                 $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$j]);
              }
           }
           $objSheet = $objPHPExcel->getActiveSheet();//获取当前活动sheet
           $objSheet->setTitle('sheet1');//给当前的活动sheet起个名称
           header('pragma:public');
           header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xlsx"');
           header("Content-Disposition:attachment;filename=$fileName.xlsx");//attachment新窗口打印inline本窗口打印
           header('Cache-Control: max-age=0');
           $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
           $response = array(
                 'success' => true,
                 'url' => $this->saveExcelToLocalFile($objWriter, $fileName)
           );
           if ($response) {
              $this->ajaxReturn($response, "json");
           }
        }

         public function getcontion($id,$ex=false){
          $dbmod=D('Gameinfo');
          $dbdata=$dbmod->where('id='.$id)->select();
          if($ex){
            $dbname=$dbdata[0]['extable'];
          }else{
            $dbname=$dbdata[0]['dbname'];
          }
          $connection = array(
              'db_type'    =>   'mysql',
              'db_host'    =>   $dbdata[0]['dburl'],
              'db_user'    =>   $dbdata[0]['dbuser'],
              'db_pwd'     =>   $dbdata[0]['dbpwd'],
              'db_port'    =>    3306,
              'db_name'    =>    $dbname, 
              'db_charset' =>    'utf8',
          );
          return $connection;
        }
        	
}