<?php
namespace qpht\Controller;
use Think\Controller;
class GamemanageController extends BaseController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
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
    public function gameManList(){
    	$this->getmenuInfo();
    	$dbmod=D('Gameinfo');
    	$cond['gameid']=1;
        $playlog=$dbmod->field('play_log')->where('id='.$cond['gameid'])->select();
        $cond['playlog']=$playlog[0]['play_log'];
    	$connection=$this->getcontion($cond['gameid']);
    	$playerinfo=new \qpht\Model\PlayerinfoModel('playerinfo','tbl_',$connection);
    	$day=date("Y-m-d",time());
    	$pagecon=1;
    	// $cond['bgtime']='2018-05-01';
    	// $cond['entime']='2018-05-18';
    	$data=$playerinfo->getOneDay($pagecon,$cond);
    	$page=$data[0];
    	$datalist=$data[1];
    	$gameid=$data[2];
    	$this->assign('yday',$yday);
    	$this->assign('tday',$tday);
    	$this->assign('datalist',$datalist);
    	$this->assign('page',$page);
    	$this->assign('gameid',$gameid);
    	$this->display();
    }

    public function seachdata($conds=null){
    	if(!$conds){
    		$data=I('post.');
	    	$cond['page']=$data['page'];
	    	$cond['bgtime']=$data['bgtime'];
	    	$cond['entime']=$data['entime'];
	    	$cond['playid']=$data['playid'];
	    	$cond['nickname']=$data['nickname'];
	    	$cond['playertype']=$data['playertype'];
	    	$cond['ordertype']=$data['ordertype'];
	    	$cond['orderfile']=C(ORDER_LIST)[$data['orderfield']];
	    	$cond['gameid']=$data['gameid'];//根据游戏ID来选择初始化的模型
    	}else{
    		$cond['page']=$conds['page'];
	    	$cond['bgtime']=$conds['bgtime'];
	    	$cond['entime']=$conds['entime'];
	    	$cond['playid']=$conds['playid'];
	    	$cond['nickname']=$data['nickname'];
	    	$cond['playertype']=$conds['playertype'];
	    	$cond['ordertype']=$conds['ordertype'];
	    	$cond['orderfile']=C(ORDER_LIST)[$conds['orderfield']];
	    	$cond['gameid']=$conds['gameid'];//根据游戏ID来选择初始化的模型
    	}
    	// $cond['bgtime']='2018-04-13';
	    // $cond['entime']='2018-05-15';
    	$dbmod=D('Gameinfo');
        $playlog=$dbmod->field('play_log')->where('id='.$cond['gameid'])->select();
        $cond['playlog']=$playlog[0]['play_log'];
    	$connection=$this->getcontion($cond['gameid']);
    	$playerinfo=new \qpht\Model\PlayerinfoModel('playerinfo','tbl_',$connection);
		$resdata=$playerinfo->getOneDay($cond['page'],$cond);
		if($conds){
			return $resdata;
		}else{
			$this->ajaxReturn($resdata);
		}
    }

    public function playsInfo(){
    	$this->getmenuInfo();
    	$userid=I('get.userid');
    	$gameids=I('get.gameid');
    	if($userid||$gameids){
    		$this->assign('userid',$userid);
    		$this->assign('gameids',$gameids);
    	}
    	if(!$gameids){
            $cond['gameid']=1;
    	}
    	if($userid){
    		$cond['playid']=$userid;
    	}
    	$connection=$this->getcontion($cond['gameid']);
    	$tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
  //   	$day=date("Y-m-d",time());
		// $yday=date("Y-m-d",strtotime($day." -1 day"));
		// $tday=date("Y-m-d",strtotime($day." +1 day"));
  //   	$cond['bgtime']=$yday;
  //   	$cond['entime']=$tday;
    	$cond['page']=1;
    	$data=$tableGameChange->playsInfo($cond);
    	$pages=$data[0];
    	$resdata=$data[1];
    	$gameid=$data[2];
    	$page=$data[3];
    	$gameinfo=D('Gameinfo');
        $gamename=$gameinfo->getGameName($gameid);
    	$this->assign('pages',$pages);
    	$this->assign('gamename',$gamename);
    	$this->assign('resdata',$resdata);
    	$this->assign('gameid',$gameid);
    	$this->assign('page',$page);
    	$this->assign('yday',$yday);
    	$this->assign('tday',$tday);
    	$this->display();
    }

    public function seachdata2(){
    	$data=I('post.');
    	$cond['page']=$data['page'];
    	$cond['bgtime']=$data['bgtime'];
    	$cond['entime']=$data['entime'];
    	$cond['gameid']=$data['gameid'];
    	$cond['playid']=$data['playid'];
    	$connection = $connection=$this->getcontion($cond['gameid']);
    	$tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
    	$resdata=$tableGameChange->playsInfo($cond);
    	$this->ajaxReturn($resdata);   	
    }
    public function getexcelplus(){
        $dbmod=D('Gameinfo');
        $data=I('get.');
        $cond['bgtime']=$data['bgtime'];
        $cond['entime']=$data['entime'];
        $cond['playid']=$data['playid'];
        $cond['nickname']=$data['nickname'];
        $cond['playertype']=$data['playertype'];
        $cond['module']=$data['module'];
        $cond['gameid']=$data['gameid'];
        if($cond['module']=='1'){
            $connection=$this->getcontion($cond['gameid']);
            $playerinfo=new \qpht\Model\PlayerinfoModel('playerinfo','tbl_',$connection);
            $playerinfo->exceldata($cond); 
        }else if($cond['module']=='5'){
            $connection=$this->getcontion($cond['gameid']);
            $tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
            $tableGameChange->exceldata($cond);
        }else if($cond['module']=='2'){
            $connection=$this->getcontion($cond['gameid']);
            $cond['gamename']=$dbmod->getGameName($cond['gameid']);
            $convertGood=new \qpht\Model\Convert_goodModel('Convert_good','tbl_',$connection);
            $convertGood->exceldata($cond);
        }
    }
    public function getexcel(){
    	set_time_limit(0);
    	ini_set("memory_limit","2048M");
    	$alldata=array();
    	$data=I('post.');
    	$cond['page']=1;
    	$cond['bgtime']=$data['bgtime'];
    	$cond['entime']=$data['entime'];
    	$cond['playid']=$data['playid'];
    	$cond['nickname']=$data['nickname'];
    	$cond['playertype']=$data['playertype'];
    	$cond['phone']=$data['phone'];
    	$cond['module']=$data['module'];
    	$cond['ordertype']=$data['ordertype'];
    	$cond['orderfile']=C(ORDER_LIST)[$data['orderfield']];
    	$cond['gameid']=1;
    	if($cond['module']=='1'){
    		$data=$this->seachdata($cond);
	    	foreach ($data[1] as $key => $value) {
	    		$temppval=array();
	    		array_push($temppval, $value['userid']);
				array_push($temppval, $value['nickname']);
				array_push($temppval, $value['channel']);
				array_push($temppval, $value['platform']);
				array_push($temppval, $value['sex']);
				array_push($temppval, $value['create_time']);
				array_push($temppval, $value['login_time']);
				array_push($temppval, $value['gold']);
				array_push($temppval, $value['diamond']);
				array_push($temppval, $value['luck_tick']);
				array_push($temppval, $value['convert_tick']);
				array_push($temppval, '...');
				array_push($temppval, $value['rall']);
				array_push($temppval, $value['r1']);
				array_push($temppval, $value['r2']);
				array_push($temppval, $value['r3']);
	    		array_push($alldata, $temppval);
	    	}
	    	for ($i=2; $i<=$data[0] ; $i++) { 
	    		$cond['page']=$i;
	    		$tempdata=$this->seachdata($cond);
	    		foreach ($tempdata[1] as $key => $value) {
		    		$temppval=array();
		    		array_push($temppval, $value['userid']);
					array_push($temppval, $value['nickname']);
					array_push($temppval, $value['channel']);
					array_push($temppval, $value['platform']);
					array_push($temppval, $value['sex']);
					array_push($temppval, $value['create_time']);
					array_push($temppval, $value['login_time']);
					array_push($temppval, $value['gold']);
					array_push($temppval, $value['diamond']);
					array_push($temppval, $value['luck_tick']);
					array_push($temppval, $value['convert_tick']);
					array_push($temppval, '...');
					array_push($temppval, $value['rall']);
					array_push($temppval, $value['r1']);
					array_push($temppval, $value['r2']);
					array_push($temppval, $value['r3']);
		    		array_push($alldata, $temppval);
			    }
	    	}
	    	$expTitle = "用户概况查询";
		    $expCellName = array(
	            array('ID', '昵称', '登录方式', '平台','性别','创建时间','最后登录时间','金币','钻石','福卡','兑换券','在线时长','游戏局数','初级场','中级场','高级场'),
	           );
    	}elseif ($cond['module']=='2') {
    		$data=$this->seachdata3($cond);
	    	foreach ($data[1] as $key => $value) {
	    		$temppval=array();
	    		array_push($temppval, $value['user_id']);
				array_push($temppval, C(GAME_LIST)[$data[2]]);
				array_push($temppval, '...');
				array_push($temppval, $value['goods_name']);
				array_push($temppval, $value['user_phone']);
	    		array_push($alldata, $temppval);
	    	}
	    	for ($i=2; $i<=$data[0] ; $i++) { 
	    		$cond['page']=$i;
	    		$tempdata=$this->seachdata3($cond);
	    		foreach ($tempdata[1] as $key => $value) {
		    		$temppval=array();
		    		array_push($temppval, $value['user_id']);
					array_push($temppval, C(GAME_LIST)[$data[2]]);
					array_push($temppval, '...');
					array_push($temppval, $value['goods_name']);
					array_push($temppval, $value['user_phone']);
		    		array_push($alldata, $temppval);
			    }
	    	}
	    	$expTitle = "兑换相关";
		    $expCellName = array(
	            array('用户ID', '兑换游戏', '兑换时间', '兑换物品','联系方式'),
	           );
    	}elseif($cond['module']=='3'){
    		$data=$this->seachdata4($cond);
	    	foreach ($data[1] as $key => $value) {
	    		$temppval=array();
	    		array_push($temppval, $value['paytime']);
				array_push($temppval, $value['payplayers']);
				array_push($temppval, $value['paynums']);
				array_push($temppval, $value['paymoney']);
				array_push($temppval, $value['newpayplayers']);
				array_push($temppval, $value['newpaynums']);
				array_push($temppval, $value['newpaymoney']);
	    		array_push($alldata, $temppval);
	    	}
	    	for ($i=2; $i<=$data[0] ; $i++) { 
	    		$cond['page']=$i;
	    		$tempdata=$this->seachdata3($cond);
	    		foreach ($tempdata[1] as $key => $value) {
		    		$temppval=array();
		    		array_push($temppval, $value['paytime']);
					array_push($temppval, $value['payplayers']);
					array_push($temppval, $value['paynums']);
					array_push($temppval, $value['paymoney']);
					array_push($temppval, $value['newpayplayers']);
					array_push($temppval, $value['newpaynums']);
					array_push($temppval, $value['newpaymoney']);
		    		array_push($alldata, $temppval);
			    }
	    	}
	    	$expTitle = "充值模块";
		    $expCellName = array(
	            array('充值日期', '充值人数', '充值次数', '充值金额','新增充值人数','新增充值次数','新增充值金额'),
	           );
    	}
    	
	    $this->exportExcel($expTitle,$expCellName,$alldata);
    }

    //兑换相关
    public function convertGoods(){
    	$this->getmenuInfo();
    	$cond['gameid']=1;
    	$cond['page']=1;
    	$cond['user_id']=null;
    	$cond['phone']=null;
    	$connection = $connection=$this->getcontion($cond['gameid']);
    	$tableGameChange=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
    	//$convertGoods=new  \qpht\Model\Convert_goodModel();
    	$data=$tableGameChange->convertGoods($cond);
    	$pages=$data[0];
    	$resdata=$data[1];
    	$gameid=$data[2];
    	$page=$data[3];
    	$this->assign('pages',$pages);
    	$this->assign('resdata',$resdata);
    	$this->assign('gameid',$gameid);
    	$this->assign('page',$page);
    	$this->display();
    }
    //兑换查询
    public function seachdata3($conds=null){
    	$data=I('post.');
    	$cond['page']=$data['page'];
    	$cond['gameid']=$data['gameid'];
    	$cond['user_id']=$data['playid'];
    	$cond['phone']=$data['phone'];
    	$cond['orderfile']=C(ORDER_LIST)[$data['orderfield']];
    	$cond['ordertype']=$data['ordertype'];
    	$connection = $connection=$this->getcontion($cond['gameid']);
    	$convertGoods=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
		$resdata=$convertGoods->convertGoods($cond);
		if($conds){
			return $resdata;
		}else{
    		$this->ajaxReturn($resdata);
		}
    	
    }

    //充值模块
    public function recharge(){
        $this->getmenuInfo();
        $cond['page']=1;
        $cond['gameid']=1;
        $connection=$this->getcontion($cond['gameid'],true);
        $connectionx=$this->getcontion($cond['gameid'],false);
        $goodsinfo=new \qpht\Model\GoodsModel('goods','table_',$connectionx);
        $goodspic=$goodsinfo->getGoodsPrice();
        $rechargedb=new \qpht\Model\Pay_infoModel('pay_info','tbl_',$connection);
        $data=$rechargedb->payInfoCount($cond,$goodspic);
        $pages=$data[0];
        $resdata=$data[1];
        $gameid=$data[2];
        $page=$data[3];
        $this->assign('pages',$pages);
        $this->assign('resdata',$resdata);
        $this->assign('gameid',$gameid);
        $this->assign('page',$page);
        $this->display();
    }

    //充值查询
   public function seachdata4($conds=null){
        $data=I('param.');
        $cond['page']=$data['page'];
        $cond['gameid']=$data['gameid'];
        $connectionx=$this->getcontion($cond['gameid'],false);
        $goodsinfo=new \qpht\Model\GoodsModel('goods','table_',$connectionx);
        $goodspic=$goodsinfo->getGoodsPrice();
        $connection=$this->getcontion($cond['gameid'],true);
        $rechargedb=new \qpht\Model\Pay_infoModel('pay_info','tbl_',$connection);   
        $resdata=$rechargedb->payInfoCount($cond,$goodspic);
        if($conds){
            return $resdata;
        }else{
            $this->ajaxReturn($resdata);
        }
        
    }

    //收入数据
    public function income(){
    	$this->getmenuInfo();
    	$this->display();
    }

    //收入数据查询
    public function seachIncomeData($conds=null){
    	// $data=I('post.');
    	// $cond['begTime']=$data['beginTime'];
    	// $cond['endTime']=$data['endTime'];
    	// $cond['gameid']=$data['gameid'];
    	// $cond['everyday']=$data['everyday'];
    	$dbmod=D('Gameinfo');
    	$gameids=$dbmod->where('state=1')->select();
    	$resdata=array();
    	if($cond['gameid']==0){
    		for ($i=0; $i <count($gameids) ; $i++) { 
    			$connection = $connection=$this->getcontion($gameids[$i]['gameid']);
    			//$tableGameChange=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
    			//$tableGameChange->x();
    		}
    	}else{
    		$connection = $connection=$this->getcontion($cond['gameid']);
    		//$tableGameChange=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
    		//$tableGameChange->x();
    	}
    	$this->ajaxReturn($cond);
    }

    //项目数据
    public function gameProData($conds=null,$isreturn=null){
    	$this->getmenuInfo();
    	if(!$conds['gameid']){
    		$conds['gameid']=1;
    	}
    	if(!$conds['beftime']){
	    	$beftime=date_sub(date_create($conds['endtime']),date_interval_create_from_date_string("7 days"));
	    	$conds['beftime']=date_format($beftime,"Y-m-d");
    	}
    	if(!$conds['endtime']){
    		$conds['endtime']=date("Y-m-d");
    	}
    	//var_dump($conds);
    	$connection=$this->getcontion($conds['gameid']);
    	$rechargedb=new \qpht\Model\PlayerinfoModel('playerinfo','tbl_',$connection);
    	$data1=$rechargedb->getNewAccount($conds);//新增
    	$tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
    	$data2=$tableGameChange->getOldOlayer($data1);//次留
    	$data3=$tableGameChange->getPlayerNum($data2);//活跃数
    	$data4=$tableGameChange->getplaysInfo($data3);//获取对局数
    	if($isreturn){
    		return $data4;
    	}
    	//exit();
    	$this->display();
    }

    public function excelplus(){
            $dbmod=D('Gameinfo');
            $cond['gameid']=1;
            $connection=$this->getcontion($cond['gameid']);
            $playerinfo=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
            $playerinfo->exceldata();
            //$count      = $res->alias('l')->where($where)->count();// 查询满足要求的总记录数  
            // $count      = $playerinfo->count();// 查询满足要求的总记录数  
            // $Page       = new \Think\Page($count,10000);// 实例化分页类 传入总记录数和每页显示的记录数(25)  
            // $ppp = ceil($count/10000);  
            // $pp = range(1,$ppp);
            // foreach ($pp as $kkk => $vvv) {  
            //     $rs[$kkk] = $playerinfo->field('user_id,change_gold,room_id,change_time,in_gold')->page($vvv.', 10000')->select();  
            
            //     $str[$kkk] = "user_id,change_gold,room_id,change_time,in_gold";  
                      
            //     $exl11[$kkk] = explode(',',$str[$kkk]);  
            //     foreach ($rs[$kkk] as $k => $v){  
            
            //         if (!$v['user_id']) $v['userid']           = '暂无数据';  
            //         if (!$v['change_gold']) $v['change_gold']                 = '暂无数据';  
            //         if (!$v['room_id']) $v['room_id']             = '暂无数据';  
            //         if (!$v['change_time']) $v['change_time']                 = '暂无数据';  
            //         if (!$v['in_gold']) $v['in_gold']   = '暂无数据';  
            //         $exl[$kkk][] = array(  
            //             $v['user_id'],$v['change_gold'],$v['room_id'],$v['change_time'],$v['in_gold']  
            //         );  
  
            //     }  
  
            //    exportToExcel('兑奖记录_'.time().$vvv.'.csv',$exl11[$kkk],$exl[$kkk]);  
            // }  
            // exit();  
    }
}