<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:67:"D:\server\PHPTutorial\WWW\seat/application/admin\view\user\add.html";i:1546100199;s:72:"D:\server\PHPTutorial\WWW\seat\application\admin\view\layout\header.html";i:1545486235;}*/ ?>
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
    添加子账号
</blockquote>
<form class="layui-form" action="<?php echo url('pushpolicy/addAction'); ?>" method="post">

    <div class="layui-form-item">
        <label class="layui-form-label">账号</label>
        <div class="layui-input-inline" >
            <input type="text" name="data[name ]" id="verifyName" lay-verify="name" placeholder="请输入账号" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux showName">请填写4-16位（字母、数字、下划线、减号组合）</div>
        <div class="layui-form-mid layui-word-aux hideName c666">账号已经存在</div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">昵称</label>
        <div class="layui-input-inline" >
            <input type="text" name="data[nick_name]"  placeholder="请输入账号" autocomplete="off" class="layui-input">
        </div>

    </div>
    <div class="layui-form-item" id="tactics">
        <div class="layui-inline">
            <label class="layui-form-label">级别</label>
            <div class="layui-input-inline">
                <select name="data[type]"  class="selectWidth">
                    <option value="0">————请选择————</option>
                    <?php if(is_array($roleList) || $roleList instanceof \think\Collection || $roleList instanceof \think\Paginator): $i = 0; $__LIST__ = $roleList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="text" name="data[passwd]"  id="verifyPass" lay-verify="pass" placeholder="请填写4-16位密码" autocomplete="off" class="layui-input">
        </div>
    </div>


    <div class="layui-form-item" pane="">
        <label class="layui-form-label" style="margin-right: -10px;">指定菜单栏</label>
        <?php if(!empty($menulist)): if(is_array($menulist) || $menulist instanceof \think\Collection || $menulist instanceof \think\Paginator): $i = 0; $__LIST__ = $menulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <div class="layui-input-block" style="width: 600px;">
                    <div class="father">
                        <input type="checkbox"  name="sonCheck[]" lay-skin="primary" value="<?php echo $vo['info']['id']; ?>"  title="<?php echo $vo['info']['menu_name']; ?>">
                    </div>
                    <div class="son">
                        <?php if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?>
                        <input type="checkbox" name="sonCheck[]" lay-skin="primary" value="<?php echo $child['info']['id']; ?>" title="<?php echo $child['info']['menu_name']; ?>">
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>

                </div>
            <?php endforeach; endif; else: echo "" ;endif; else: ?> <?php echo $msg; endif; ?>

    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即添加</button>
            <a class="layui-btn layui-btn-primary cancel"  lay-filter="cancel"  >取消</a>
        </div>
    </div>
</form>
<style>
    .father{
        color: #000;
        font-size: 30px;
        font-weight: 700;
    }
</style>

<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;
        //自定义验证规则
        form.verify({
            name: [/^[a-zA-Z0-9_-]{4,16}$/,'账号为：4-16位字母、数字、下划线、减号组合'],
            pass: [/^[a-zA-Z0-9_-]{4,16}$/,'密码为：4-16位字母、数字、下划线、减号组合'],
            content: function(value){
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
                    url: "<?php echo url('user/addAction'); ?>", // ajax请求路径
                    data: data.field,
                    success: function(data){
                        if(data.code==0){
                             layer.msg('添加成功');
                             setTimeout(function () {
                                 window.location.href="<?php echo url('index/userIndex'); ?>"
                             },1000)
                         }else{
                             layer.msg(data.msg);
                         }
                    }
                });
            });
            return false;
        });

        $('.son').click(function () {
            console.log($("input[name='checkboxall']").val())
            $(this).prev().children('input').prop("checked",true);
            form.render('checkbox');
        })


        $('.father').click(function () {
            var result = $(this).children('input').is(':checked');
            if(result == true){
                $(this).next().children('input').prop("checked",true);
            }else{
                $(this).next().children('input').prop("checked",false);
            }
            form.render('checkbox');
        })
    });



    // 取消
    $(function () {
        $('.showName').show();
        $('.passName').show();

        $('.hideName').hide();
        $("#verifyName").blur(function () {
            var nameValue = $("input[name='data[username]']").val();
            if(nameValue == ''){
                $('.showName').show();
            }
            $.ajax({
                type: 'post',
                url: "<?php echo url('user/verifyName'); ?>", // ajax请求路径
                data: {username:nameValue},
                success: function(data){
                    console.log(data);
                    if(data == 1){ //说明存在相同的账号，提示错误后清空input
                        $('.showName').hide();
                        $('.hideName').show();
                    }else{
                        $('.hideName').hide();
                    }
                }
            });
        })


        $(".cancel").click(function () {
            window.location.href="<?php echo url('index/userIndex'); ?>"
        })

        $(function () {

        })

    })

</script>