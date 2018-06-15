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
                            收入情况
                            <form class="form-inline">
							  <div class="form-group">
							    <label for="exampleInputName2">项目名称:</label>
							    <select class="form-control" id="gametype">
							    	<option value="0">全部</option>
	                                <?php if(is_array($gameinfo)): $i = 0; $__LIST__ = $gameinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["gamename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							  </div>
							  <div class="form-group">
							    <label for="exampleInputEmail2">指定日期</label>
							    <input type="text" class="form-control" id="begin_time">——
							    <input type="text" class="form-control" id="end_time">
							  </div>
							  <button type="button" class="btn btn-default" id="seachbtn">查询</button>
							  <button type="button" class="btn btn-default" id="seachbtnevery">按天查询</button>
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
				                                            <th>项目名称</th>
				                                            <th>金币场服务费</th>
				                                            <th>互动道具</th>
				                                            <th>自定义道具</th>
				                                            <th>转盘</th>
				                                            <th>夺宝</th>
				                                            <th>时间</th>
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
				                                            <td class="center">...</td>
				                                            <td class="center"><?php echo ($vos["goods_name"]); ?></td>
				                                            <td class="center"><?php echo ($vos["user_phone"]); ?></td>
				                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
				                                    </tbody>
				                                </table>
				                                <div class="col-md-1 col-sm-1">
				                                	<select class="form-control" id="pagenums" >
													  <?php $__FOR_START_13061__=0;$__FOR_END_13061__=$pages;for($i=$__FOR_START_13061__;$i < $__FOR_END_13061__;$i+=1){ ?><option><?php echo ($i+1); ?></option><?php } ?>
													</select>
				                                </div>
				                                </p>
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
        		new loadSource("/Public/qpht/assets/js/income.js");
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
    <script src="/Public/qpht/assets/js/morris/raphael-2.1.0.min.js"></script>
    <!-- <script src="/Public/qpht/assets/js/morris/morris.js"></script> -->
    
    
    <script src="/Public/qpht/assets/js/easypiechart.js"></script>
    <script src="/Public/qpht/assets/js/easypiechart-data.js"></script>
    
     <script src="/Public/qpht/assets/js/Lightweight-Chart/jquery.chart.js"></script>
     <script src="/Public/qpht/assets/js/pubfunction.js?125456sdfs5serwda"></script>
    
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