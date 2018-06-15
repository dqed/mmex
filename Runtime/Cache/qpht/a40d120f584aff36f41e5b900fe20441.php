<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>棋牌后台</title>
    <!-- Bootstrap Styles-->
    <link href="/Public/qpht/assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="/Public/qpht/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="/Public/qpht/assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="/Public/qpht/assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="/Public/qpht/assets/js/Lightweight-Chart/cssCharts.css"> 
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
    <link rel="stylesheet" href="/Public/qpht/assets/css/selfstyle.css">
    <link rel="stylesheet" href="/Public/qpht/assets/css/jquery.datetimepicker.css">
    <script src="/Public/qpht/assets/js/jquery-1.10.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">
         function loadSource(src){
            this.src = src;
            var type = src.split(".").pop();
            var s = document.createElement('script');
            s.src = this.src+"?"+Math.random();
            s.async = true;
            $('#footscript').append(s);
        }
    </script>
    <script type="text/javascript">
        Date.prototype.format = function (format) {
        var args = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
        };
        if (/(y+)/.test(format))
            format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var i in args) {
            var n = args[i];
            if (new RegExp("(" + i + ")").test(format))
                format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? n : ("00" + n).substr(("" + n).length));
        }
        return format;
    };
    </script>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" id="test44"><strong >点秦棋牌后台</strong></a>
                
        <div id="sideNav" href=""><i class="fa fa-caret-right"></i></div>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> 用户信息</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> 设置</a>
                        </li> -->
                        <li class="divider"></li>
                        <li><a href="javascript:void(0);" id="outsystem"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <input type="hidden" name="" id="aclog" value="/<?php echo ($actionname); ?>/<?php echo ($actionname2); ?>">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <?php if(is_array($moduleinfo1)): $i = 0; $__LIST__ = $moduleinfo1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vmodules): $mod = ($i % 2 );++$i; if(($acid == $vmodules['id'])): ?><li class="acli active" data-vx='<?php echo ($vmodules["modulename"]); ?>'>
                        <?php else: ?>
                            <li class="acli" data-vx='<?php echo ($vmodules["modulename"]); ?>'><?php endif; ?>
                            <a href="#"><i class="fa fa-sitemap"></i> <?php echo ($vmodules["modulename"]); ?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            <?php if(is_array($menuinfo1)): $i = 0; $__LIST__ = $menuinfo1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vmenus): $mod = ($i % 2 );++$i; if(is_array($vmenus)): $i = 0; $__LIST__ = $vmenus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vmenuss): $mod = ($i % 2 );++$i; if($vmodules["id"] == $vmenuss['moduleid']): ?><li data-cc='mx'>
                                            <a href="/qpht.php<?php echo ($vmenuss["menurl"]); ?>" class="menuac"><?php echo ($vmenuss["menuname"]); ?></a>
                                        </li><?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>  
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
      
        <div id="page-wrapper">
          <div class="header"> 
                        <h1 class="page-header">
                          <small>欢迎 <?php echo ($username); ?></small>
                        </h1>
                        <ol class="breadcrumb">
                      <li><a href="/qpht.php/index/index">首页</a></li>
                      <li><a href="<?php echo ($actions); ?>"><?php echo ($mdname); ?></a></li>
                    </ol> 
                                    
        </div>
            <div id="page-inner">
                
    <div id="page-inner">                
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            兑换模块
                            <form class="form-inline">
							  <div class="form-group">
							    <label for="exampleInputName2">用户ID:</label>
							    <input type="text" class="form-control" id="playerid" placeholder="用户ID">
							  </div>
							  <div class="form-group">
							    <label for="exampleInputEmail2">兑换游戏:</label>
							    <select class="form-control" id="gametype">
							       <?php if(is_array($gameinfo)): $i = 0; $__LIST__ = $gameinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["gamename"]); ?></option>
								  <!-- <option value="2">斗地主</option> --><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							  </div>
							  <div class="form-group">
							    <label for="exampleInputName2">联系方式:</label>
							    <input type="text" class="form-control" id="playerphone" placeholder="联系方式">
							  </div>
							  <button type="button" class="btn btn-default" id="seachbtn">查询</button>
							  <button type="button" class="btn btn-default" id="getexclbtn">导出</button>
							</form>
                        </div>
                        <div class="panel-body">
                            <!-- <ul class="nav nav-tabs">
                                <li class="active"><a href="#home" data-toggle="tab">血流成河</a>
                                </li>
                                <li class=""><a href="#profile" data-toggle="tab">斗地主</a>
                                </li>
                            </ul> -->

                            <div class="tab-content">
                            	<div class="tab-pane fade active in" id="home">
                            		<input type="hidden" name="" value="<?php echo ($gameid); ?>" id="gameid">
                            		<input type="hidden" value="asc" data-begin="<?php echo ($yday); ?>" data-end="<?php echo ($tday); ?>" id="timespan">
		                                    <div class="table-responsive">
				                                 <table class="table table-striped table-bordered table-hover" id="dataTables-example">
				                                    <thead>
				                                        <tr>
				                                            <th>编号</th>
				                                            <th class="ordersign" field='1'>用户ID
				                                            	<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true" data-orderfile="user_id" data-ordertype='asc'></span>
				                                            </th>
				                                            <th>兑换游戏</th>
				                                            <th>兑换时间</th>
				                                            <th>兑换物品</th>
				                                            <th class="ordersign" field='1'>联系方式
				                                            	<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true" data-orderfile="user_phone" data-ordertype='asc'></span>
				                                            </th>
				                                    </thead>
				                                    <tbody id="table_main" class="table_main">
				                                    	<?php if(is_array($resdata)): $k = 0; $__LIST__ = $resdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($k % 2 );++$k;?><tr class="odd gradeX">
				                                            <td>
				                                            		<!-- <label>
				                                            			<input type="checkbox" class="selcheck" value="<?php echo ($account["id"]); ?>">
				                                            		</label> -->
				                                            		<?php echo ($k); ?>
				                                            </td>
				                                            <td class="center"><?php echo ($vos["user_id"]); ?></td>
				                                            <td class="center">
																	<?php switch($gameid): case "1": ?>血流麻将<?php break;?>
																	    <?php case "2": ?>斗地主<?php break;?>
																	    <?php default: ?>default<?php endswitch;?>
				                                            </td>
				                                            <td class="center"><?php echo ($vos["data"]); ?></td>
				                                            <td class="center"><?php echo ($vos["goods_name"]); ?></td>
				                                            <td class="center"><?php echo ($vos["user_phone"]); ?></td>
				                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
				                                    </tbody>
				                                </table>
				                            </div>
				                                <div class="row pagediv" style="position: relative;">
			                                	<div class="btn-group col-md-2 col-sm-2 pagechange" role="group" aria-label="..." style="width: 110px">
												  <button type="button" class="btn btn-default" id="goprevx">
												  	 <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
												  </button>
												  <button type="button" class="btn btn-default" id="goprev">
												  	 <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
												  </button>
												</div>
												<div class="col-md-2 col-sm-2">
<input type="tel" id="pagenums" data-page="<?php echo ($pages); ?>" name="" value="1" class="form-control" placeholder="" style="width: 70px" style="float: left;">
<button type="" class="btn btn-info" id='gopage' style="float: right;position: relative; bottom: 34px;">跳转</button>
</div>
<p style="position: absolute;left:190px;bottom:30px">共<i id="allpages"><?php echo ($pages); ?></i>页</p>
												<div class="btn-group col-md-2 col-sm-2 pagechange" role="group" aria-label="..." style="position: relative;left: 60px;">
												  <button type="button" class="btn btn-default" id="gonext">
												  	<span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
												  </button>
												  <button type="button" class="btn btn-default" id="gonextx">
												  	<span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
												  </button>
												</div>
			                            	</div>
		                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        	window.onload=function(){
        		new loadSource("/Public/qpht/assets/js/convertgoods.js");
        	}
        </script>

            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
     <div id="footscript"></div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
   
   <!--  <script src="/Public/qpht/assets/js/jquery.js"></script> -->
    <!-- Bootstrap Js -->
    <script src="/Public/qpht/assets/js/bootstrap.min.js"></script>
    <script src="/Public/qpht/assets/js/jquery.datetimepicker.full.js"></script>
    <!-- Metis Menu Js -->
    <script src="/Public/qpht/assets/js/jquery.metisMenu.js"></script>
    <!-- Morris Chart Js -->
    <!-- <script src="/Public/qpht/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="/Public/qpht/assets/js/morris/morris.js"></script> -->
    
    
    <script src="/Public/qpht/assets/js/easypiechart.js"></script>
    <script src="/Public/qpht/assets/js/easypiechart-data.js"></script>
    
     <script src="/Public/qpht/assets/js/Lightweight-Chart/jquery.chart.js"></script>
     <script src="/Public/qpht/assets/js/pubfunction.js?1254565serwda"></script>
    
    <!-- Custom Js -->
    <script src="/Public/qpht/assets/js/custom-scripts.js"></script>
    
      <script>
        $('#begin_time').datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y/m/d'
        }); 
        $('#end_time').datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y/m/d'
        });
        new loadSource("/Public/qpht/assets/js/accountfun.js");  
      </script>
   
</body>

</html>