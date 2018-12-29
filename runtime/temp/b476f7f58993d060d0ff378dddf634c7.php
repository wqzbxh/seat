<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\server\PHPTutorial\WWW\seat/application/admin\view\server\index.html";i:1545463007;s:72:"D:\server\PHPTutorial\WWW\seat\application\admin\view\layout\header.html";i:1545463008;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>分发运营管理平台</title>
    <link rel="stylesheet" href="/public/static/layui/css/layui.css">
    <link rel="stylesheet" href="/public/static/css/pai.css">
    <script src="/public/static/layui/layui.js"></script>
    <script src="/public/static/js/jquery-3.3.1.js"></script>

</head>
<blockquote class="layui-elem-quote layui-text">
    服务器列表&nbsp&nbsp&nbsp&nbsp&nbsp
    <!--<span class="back xhx"></span>-->
</blockquote>


<table class="layui-table" lay-data="{width: 1350, height:680, url:'<?php echo url('server/getServer'); ?>', page:true,limits:[15,30,45],limit:15, id:'idTest'}" lay-filter="demo">
    <div class=" demoTable layui-inline">
        <div class="layui-inline">
            <input class="layui-input" name="id" id="search" placeholder="请输入服务器名称">
        </div>
        <button class="layui-btn" data-type="reload">搜索</button>
        <a href="<?php echo url('server/add'); ?>"><button class="layui-btn margin-left10" style="margin-left: 10px;" >添加服务器</button></a>
    </div>
    <thead>
    <tr>
        <th lay-data="{field:'id', width:70}">ID</th>
        <th lay-data="{field:'servername', width:180}">服务器名称</th>
        <th lay-data="{field:'serverstatus', width:100,templet: '#serverstatus'}">状态</th>
        <th lay-data="{field:'inputcard', width:180}">入口网卡</th>
        <th lay-data="{field:'outcard', width:180}">回包网卡</th>
        <th lay-data="{field:'macaddress', width:180}">网关MAC地址</th>
        <th lay-data="{fixed: 'right', width:450, align:'center', toolbar: '#barDemo'}">操作项</th>
    </tr>
    </thead>
</table>


<script type="text/html" id="titleTpl">
    {{#  if(d.product_type ==0){ }}
    通匹
    {{#  } else if(d.product_type ==1){ }}
    基本
    {{#  } }}
</script>

<script type="text/html" id="serverstatus">
    {{#  if(d.serverstatus ==0){ }}
    <span class="c666 overstriking">未运行</span>
    {{#  } else if(d.serverstatus ==1){ }}

    <span class="c009688 overstriking"> 运行中</span>

    {{#  } }}
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="open">开启</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="detail">停止</a>
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="edit">修改</a>
    <a class="layui-btn layui-btn-xs" lay-event="copy" >复制</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    <a class="layui-btn layui-btn-xs" lay-event="order">命令</a>
    <a class="layui-btn layui-btn-xs" lay-event="download">下载规则</a>
    <a class="layui-btn layui-btn-xs" lay-event="flow" >流量</a>
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
            if(obj.event === 'flow'){
                window.location.href="<?php echo url('serverstatistics/flow'); ?>?serverid="+data.id;
            } else if(obj.event === 'del'){
                layer.confirm('数据无法恢复，确定执行删除操作？', function(index){
                    layui.use('jquery',function(){
                        var $=layui.$;
                        $.ajax({
                            type: 'post',
                            url: "<?php echo url('server/delAction'); ?>", // ajax请求路径
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
                window.location.href="<?php echo url('server/edit'); ?>?id="+data.id;
            } else if(obj.event === 'copy'){
                var id = data.id ;
                layer.confirm('确定复制？', function(index){
                    $.ajax({
                        type: 'post',
                        url: "<?php echo url('server/copy'); ?>", // ajax请求路径
                        data: {
                            serverid:id,
                        },
                        beforeSend: function () {
                            i = showLoad();
                        },
                        success: function(data){
                            if(data.code == 0){
                                closeLoad(i);
                                layer.msg('服务器'+id+'：复制成功');
                                table.reload('idTest');
                            }else if(data.code == 17002){
                                return layer.msg(data.msg, {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                            }else{
                                closeLoad(i);
                                return layer.msg('服务器'+id+'：复制失败...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                            }
                        }
                    })
                })
            } else if(obj.event === 'download'){
                var id = data.id ;
                layer.confirm('确定下载？', function(index){
                    layui.use('jquery',function(){
                        var i;
                        var $=layui.$;
                        var startime = parseInt(Date.parse(new Date()) / 1000);
                        $.ajax({
                            type: 'post',
                            url: "<?php echo url('server/otherOperateServer'); ?>", // ajax请求路径
                            data: {
                                serverid:id,
                                opcode:7
                            },
                            beforeSend: function () {
                                i = showLoad();
                            },
                            success: function(data){
                                if(data == 200){
                                    var wqzbxhz1 =  window.setInterval(function () {
                                        var q = 1;
                                        $.ajax({
                                            type: 'post',
                                            url: "<?php echo url('server/lookStatus'); ?>", // ajax请求路径
                                            data: {
                                                id:id,
                                                opcode:7
                                            },
                                            success: function(data){
                                                var nowtime = parseInt(Date.parse(new Date()) / 1000);
                                                var mistiming =  nowtime - startime;
                                                if(data.code == 0){
                                                    window.location.href="<?php echo url('common/common/downLoadFile'); ?>?path=rulefile/DecryptFile/&&file=out_"+id+".xml&&zipname=Server"+id+"XML";//下载文件
                                                   window.clearInterval(wqzbxhz1);
                                                    layer.msg('服务器'+id+'：XML文件正在下载！', {
                                                        offset: '6px'
                                                    });
                                                    closeLoad(i);
                                                    table.reload('idTest');
                                                }
                                                if(mistiming > 60){
                                                    window.clearInterval(wqzbxhz1);
                                                    closeLoad(i);
                                                    return layer.msg('服务器'+id+'：下载XML文件超时...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                                }
                                            }
                                        })
                                    }, 2000);
                                }else if(data.code == 17002){
                                    return layer.msg(data.msg, {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }else{
                                    closeLoad(i);
                                    return layer.msg('服务器'+id+'：向服务器发出请求失败（'+data+')', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }
                            }
                        });
                    });
                });
            }  else if(obj.event === 'detail'){
                var id = data.id ;
                layer.confirm('确定停止？', function(index){
                    layui.use('jquery',function(){
                        var i;
                        var $=layui.$;
                        var startime = parseInt(Date.parse(new Date()) / 1000);
                        $.ajax({
                            type: 'post',
                            url: "<?php echo url('server/operateServer'); ?>", // ajax请求路径
                            data: {
                                serverid:id,
                                opcode:5
                            },
                            beforeSend: function () {
                                i = showLoad();
                            },
                            success: function(data){
                                if(data == 200){
                                    var c =  window.setInterval(function () {
                                        var q = 1;
                                        $.ajax({
                                            type: 'post',
                                            url: "<?php echo url('server/lookStatus'); ?>", // ajax请求路径
                                            data: {
                                                id:id
                                            },
                                            success: function(data){
                                                var nowtime = parseInt(Date.parse(new Date()) / 1000);
                                                var mistiming =  nowtime - startime;
                                                if(data.code == 0){
                                                    window.clearInterval(c);
                                                    layer.msg('服务器'+id+'：已停止！', {
                                                        offset: '6px'
                                                    });
                                                    closeLoad(i);
                                                    table.reload('idTest');
                                                }
                                                if(mistiming > 60){
                                                    window.clearInterval(c);
                                                    closeLoad(i);
                                                    return layer.msg('服务器'+id+'：停止超时...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                                }
                                            }
                                        })
                                    }, 2000);
                                }else if(data.code == 17002){
                                    return layer.msg(data.msg, {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }else{
                                    closeLoad(i);
                                    return layer.msg('服务器'+id+'：向服务器请求失败（'+data+')', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }
                            }
                        });
                    });
                });
            } else if(obj.event === 'open'){
                var id = data.id ;
                layer.confirm('确定开启？', function(index){
                    layui.use('jquery',function(){
                        var i;
                        var $=layui.$;
                        $.ajax({
                            type: 'post',
                            url: "<?php echo url('server/generateRuleXml'); ?>", // ajax请求路径
                            data: obj.data,
                            beforeSend: function () {
                                i = showLoad();
                            },
                            success: function(data){
                                console.log(data);
                                if(data == 200){
                                    var startime = parseInt(Date.parse(new Date()) / 1000);
                                    var c =  window.setInterval(function () {
                                        var q = 1;
                                        $.ajax({
                                            type: 'post',
                                            url: "<?php echo url('server/lookStatus'); ?>", // ajax请求路径
                                            data: {
                                                id:id
                                            },
                                            success: function(data){
                                                var nowtime = parseInt(Date.parse(new Date()) / 1000);
                                                var mistiming =  nowtime - startime;
                                                if(data.code == 0){
                                                    window.clearInterval(c);
                                                    closeLoad(i);
                                                    layer.msg('服务器'+id+'：已开启！', {
                                                        offset: '6px'
                                                    });
                                                    table.reload('idTest');
                                                }
                                                if(mistiming > 60){
                                                    window.clearInterval(c);
                                                    closeLoad(i);
                                                    return layer.msg('服务器'+id+'：开启超时...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                                }
                                                q++;
                                            }
                                        })
                                    }, 2000);
                                }else if (data == 12001){
                                    window.clearInterval(c);
                                    closeLoad(i);
                                    return layer.msg('服务器'+id+'：加密文件失败...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }else if(data.code == 17002){
                                    return layer.msg(data.msg, {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }else{
                                    window.clearInterval(c);
                                    closeLoad(i);
                                    console.log(data);
                                    return layer.msg('服务器'+id+'：向服务器请求失败（'+data+')', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                }
                            }
                        });
                    });
                });
            }else if(obj.event === 'order'){
                var id = data.id ;
                var open = layer.open({
                    type: 1 //Page层类型
                    ,btn:["确定","取消"]
                    ,title:'命令填写'
                    ,skin: 'layui-layer-prompt'
                    ,area:['830px','700px;']
                    ,content: "<form><div class='layui-form'>" +
                        "<textarea class='layui-textarea' style='height:100px;margin-top:5px;' placeholder='请输入命令'></textarea>" +
                        "<textarea class='layui-textarea' style='height:450px;margin-top:5px;' id='cmdresult' placeholder='执行结果' disabled></textarea></div></form>"
                    ,yes: function(index, layero){
                        var childrule_push_content = $(layero).find("textarea").val()
                        layui.use('jquery',function(){
                            var $=layui.$;
                            $.ajax({
                                type: 'post',
                                url: "<?php echo url('server/generateRuleCmd'); ?>", // ajax请求路径
                                beforeSend: function () {
                                    i = showLoad();
                                },
                                data:{
                                    "order" : childrule_push_content,
                                    "id" : id,
                                    'opcode':10
                                },
                                success: function(data){
                                    console.log(data);
                                    if(data == 200){
                                        var startime = parseInt(Date.parse(new Date()) / 1000);
                                        var c =  window.setInterval(function () {
                                            var q = 1;
                                            $.ajax({
                                                type: 'post',
                                                url: "<?php echo url('server/upgradeLookStatus'); ?>", // ajax请求路径
                                                data: {
                                                    id:id
                                                },
                                                success: function(data){
                                                    var nowtime = parseInt(Date.parse(new Date()) / 1000);
                                                    var mistiming =  nowtime - startime;
                                                    if(data.code == 0){
                                                        window.clearInterval(c);
                                                        closeLoad(i);
                                                        layer.msg('服务器'+id+'：命令生成！', {
                                                            offset: '6px'
                                                        });
                                                        $('#cmdresult').text(data.data)
                                                    }else if(data.code == 12004){
                                                        window.clearInterval(c);
                                                        closeLoad(i);
                                                        layer.close(index);
                                                        layer.msg(data.data);
                                                    }

                                                    if(mistiming > 60){
                                                        window.clearInterval(c);
                                                        closeLoad(i);
                                                        return layer.msg('服务器'+id+'：命令生成超时...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                                    }
                                                    q++;
                                                }
                                            })
                                        }, 2000);
                                    }else if (data.code == 12003){
                                        window.clearInterval(c);
                                        closeLoad(i);
                                        return layer.msg('服务器'+id+'：命令程序执行失败...', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                    }else if(data.code == 17002){
                                        closeLoad(open);
                                        closeLoad(i);
                                        return layer.msg(data.msg, {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                    }else {
                                        window.clearInterval(c);
                                        closeLoad(i);
                                        return layer.msg('服务器'+id+'：命令生成失败...（'+data+')', {icon: 0,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: 'auto', time:2000});
                                    }
                                }
                            });
                        });
                    }
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
                        servername: demoReload
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