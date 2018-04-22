<{extends 'public/base.tpl'}>

<{block name=title}>系统出错了 - <{$exception->getCode()}><{/block}>

<{block name=body}>
<div class="layui-fluid">
    <h2>
        <i class="layui-icon" face style="font-size: 45px;">&#xe664;</i> 系统出错了
        <strong>[<{$exception|get_class}>(<{$exception->getCode()}>)]</strong>
    </h2>
    <div class="layadmin-tips">
        <div class="layui-text" style="width:100%; text-align: left;line-height: 45px;">
            <p style="color:#FF5722;"><{$exception->getMessage()}></p>
        </div>
    </div>
</div>

<{/block}>
