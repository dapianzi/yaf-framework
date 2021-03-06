<{extends 'public/base.tpl'}>

<{block name=title}>Dapianzi Admin<{/block}>

<{block name=static}><{/block}>

<{block name=body}>
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <!--<li class="layui-nav-item layui-hide-xs" lay-unselect>-->
                <!--<a lay-href="//www.layui.com/doc/"  target="_blank" title="layUI Admin">-->
                <!--<i class="layui-icon layui-icon-website"></i> <span>LayUI Admin</span>-->
                <!--</a>-->
                <!--</li>-->
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
                <!--<li class="layui-nav-item" lay-unselect>-->
                <!--<a lay-href="/index/message/" tppabs="/index/message/" layadmin-event="message" lay-text="消息中心">-->
                <!--<i class="layui-icon layui-icon-notice"></i>  -->
                <!--&lt;!&ndash; 如果有新消息，则显示小圆点 &ndash;&gt;-->
                <!--<{if $unread_messages}>-->
                <!--<span class="layui-badge-dot"></span>-->
                <!--<{/if}>-->
                <!--</a>-->
                <!--</li>-->
                <li class="layui-nav-item" lay-unselect>
                    <{if $user}>
                    <a href="javascript:;">
                        <cite><{$user.username}></cite>
                    </a>
                    <dl class="layui-nav-child">
                        <!--<dd><a lay-href="/account/info/" tppabs="/account/info/">基本资料</a></dd>-->
                        <dd><a lay-href="/admin/account/password/" tppabs="/admin/account/password/">修改密码</a></dd>
                        <dd style="text-align: center;"><a href="/admin/account/logout/">退出</a></dd>
                    </dl>
                    <{else}>
                    <a href="/admin/account/login/">登录</a>
                    <{/if}>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>
        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="/admin/index/dashboard/" tppabs="/admin/index/dashboard/">
                    <span>Carl的管理平台框架</span>
                </div>
                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu"></ul>
            </div>
        </div>
        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="/admin/index/dashboard/" class="layui-this"><i class="layui-icon layui-icon-home"></i>
                    </li>
                </ul>
            </div>
        </div>
        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="/admin/index/dashboard/" tppabs="/admin/index/dashboard/" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>
        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>
<{/block}>

<{block name=script}>
<script type="text/javascript">
    (function(data){
        var render_tree = function(id){
            var _html = '';
            for (var i in data[id]) {
                var _item = data[id][i],
                    has_children = (data[_item.id] && data[_item.id].length > 0),
                    _href = has_children ? '' : 'lay-href="'+ _item.href +'"';
                if (id == '0') {
                    _html += '<li data-name="'+ _item.node +'" class="layui-nav-item layui-nav-itemed">' +
                        '<a href="javascript:;" lay-tips="'+ _item.node +'" '+_href+' lay-direction="2">' +
                        '<i class="layui-icon layui-icon-'+ _item.icon +'"></i>' +
                        '<cite>'+ _item.node +'</cite>' +
                        '</a>';
                } else {
                    _html += '<dd data-name="'+ _item.node +'">'
                    if (has_children) {
                        _html += '<a href="javascript:;">'+ _item.node +'</a>';
                    } else {
                        _html += '<a href="javascript:;" lay-text="'+ _item.node +'" '+ _href +'>'+ _item.node +'</a>';
                    }
                }
                if (has_children) {
                    _html += '<dl class="layui-nav-child">' + render_tree(_item.id) +'</dl>';
                }
                _html += id=='0' ? '</li>' : '</dd>';
            }
            return _html;
        }
        document.getElementById('LAY-system-side-menu').innerHTML = render_tree('0');
    })(<{$menu}>);
</script>
<script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>
<{/block}>
