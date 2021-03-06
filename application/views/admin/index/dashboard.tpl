<{extends 'public/base.tpl'}>

<{block name=title}>控制台主面板<{/block}>
<{block name=static}><{/block}>

<{block name=body}>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">基本环境</div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <colgroup>
                            <col width="30%">
                            <col>
                        </colgroup>
                        <tbody>
                        <tr>
                            <td>当前版本</td>
                            <td>v1.0.0-alpha</td>
                        </tr>
                        <tr>
                            <td>基于框架</td>
                            <td>Yaf/Smarty/LayUI</td>
                        </tr>
                        <tr>
                            <td>PHP 版本</td>
                            <td><{$env.php_version}></td>
                        </tr>
                        <tr>
                            <td>MySQL 版本</td>
                            <td><{$env.mysql_version}></td>
                        </tr>
                        <tr>
                            <td>Yaf 版本</td>
                            <td><{$env.yaf_version}></td>
                        </tr>
                        <tr>
                            <td>Yaf 环境</td>
                            <td><strong><{$env.yaf_env}></strong></td>
                        </tr>
                        <tr>
                            <td>最后登录</td>
                            <td><strong><{$user.last_login}></strong></td>
                        </tr>
                        <tr>
                            <td>登录IP</td>
                            <td><strong><{$user.last_login_ip}></strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<{/block}>

