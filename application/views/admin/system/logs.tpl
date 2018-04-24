<{extends 'public/base.tpl'}>

<{block name=title}>系统日志<{/block}>
<{block name=body}>
<div class="layui-fluid">
    <div class="layadmin-content">
        <div class="demoTable layui-form">
            <div class="layui-inline" style="width: 350px;">
                <input class="layui-input" name="time" id="time" placeholder="yyyy-MM-dd HH:mm:ss - yyyy-MM-dd HH:mm:ss">
            </div>

            <button class="layui-btn" data-action="reload">搜索</button>
            <button class="layui-btn layui-btn-danger" data-action="del">删除一月前数据</button>
        </div>
        <table class="layui-table" lay-data="{height: 'full-100',cellMinWidth: 80,url:'/admin/system/logsList/', id:'log-table',page: true,limit:20}" lay-filter="log-table">
            <thead>
            <tr>
                <th lay-data="{field:'adate',sort:true,width:180}">时间</th>
                <th lay-data="{field:'username'}">用户</th>
                <th lay-data="{field:'uri'}">请求地址</th>
                <th lay-data="{field:'data'}">请求数据</th>
                <!--<th lay-data="{field:'action',width:120}">行为</th>-->
                <!--<th lay-data="{field:'result',width:100}">操作结果</th>-->
                <th lay-data="{field:'details'}">细节</th>
                <th lay-data="{field:'ip',width:160}">IP</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<{/block}>

<{block name=script}>
<script>
    layui.use(['table','c_comm','laydate'], function(){
        var $ = layui.$
            ,laydate = layui.laydate
            ,table = layui.table
            ,comm = layui.c_comm;
        laydate.render({
            elem: '#time'
            ,type: 'datetime'
            ,calendar: true
            ,range: true
        });
        var $ = layui.$, js_logs = {
            reload: function(){
                var time = $('#time').val().split(' - ');
                //执行重载
                table.reload('log-table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        from: time[0] || '',
                        to: time[1] || ''
                    }
                });
            },
            del: function(){
                comm.confirm('确定删除数据么', function(){
                    comm.ajax("/admin/system/logsClean/", {"clean": 1}, function(data){
                        js_logs.reload();
                    });
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('action');
            js_logs[type] && js_logs[type]();
        });

    });
</script>
<{/block}>

