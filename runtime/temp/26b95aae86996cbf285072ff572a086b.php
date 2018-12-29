<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"D:\server\PHPTutorial\WWW\seat/application/admin\view\driver\edit.html";i:1546010958;s:72:"D:\server\PHPTutorial\WWW\seat\application\admin\view\layout\header.html";i:1545486235;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>南宁驾校统一平台</title>
    <link rel="stylesheet" href="/public/static/layui/css/layui.css">
    <link rel="stylesheet" href="/public/static/css/pai.css">
    <script src="/public/static/layui/layui.js"></script>
    <script src="/public/static/js/jquery-3.3.1.js"></script>

</head>
<blockquote class="layui-elem-quote layui-text">
    <a class="xhx AA094" href="<?php echo url('driver/index'); ?>">驾校管理</a>&nbsp>&nbsp修改驾校
</blockquote>
<style>
    .margin-left10{
        margin-left: 10px !important;
    }

     .layui-form-item label{
         width: 105px !important;
     }   #allmap{
             box-shadow: 10px 10px 10px  #888888;
             width:60%;height:500px;
             position: absolute;
             left:35%;
             right: 5%;
             margin-right: 10%;
             top: 10%;
             border-radius: 1%;
         }
    .anchorBL{
        display:none;
    }
</style>

<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=FkY6tDV2G7d7T4c32YmMbyDClfpQzkUb"></script>
<form class="layui-form" action="<?php echo url('driver/addAction'); ?>" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">驾校名称</label>
        <div class="layui-input-block">
            <input type="text" name="data[drive_name]" value="<?php echo $driver['drive_name']; ?>" lay-verify="title" autocomplete="off" placeholder="请输入服务器名称" class="layui-input inputWidth">
            <input type="hidden" name="data[id]" value="<?php echo $driver['id']; ?>" >
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">驾校地址</label>
        <div class="layui-input-block">
            <input type="text" name="data[site]" id="seat" value="<?php echo $driver['site']; ?>"  autocomplete="off" placeholder="请输入驾校地址" class="layui-input inputWidth">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">联系方式</label>
        <div class="layui-input-block">
            <input type="text" name="data[phone]"  autocomplete="off"  value="<?php echo $driver['phone']; ?>" placeholder="请输入联系方式" class="layui-input inputWidth">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">经度</label>
        <div class="layui-input-block">
            <input type="text" name="data[lng]" autocomplete="off" id="lng" value="<?php echo $driver['lng']; ?>" placeholder="请输入经度" class="layui-input inputWidth">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">纬度</label>
        <div class="layui-input-block">
            <input type="text" name="data[lat]" autocomplete="off" id="lat" value="<?php echo $driver['lat']; ?>" placeholder="请输入纬度" class="layui-input inputWidth">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">默认价格</label>
        <div class="layui-input-block">
            <input type="text" name="data[price]" autocomplete="off" value="<?php echo $driver['price']; ?>" placeholder="请输入默认价格" class="layui-input inputWidth">
        </div>
    </div>

    <?php if(is_array($rolelist) || $rolelist instanceof \think\Collection || $rolelist instanceof \think\Paginator): $i = 0; $__LIST__ = $rolelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
    <div class="layui-form-item">
        <label class="layui-form-label"><?php echo $vo['name']; ?>价格</label>
        <div class="layui-input-block">
            <input type="text" name="price[<?php echo $vo['id']; ?>]" autocomplete="off"  value="<?php echo $vo['price']; ?>" placeholder="请输入<?php echo $vo['name']; ?>看到的价格" class="layui-input inputWidth">
        </div>
    </div>
    <?php endforeach; endif; else: echo "" ;endif; ?>

    <div class="layui-form-item">
        <label class="layui-form-label">驾校链接</label>
        <div class="layui-input-block">
            <input type="text" name="data[link]" autocomplete="off"  value="<?php echo $driver['link']; ?>" placeholder="请输入驾校链接" class="layui-input inputWidth">
        </div>
    </div>

    <div class="layui-form-item" >
        <label class="layui-form-label">类型：</label>
        <div class="layui-input-block" id="type">
            <input type="radio" name="data[type]" value="1" title="驾校" class="product_radio" checked="">
            <input type="radio" name="data[type]" value="0" title="体验站" class="product_radio" >
        </div>
    </div>

    <div class="layui-form-item" >
        <label class="layui-form-label">是否展示：</label>
        <div class="layui-input-block" id="is_show">
            <input type="radio" name="data[is_show]" value="1" title="展示" class="product_radio" checked="">
            <input type="radio" name="data[is_show]" value="0" title="不展示" class="product_radio" >
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即修改</button>
            <a class="layui-btn layui-btn-primary cancel"  lay-filter="cancel"  >取消</a>
        </div>
    </div>

    <div class="layui-row">
        <div class="layui-col-md6" id="allmap"></div>
    </div>

</form>

<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;
        //自定义验证规则
        form.verify({
            title: function(value){
                if(value.length < 1){
                    return '提示处不能为空！';
                }
            }
            ,content: function(value){
                layedit.sync(editIndex);
            }
        });
        //监听指定开关
        form.on('switch(switchTest)', function(data){
            console.log(this.checked);
            layer.msg('IPIP隧道回注：'+ (this.checked ? '开启' : '关闭'), {
                offset: '6px'
            });

        });
        //监听switchHost指定开关
        form.on('switch(switchHost)', function(data){
            console.log(this.checked);
            layer.msg('Host统计：'+ (this.checked ? '开启' : '关闭'), {
                offset: '6px'
            });
            if(this.checked){
                $('.switchHost').show();
            }else{
                $('.switchHost').hide();
            }

        });
        //监听提交
        form.on('submit(demo1)', function(data){
            layui.use('jquery',function(){
                var $=layui.$;
                $.ajax({
                    type: 'post',
                    url: "<?php echo url('driver/editAction'); ?>", // ajax请求路径
                    data: data.field,
                    success: function(data){
                        if(data.code==0){
                             layer.msg('修改成功！');
                             setTimeout(function () {
                                 window.history.back(-1);
                             },1000)
                         }else{
                             layer.msg(data.msg);
                         }
                    }
                });
            });
            return false;
        });
    });

    $(function () {
        console.log(<?php echo $driver["is_show"]; ?>)
        $("#is_show").find("input[value='<?php echo $driver["is_show"]; ?>']").prop("checked",true);
        $("#type").find("input[value='<?php echo $driver["type"]; ?>']").prop("checked",true);


        // var hostcollect =  $("input[name='data[hostcollect]']:checked").val();
        // console.log(hostcollect);
        // if(hostcollect == 'on'){
        //     $('.switchHost').show();
        // }else{
        //     $('.switchHost').hide();
        // }
        // $('#tpisshow').hide();

        // $(".product_radio").click(function () {
        //     var product =  $("input[name='product_type']:checked").val();
        //     if(product == 0){
        //         $('#tpisshow').show();
        //     }else if(product == 1){
        //         $('#tpisshow').hide();
        //     }
        // })

        $(function () {
            $(".cancel").click(function () {
                window.location.href="<?php echo url('driver/index'); ?>"
            })
        })
    })
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point(108.378754, 22.813567), 11);
    function showInfo(e){
        $('#lng').val(e.point.lng)
        $('#lat').val(e.point.lat)
        var point = new BMap.Point(e.point.lne,e.point.lat);
        var geoc = new BMap.Geocoder();
        var pt = e.point;
        geoc.getLocation(pt, function(rs){
            var addComp = rs.addressComponents;
            console.log(addComp);
            $('#seat').val(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber)
        });
    }
    map.addEventListener("click", showInfo);
    map.enableScrollWheelZoom(true);
</script>
