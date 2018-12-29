<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"D:\server\PHPTutorial\WWW\seat/application/admin\view\index\index.html";i:1545477285;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- 删除苹果默认的工具栏和菜单栏 -->
    <meta name="viewport"
          content="width=device-width,initial-scale=1,
        minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <link rel="icon" href="/public/static/admin/images/favicon.ico">
    <link rel="stylesheet" href="/public/static/admin/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/public/static/admin/css/font_eolqem241z66flxr.css" media="all" />
    <link rel="stylesheet" href="/public/static/admin/css/main.css" media="all" />
    <link rel="stylesheet" href="/public/static/admin/ali/iconfont.css" media="all" />

    <link rel="stylesheet" href="/public/static/css/pai.css">
    <script src="/public/static/js/jquery-3.3.1.js"></script>
</head>
<body class="main_body">
<div class="layui-layout layui-layout-admin">
    <!-- 顶部 -->
    <div class="layui-header header">
        <div class="layui-main">
            <a href="#" class="logo"> 南宁驾校统一平台</a>

            <!-- 顶部右侧菜单 -->
            <ul class="layui-nav top_menu">

                <?php if($userflag == 1){ ?>
                    <li class="layui-nav-item lockcms" pc>
                        <a href="javascript:;"><i class="layui-icon licon iconfont icon-suoping"></i><cite class="">锁屏</cite></a>
                    </li>
                <?php } ?>
                <li class="layui-nav-item" mobile>
                    <a href="javascript:;"><i class="iconfont icon-loginout quit"></i> <span class="quit">退出</span></a>
                </li>

                <li class="layui-nav-item" pc>
                    <a href="javascript:;"><i class="iconfont icon-loginout "></i><span class="quit"> 退出</span></a>
                </li>

                <li class="layui-nav-item" pc>
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <?php echo $username; ?>
                    </a>
                    <!--<dl class="layui-nav-child">-->
                        <!--<dd><a href="">密码修改</a></dd>-->
                    <!--</dl>-->
                </li>
                </li>
            </ul>
        </div>
    </div>
    <!-- 左侧导航 -->
    <div class="layui-side layui-bg-black">
        <div class="navBar layui-side-scroll" style="height: 695px;">
            <ul class="layui-nav layui-nav-tree">
<!--//////////////////////////////////////-->
            <?php if(!empty($menulist)): if(is_array($menulist) || $menulist instanceof \think\Collection || $menulist instanceof \think\Paginator): $i = 0; $__LIST__ = $menulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li class="layui-nav-item "><a href="javascript:;"><i class="layui-icon licon iconfont <?php echo $vo['info']['menu_icon']; ?>" data-icon=""></i><cite><?php echo $vo['info']['menu_name']; ?></cite><span class="layui-nav-more"></span></a>
                        <?php if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?>
                            <dl class="layui-nav-child">
                                <dd><a href="javascript:;" data-url="<?php echo $child['info']['menu_url']; ?>"> <i class="layui-icon licon iconfont <?php echo $child['info']['menu_icon']; ?>" data-icon=""></i><cite><?php echo $child['info']['menu_name']; ?></cite></a></dd>
                                <!--<dd><a href="javascript:;" data-url="<?php echo $child['info']['menu_url']; ?>"><i class="layui-icon layui-icon-username " data-icon=""><?php echo $child['info']['menu_icon']; ?></i><cite><?php echo $child['info']['menu_name']; ?></cite></a></dd>-->
                            </dl>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </li>
                <?php endforeach; endif; else: echo "" ;endif; else: ?> <?php echo $msg; endif; ?>
<!--//////////////////////////////////-->  <i class="icon iconfont icon-fanhui">
                <span class="layui-nav-bar" style="top: 227.5px; height: 0px; opacity: 0;"></span>
            </ul>

        </div>
    </div>
    <!-- 右侧内容 -->
    <style>
        .warning{
            background: #FF5722!important;
            padding: 3px;
            color: #fff;
            border-radius: 2px;
        }
    </style>
    <div class="layui-body layui-form" style="bottom: 0">
        <div class="layui-tab marg0" lay-filter="bodyTab">
            <ul class="layui-tab-title top_tab">
                <li class="layui-this" lay-id=""><i class="layui-icon licon iconfont icon-zhuye"></i> <cite>后台首页&nbsp&nbsp
                </cite></li>
            </ul>
            <div class="layui-tab-content clildFrame">
                <div class="layui-tab-item layui-show">
                    <iframe src="<?php echo url('index/main'); ?>" name="myFrameName"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部 -->
    <div class="layui-footer footer">
        南宁驾校统一平台-海海技术支持
    </div>
</div>

<!-- 锁屏 -->
<div class="admin-header-lock" id="lock-box" style="display: none;">
    <div class="input_btn">
        <input type="password" class="admin-header-lock-input layui-input" placeholder="请输入密码解锁.." name="lockPwd" id="lockPwd" />
        <button class="layui-btn" id="unlock">解锁</button>
    </div>
</div>
<!-- 移动导航 -->
<div class="site-tree-mobile layui-hide"><i class="layui-icon">&#xe602;</i></div>
<div class="site-mobile-shade"></div>

<script type="text/javascript" src="/public/static/admin/layui/layui.js"></script>
<script type="text/javascript" src="/public/static/admin/js/leftNav.js"></script>
<script type="text/javascript" src="/public/static/admin/js/index.js"></script>
</body>
</html>

<script>
    //JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;
    });
    layui.use('layer', function(){
        $(function () {
            $('.daikaifang').click(function () {
                layer.msg('此功能待开放！');
            })

            $('.yujing').click(function () {
                console.log(1)
                window.myFrameName.location.href = '<?php echo url('index/warning'); ?>';
            })

            $('.quit').click(function () {
                $.post("<?php echo url('login/exitAction'); ?>",function(data){
                    console.log(data)
                    if(data == 0){
                        layer.msg('2秒后退出！');
                        setTimeout(function () {
                            window.location.href="<?php echo url('index/index/login'); ?>";
                        },2000)
                    }
                });
            })
        })

    })
    
    function time()
    {
        dt = new Date();
        var h=dt.getHours();//获取时
        var m=dt.getMinutes();//获取分
        var s=dt.getSeconds();//获取秒
        document.getElementById("showTime").innerHTML =  "如今的时间为："+h+"时"+m+"分"+s+"秒";
        setTimeout("time()",1000); //设定定时器，循环运行
    }


</script>
