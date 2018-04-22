<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><{block name=title}>此处应有标题<{/block}></title>
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/css/admin.css" media="all">

    <!-- static files -->
    <{block name=static}><{/block}>
</head>
<body class="layui-layout-body">
    <{block name=body}>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">模板继承</div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <colgroup>
                                <col width="160">
                                <col>
                            </colgroup>
                            <tbody>
                            <tr>
                                <td>block title</td>
                                <td>页面标题</td>
                            </tr>
                            <tr>
                                <td>block static</td>
                                <td>头部静态资源</td>
                            </tr>
                            <tr>
                                <td>block body</td>
                                <td>页面内容</td>
                            </tr>
                            <tr>
                                <td>block script</td>
                                <td>尾部 JS 代码</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <{/block}>
    <script src="/layuiadmin/layui/layui.js"></script>
    <script>
        layui.config({
            base: '/' //静态资源所在路径
        }).extend({
            /**
             * 建议模块名称加个cus_前缀区分框架自身的还是自定义的
             * 例如： cus_echarts
             */
            c_comm: 'js/lib/comm',//配置comm模块的路径
            c_echarts:'js/lib/echarts'
        });
    </script>
    <{block name=script}><{/block}>
</body>
</html>