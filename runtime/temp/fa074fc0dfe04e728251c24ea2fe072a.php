<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"D:\server\PHPTutorial\WWW\seat/application/admin\view\role\index.html";i:1545498364;s:72:"D:\server\PHPTutorial\WWW\seat\application\admin\view\layout\header.html";i:1545486235;}*/ ?>
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
     角色&nbsp&nbsp&nbsp&nbsp&nbsp
    <!--<span class="back xhx"></span>-->
</blockquote>

<div action="" class="layui-form">
    <table class="layui-table " lay-data="{width: 500, height:667, url:'<?php echo url('Role/getRolelist'); ?>', page:true,limits:[15,30,45],limit:15,serverid:0, id:'idTest'}" lay-filter="demo">
        <div class="layui-form-item" id="tpisshow">
            <div class=" demoTable layui-inline">
                <div class="layui-inline">
                    <input class="layui-input" name="id" id="search" placeholder="请输入搜索角色名称">
                </div>
                <button class="layui-btn" data-type="reload">搜索</button>
                <a class="layui-btn margin-left10 blackAdd" style="margin-left: 10px;"  lay-event="sonLink" c>添加角色</a>
            </div>



        </div>
        <thead>
        <tr>
            <th lay-data="{field:'id', width:115, sort: true, fixed: true}">ID</th>
            <th lay-data="{field:'name', width:220}">角色名称</th>
            <th lay-data="{field:'status', width:140,templet: '#status'}">操作项</th>
        </tr>
        </thead>
    </table>
</div>

<script type="text/html" id="status">
    <a class="layui-btn  layui-btn-xs " lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


<script type="text/html" id="product_names">
    <a class="xhx" lay-event="sonLink">{{d.product_name}}</a>
</script>


<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">规则</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>

    layui.use(["table","form"], function(){


    })
    layui.use(["table","form"], function(){
        var table = layui.table;
        var form = layui.form;
        form.on('select(selectChage)', function(data){
            console.log(data.elem); //得到select原始DOM对象
            console.log(data.value); //得到被选中的值
            console.log(data.othis); //得到美化后的DOM对象
            table.reload('idTest',{
                limit:15,
                where:{
                    serverid:data.value,
                },
                page: {
                    curr: 1 //重新从第 1 页开始
                }
            });
        });
        //监听表格复选框选择
        table.on('checkbox(demo)', function(obj){
            console.log(obj.id)
        });
        form.on('radio(status)', function(data){
            var serverid = $("#selected").find("option:selected").val();
            table.reload('idTest',{
                limit:15,
                where:{
                    serverid:serverid,
                    status:data.value
                },
                page: {
                    curr: 1 //重新从第 1 页开始
                }
            });
        })

        //监听工具条
        table.on('tool(demo)', function(obj){
            var data = obj.data;
            var serverid = $("#selected").find("option:selected").val();

            if(obj.event === 'edit'){
                window.location.href="<?php echo url('role/edit'); ?>?id="+data.id+"&&serverid="+serverid;
            } else if(obj.event === 'del'){
                layer.confirm('数据无法恢复，确定执行删除操作？', function(index){
                    layui.use('jquery',function(){
                        var $=layui.$;
                        $.ajax({
                            type: 'post',
                            url: "<?php echo url('role/delAction'); ?>", // ajax请求路径
                            data: obj.data,
                            success: function(data){
                                if(data.code==0){
                                    layer.msg('删除成功！');
                                    obj.del();
                                    layer.close(index);
                                }else{
                                    layer.msg(data.msg);
                                }
                            }
                        });
                    });
                });
            } else if(obj.event === 'statusOff'){
                console.log(obj)


                layui.use('jquery',function(){
                    var $=layui.$;
                    $.ajax({
                        type: 'post',
                        url: "<?php echo url('serverproduct/changeStatus'); ?>", // ajax请求路径
                        data:{
                            "serverid" : serverid,
                            "productid" : data.id,
                            "status" : data.status,
                            "spid" : data.spid
                        },
                        success: function(data){
                            if(data.code==0){
                                table.reload('idTest',{

                                });
                                layer.alert('已绑定');
                            }else{
                                layer.msg(data.msg);
                            }
                        }
                    });
                });
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
                        name: demoReload
                    }
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });

    $(function () {

        $(".back").click(function () {
            window.history.back(-1);
            $("#selected").options[0].selected = true;
        })

        $(".blackAdd").click(function () {
            var serverid = $("#selected").find("option:selected").val();
            console.log(serverid);
            window.location.href="<?php echo url('Role/add'); ?>?serverid="+serverid
        })
    })

    //
    // window.onload = function(){
    //     if(window.name!="hasLoad"){
    //         location.reload();
    //         window.name = "hasLoad";
    //     }else{
    //         window.name="";
    //     }
    // }
</script>
