<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\server\PHPTutorial\WWW\seat/application/admin\view\role\add.html";i:1545497279;s:72:"D:\server\PHPTutorial\WWW\seat\application\admin\view\layout\header.html";i:1545486235;}*/ ?>
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
    添加角色
</blockquote>
<form class="layui-form layui-form-item" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label typename">角色名称</label>
        <div class="layui-input-block">
            <input type="text" name="data[name]" lay-verify="whitelistContents" autocomplete="off" placeholder="请输入" class="layui-input inputWidth">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即添加</button>
            <a class="layui-btn layui-btn-primary cancel"  lay-filter="cancel"  >取消</a>
        </div>
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
            whitelistContents: function(value){
                if(value.length < 1){
                    return '内容不能为空!';
                }
            }
            ,content: function(value){
                layedit.sync(editIndex);
            }
        });
        //监听提交
        form.on('submit(demo1)', function(data){
            // layer.alert(JSON.stringify(data.field), {
            //     title: '最终的提交信息'
            // })
            layui.use('jquery',function(){
                var $=layui.$;
                $.ajax({
                    type: 'post',
                    url: "<?php echo url('Role/addAction'); ?>", // ajax请求路径
                    data: data.field,
                    success: function(data){
                        if(data.code==0){
                             layer.msg('添加成功');
                             setTimeout(function () {
                                 window.location.href="<?php echo url('Role/Index'); ?>"
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
        $(".product_radio").click(function () {
            var product =  $("input[name='data[iptype]']:checked").val();
            if(product == 0){
                $('.typename').text('宽带账号');
                $('.format').hide();

            }else if(product == 1){
                $('.typename').text('用户IP');
                $('.format').show();
            }
        })
        // 取消
        $(function () {
            $(".cancel").click(function () {
                window.location.href="<?php echo url('Role/index'); ?>"
            })
        })
    })
</script>
