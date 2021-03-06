<{extends file='public/base.tpl'}>

<{block name=title}>交换机配置脚本<{/block}>

<{block name=static}><{/block}>

<{block name=body}>
<div class="layui-fluid">
    <div class="layadmin-content layui-row layui-col-space15">
        <div class="layui-col-md6">
            <fieldset class="layui-elem-field layui-field-title site-title"><legend>输入工单号</legend></fieldset>
            <form action="" method="get">
                <div class="layui-col-md6">
                    <input type="text" name="q" placeholder="请输入工单号" autocomplete="off" class="layui-input" value="<{$q}>">
                </div>
                <button class="layui-btn" lay-submit="" lay-filter="component-form-element">确定</button>
            </form>
            <{if isset($article_info)}>
            <fieldset class="layui-elem-field layui-field-title site-title">
                <legend><a name="default">工单信息</a></legend>
            </fieldset>
            <table class="layui-table">
                <colgroup>
                    <col width="180">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>参数名</th>
                    <th>参数值</th>
                </tr>
                </thead>
                <tbody>
                <{foreach $article_info as $k=>$v}>
                <tr>
                    <td><{$k}></td>
                    <td><strong><{$v}></strong></td>
                </tr>
                <{/foreach}>
                </tbody>
            </table>
            <{/if}>
        </div>
        <div class="layui-col-md6">
            <fieldset class="layui-elem-field layui-field-title site-title">
                <legend>交换机脚本</legend>
            </fieldset>
            <{if isset($template_err)}>
            <p style="color:#FF5722;"><i class="layui-icon layui-icon-close-fill"></i> <span><{$template_err}></span></p>
            <{/if}>
            <{if isset($switch_script)}><pre class="layui-code"><{$switch_script}></pre><{/if}>
        </div>

    </div>
</div>
<{/block}>

<{block name=script}>
<script type="application/javascript">
    layui.use('code', function(){ //加载code模块
        layui.code({
            title: '脚本内容'
            ,about: false
//            ,encode: true //html转码交给服务端
        }); //引用code方法
    });
    var $=layui.$;
</script>
<{/block}>