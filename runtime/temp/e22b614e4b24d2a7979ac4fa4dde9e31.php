<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\server\PHPTutorial\WWW\seat/application/admin\view\driver\index.html";i:1545664289;s:72:"D:\server\PHPTutorial\WWW\seat\application\admin\view\layout\header.html";i:1545486235;}*/ ?>
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
    驾校列表&nbsp&nbsp&nbsp&nbsp&nbsp
    <!--<span class="back xhx"></span>-->
</blockquote>


<table class="layui-table" lay-data="{width: 1350, height:680, url:'<?php echo url('driver/getDriverlist'); ?>', page:true,limits:[15,30,45],limit:15, id:'idTest'}" lay-filter="demo">
    <div class=" demoTable layui-inline">
        <div class="layui-inline">
            <input class="layui-input" name="id" id="search" placeholder="请输入驾校名称">
        </div>
        <button class="layui-btn" data-type="reload">搜索</button>
        <a href="<?php echo url('driver/add'); ?>"><button class="layui-btn margin-left10" style="margin-left: 10px;" >添加驾校</button></a>
    </div>
    <thead>
    <tr>
        <th lay-data="{field:'id', width:70}">ID</th>
        <th lay-data="{field:'drive_name', width:200}">驾校名称</th>
        <th lay-data="{field:'site', width:380}">驾校地址</th>
        <th lay-data="{field:'phone', width:180}">联系方式</th>
        <th lay-data="{field:'macaddress', width:180,templet: '#serverstatus'}">驾校类型</th>
        <th lay-data="{fixed: 'right', width:120, align:'center', toolbar: '#barDemo'}">操作项</th>
    </tr>
    </thead>
</table>


<script type="text/html" id="serverstatus">
    {{#  if(d.type ==1){ }}
    <span class="c666 overstriking">驾校</span>
    {{#  } else if(d.type ==0){ }}
    <span class="c009688 overstriking"> 体验站</span>
    {{#  } }}
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>

    function checkServer(id){

    }

    function showLoad() {
        return layer.msg('正在执行中...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:100000});
    }

    function closeLoad(index) {
        layer.close(index);

    }
    function showSuccess() {
        layer.msg('执行成功！',{time: 1000,offset: 'auto'});

    }

    layui.use('table', function(){
        var table = layui.table;
        //监听表格复选框选择
        table.on('checkbox(demo)', function(obj){
            console.log(obj)
        });
        //监听工具条
        table.on('tool(demo)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                window.location.href="<?php echo url('driver/edit'); ?>?id="+data.id;
            } else if(obj.event === 'del'){
                layer.confirm('数据无法恢复，确定执行删除操作？', function(index){
                    layui.use('jquery',function(){
                        var $=layui.$;
                        $.ajax({
                            type: 'post',
                            url: "<?php echo url('driver/delAction'); ?>", // ajax请求路径
                            data: obj.data,
                            success: function(data){
                                if(data.code==0){
                                        layer.msg('删除成功！');
                                    obj.del();
                                    layer.close(index);
                                }else{
                                    return layer.msg(data.msg, {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }
                            }
                        });
                    });
                });
            } else if(obj.event === 'edit'){
                window.location.href="<?php echo url('driverdrive_name/edit'); ?>?id="+data.id;
            }
        });

        var $ = layui.$, active = {
            reload: function(){ //获取输入框数据
                var demoReload = $('#search').val();
                table.reload('idTest', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        drive_name: demoReload
                    }
                });
            }
        };


        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });


    });
</script>