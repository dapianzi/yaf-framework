<{extends 'public/base.tpl'}>

<{block name=title}>忘记密码<{/block}>

<{block name=static}>
<link rel="stylesheet" href="/css/login.css" media="all">
<{/block}>

<{block name=body}>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-body">
            <form class="layui-form" id="set-password-form" method="post" action="/admin/account/setpassword/">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="old_pass" id="LAY-user-login-oldpass" lay-verify="required" placeholder="原密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                <input type="password" name="new_pass" id="LAY-user-login-password" lay-verify="required" placeholder="新密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-repass"></label>
                <input type="password" name="repass" id="LAY-user-login-repass" lay-verify="required" placeholder="确认密码" class="layui-input">
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" id="set-password" type="button" lay-filter="LAY-user-forget-resetpass">重置新密码</button>
            </div>
            </form>
        </div>
    </div>
</div>
<{/block}>
<{block name=script}>
<script type="text/javascript">
    layui.use(['c_comm', 'form'], function(){
        var form = layui.form
            ,$ = layui.$
            ,comm = layui.c_comm;
        $('#set-password').on('click', function(){
            var $form = $('#set-password-form')
                , requires = $form.find('input.layui-input[lay-verify="required"]');
            requires.each(function(i, e){
                if ('' === $(e).val()) {
                    comm.msg_error('必填的字段不能为空！');
                    $(e).addClass('layui-form-danger').focus();
                    return false;
                }
            });
            if ($('#LAY-user-login-password').val() !== $('#LAY-user-login-repass').val()) {
                comm.msg_error('确认密码不一致！');
                $('#LAY-user-login-repass').addClass('layui-form-danger').focus();
                return false;
            }
            comm.ajax_form($form, function(res){
                window.parent.location.href = '/admin/account/logout/';
            });

        });
    });
</script>
<{/block}>