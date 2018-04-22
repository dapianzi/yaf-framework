<{extends file='public/base.tpl'}>

<{block name=title}>用户登录<{/block}>

<{block name=static}>
<link rel="stylesheet" href="/css/login.css" media="all">
<{/block}>

<{block name=body}>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>Dapianzi Admin</h2>
            <p>dapianzi 后台管理模板系统</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <form action="/admin/account/onlogin/" method="post" id="login-form">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
                        <input type="text" name="captcha_code" id="LAY-user-login-vercode" lay-verify="required" placeholder="图形验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="/admin/account/captcha/?action=login&t=<{''|time}>" class="layadmin-user-login-codeimg" id="LAY-user-get-vercode">
                        </div>
                    </div>
                </div>
            </div>
            <{*<div class="layui-form-item" style="margin-bottom: 20px;">*}>
                <{*<input type="checkbox" name="remember" lay-skin="primary" title="记住密码">*}>
                <{*<a href="/admin/account/forget/" class="layadmin-user-jump-change layadmin-link" style="margin-top: 7px;">忘记密码？</a>*}>
            <{*</div>*}>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" id="on-login" type="button" lay-filter="LAY-user-login-submit">登 入</button>
            </div>
            </form>
        </div>
    </div>
</div>
<{/block}>

<{block name=script}>
<script type="text/javascript">
    layui.use(['c_comm', 'form'], function() {
        var form = layui.form,comm=layui.c_comm,$=layui.$;
        $('#on-login').on('click', function(e){
            var $form = $('#login-form'),
                requires = $form.find('input.layui-input[lay-verify="required"]');
            requires.each(function(i, e){
                if ('' === $(e).val()) {
                    comm.msg_error('必填的字段不能为空！');
                    $(e).addClass('layui-form-danger').focus();
                    return false;
                }
            });
            comm.ajax_form($form, function(res){
                location.href = '/admin/index/index/';
            });
        });
        $('#LAY-user-get-vercode').on('click', function() {
            $(this).attr('src', '/admin/account/captcha/?action=login&t='+new Date().getTime())
        })
    });
</script>
<{/block}>