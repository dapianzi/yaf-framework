<div class="layui-card-header">
    <span class="layui-breadcrumb">
        <{$node_nav|print_r}>
        <{if $node_nav.name==''}>
            <a><cite><{$nodeName}></cite></a>
        <{else}>
            <a href="<{$parentNode.url}>"><{$parentNode.name}></a>
            <a><cite><{$nodeName}></cite></a>
        <{/if}>
    </span>
</div>