<{extends 'public/base.tpl'}>

<{block name=title}>系统用户管理<{/block}>

<{block name=body}>
<div class="layui-fluid">
    <div class="layadmin-content">
        <div class="dealTable">
            <div class="layui-btn-group ">
                <button class="layui-btn" id="add-admin" data-action="add-admin"><i class="layui-icon layui-icon-tianjia2"></i>添加系统用户</button>
                <button class="layui-btn" id="add-role" data-action="add-role"><i class="layui-icon layui-icon-tianjia2"></i>添加系统角色</button>
            </div>
        </div>
        <table class="layui-table" lay-data="{cellMinWidth:60,url:'/admin/system/adminList/',id:'user-admin-table'}" lay-filter="user-admin-table">
            <thead>
            <tr>
                <th lay-data="{field:'id', width:80}">ID</th>
                <th lay-data="{field:'username'}">用户名</th>
                <th lay-data="{field:'nickname', edit: 'text'}">昵称</th>
                <th lay-data="{field:'role', event: 'setRole', style:'cursor: pointer;'}">角色</th>
                <th lay-data="{field:'email', edit: 'text'}">邮箱</th>
                <th lay-data="{field:'status',width: 100, templet: '#statusTpl', unresize: true}">状态</th>
                <th lay-data="{field:'last_login'}">最后登录时间</th>
                <th lay-data="{field:'last_login_ip'}">最后登录IP</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/html" id="roleTpl">[{{ d.role_id }}] {{ d.role }}</script>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{ d.id }}" lay-skin="switch" lay-text="开启|关闭" lay-filter="status" {{ d.status == -1 ? '' : 'checked' }} >
</script>
<script type="text/html" id="editRoleTpl">
    <form class="layui-form layui-fluid" onsubmit="return false;" lay-filter="edit-role-form">
        <div class="layui-form-item">
            <label class="layui-form-label">角色：</label>
            <div class="layui-input-block">
                {{# for (var i in roles){ }}
                <input type="radio" name="role_id" value="{{ roles[i].id }}" title="{{ roles[i].role }}" {{ d.role_id==roles[i].id ? 'checked' : '' }} />
                {{# } }}
            </div>
        </div>
        <input type="hidden" name="id" value="{{ d.id }}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-submit lay-filter="set">确定</button>
                <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary" lay-submit lay-filter="cancel">取消</button>
            </div>
        </div>
    </form>
</script>
<script type="text/html" id="editAdminTpl">
    <form class="layui-form layui-fluid" onsubmit="return false;" lay-filter="edit-admin-form">
        <input type="hidden" name="id" value="{{ d.id }}" />
        <div class="layui-form-item">
            <label class="layui-form-label">用户名：</label>
            <div class="layui-input-block">
                <input type="text" name="username" value="{{ d.username }}" lay-verify="required" autocomplete="off" class="layui-input" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色：</label>
            <div class="layui-input-block">
                <select name="role_id" class="layui-select">
                    {{# for (var i in roles){ }}
                    <option value="{{ roles[i].id }}" {{ d.role_id==roles[i].id ? "checked" : "" }} >{{ roles[i].role }}</option>
                    {{# } }}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码：</label>
            <div class="layui-input-block">
                <input type="password" name="password" value="{{ d.password }}" lay-verify="required" autocomplete="off" class="layui-input" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码：</label>
            <div class="layui-input-block">
                <input type="password" name="repass" value="" lay-verify="required" autocomplete="off" class="layui-input" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称：</label>
            <div class="layui-input-block">
                <input type="text" name="nickname" value="{{ d.nickname }}" autocomplete="off" class="layui-input" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱：</label>
            <div class="layui-input-block">
                <input type="text" name="email" value="{{ d.email }}" autocomplete="off" class="layui-input" />
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-submit lay-filter="add-admin">确定</button>
                <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary" lay-submit lay-filter="cancel">取消</button>
            </div>
        </div>
    </form>
</script>
<{/block}>

<{block name=script}>
<script type="text/javascript">
    var roles = <{$roles}>;
</script>
<script type="text/javascript">
    layui.use(['table', 'c_comm'], function(){
        var $ = layui.$,
            table = layui.table,
            form = layui.form,
            laytpl = layui.laytpl,
            layer = layui.layer,
            comm = layui.c_comm,
            _layer = null;

        table.on('tool(user-admin-table)', function(obj){
            var data = obj.data;
            if(obj.event === 'setRole'){
                laytpl($('#editRoleTpl').html()).render(data, function(html){
                    _layer = comm.open({
                        title: '修改 <strong>['+ data.username +']</strong> 的用户角色',
                        area: '40%', //宽高
                        offset: '20%',
                        content: html
                    });
                    form.render('radio');
                });
            }
        });
        //监听单元格编辑
        table.on('edit(user-admin-table)', function(obj){
            var value = obj.value, //得到修改后的值
                field = obj.field, //得到字段键值
                data = obj.data; //得到所在行所有键值
            comm.ajax("/admin/system/adminState/", {"id": data.id, "field": field, "value": value});
        });
        $('#add-admin').on('click', function(e) {
            laytpl($('#editAdminTpl').html()).render({id:0,username:'',password:'',nickname:'',email:'',role_id:0}, function(content){
                _layer = comm.open({
                    area: '40%',
                    offset: '20%',
                    title: '添加系统用户',
                    content: content
                });
                form.render('select');
            });
        });
        $('#add-role').on('click', function(e) {
            layer.prompt({
                title: '添加用户角色', //输入框类型，支持0（文本）默认1（密码）2（多行文本）
            }, function(value, index, elem){
                comm.ajax("/admin/system/adminRole/", {"action":"add","role":value}, function(res){
                    roles.push(res.data);
                    layer.close(index);
                });
            });
        });
        //监听是否菜单操作
        form.on('switch(status)', function(obj){
            comm.ajax('/admin/system/adminState/', {'id': obj.value, 'field': 'status', "value": obj.elem.checked});
        });
        form.on('submit(set)', function(obj){
            comm.ajax("/admin/system/adminState/", {"id": obj.field.id, "field": "role_id", "value": obj.field.role_id}, function(){
                layer.close(_layer);
                table.reload('user-admin-table');
                return false;
            });
        });
        form.on('submit(cancel)', function(){
            layer.close(_layer);
            return false;
        });
        form.on('submit(add-admin)', function(obj){
            if (obj.field.password !== obj.field.repass) {
                comm.msg_error('确认密码不一致');
                return false;
            }
            comm.ajax("/admin/system/adminSave/", obj.field, function(res){
                layer.close(_layer);
                table.reload('user-admin-table');
                return false;
            });
            return false;
        });
    });
</script>
<{/block}>

