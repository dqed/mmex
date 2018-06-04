<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>棋牌后台</title>
    <!-- Bootstrap Styles-->
    <link href="/qp/Public/qpht/assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="/qp/Public/qpht/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="/qp/Public/qpht/assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="/qp/Public/qpht/assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="/qp/Public/qpht/assets/js/Lightweight-Chart/cssCharts.css"> 
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
    <link rel="stylesheet" href="/qp/Public/qpht/assets/css/selfstyle.css">
    <link rel="stylesheet" href="/qp/Public/qpht/assets/css/jquery.datetimepicker.css">
    <script src="/qp/Public/qpht/assets/js/jquery-1.10.2.js"></script>
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
                            游戏概览
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs" role="tablist">
                            	<?php if(is_array($gameinfo)): $i = 0; $__LIST__ = $gameinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["id"] == 1)): ?><li class="active" role="presentation"><a href="#my-table<?php echo ($vo["id"]); ?>" role="tab" data-toggle="tab" aria-controls="my-table<?php echo ($vo["id"]); ?>" data-gameid=<?php echo ($vo["id"]); ?>><?php echo ($vo["gamename"]); ?></a>
	                                <?php else: ?>
	                                	<li role="presentation"><a href="#my-table<?php echo ($vo["id"]); ?>" data-toggle="tab" aria-controls="my-table<?php echo ($vo["id"]); ?>" role="tab" data-gameid=<?php echo ($vo["id"]); ?>><?php echo ($vo["gamename"]); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                            <div class="tab-content">
                               <?php if(is_array($lres)): $i = 0; $__LIST__ = $lres;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i; if(($key == 1)): ?><div class="tab-pane active"  id="my-table<?php echo ($key); ?>" role="tabpanel">
                               	 	<?php else: ?>
                            	<div class="tab-pane"  id="my-table<?php echo ($key); ?>" role="tabpanel"><?php endif; ?>
                                    <div class="table-responsive">
			                                <table class="table table-striped table-bordered table-hover">
			                                    <thead>
			                                        <tr>
			                                            <th>日期</th>
			                                            <th>新增</th>
			                                            <th>次留</th>
			                                            <th>总活跃</th>
			                                            <th>总对局</th>
			                                            <th>平均对局</th>
			                                    </thead>
		                             			<tbody id="table_main">
			                                    	<?php if(is_array($vos)): $i = 0; $__LIST__ = $vos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vort): $mod = ($i % 2 );++$i;?><tr class="odd gradeX">
				                                            <td class="center">
				                                            		<?php echo ($vort["countime"]); ?>
				                                            </td>
				                                            <td class="center"><?php echo ($vort["newplayer"]); ?></td>
				                                            <td class="center">
																	<?php echo ($vort["oldplayer"]); ?>
				                                            </td>
				                                            <td class="center"><?php echo ($vort["activeplayer"]); ?></td>
				                                            <td class="center"><?php echo ($vort["allplay"]); ?></td>
				                                            <td class="center"><?php echo ($vort["aveplay"]); ?></td>
				                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
			                                    </tbody>
			                                </table>
		                            </div>
		                         </div><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        	window.onload=function(){
        		new loadSource("/qp/Public/qpht/assets/js/index.js");
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
   
   <!--  <script src="/qp/Public/qpht/assets/js/jquery.js"></script> -->
    <!-- Bootstrap Js -->
    <script src="/qp/Public/qpht/assets/js/bootstrap.min.js"></script>
    <script src="/qp/Public/qpht/assets/js/jquery.datetimepicker.full.js"></script>
    <!-- Metis Menu Js -->
    <script src="/qp/Public/qpht/assets/js/jquery.metisMenu.js"></script>
    <!-- Morris Chart Js -->
    <!-- <script src="/qp/Public/qpht/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="/qp/Public/qpht/assets/js/morris/morris.js"></script> -->
    
    
    <script src="/qp/Public/qpht/assets/js/easypiechart.js"></script>
    <script src="/qp/Public/qpht/assets/js/easypiechart-data.js"></script>
    
     <script src="/qp/Public/qpht/assets/js/Lightweight-Chart/jquery.chart.js"></script>
     <script src="/qp/Public/qpht/assets/js/pubfunction.js?1254565sda"></script>
    
    <!-- Custom Js -->
    <script src="/qp/Public/qpht/assets/js/custom-scripts.js"></script>
    
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
        new loadSource("/qp/Public/qpht/assets/js/accountfun.js");  
      </script>
   
</body>

</html>