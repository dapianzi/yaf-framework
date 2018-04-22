<{extends 'public/base.tpl'}>

<{block name=title}>系统权限管理<{/block}>
<{block name=static}>
<style type="text/css">
    #type-radio-wrap .layui-unselect.layui-form-switch.layui-form-onswitch{
        border-color: #FF5722!important;
        background-color: #FF5722!important;
    }
    #type-radio-wrap .layui-unselect.layui-form-switch{
        border-color: #5FB878!important;
        background-color: #5FB878!important;
    }
    #type-radio-wrap .layui-unselect.layui-form-switch em{
        color: #ffffff!important;
    }
    .layui-unselect.layui-form-switch i{
        background-color: #ffffff!important;
    }
    .menu-tree{
        padding-left: 50px;
    }
    .menu-tree-leaf {
        display: inline-block;
    }
    .layui-form-checkbox.layui-form-checked.check-denied i{
        border-color: #FF5722;
        background-color: #FF5722;
    }
</style>
<{/block}>

<{block name=body}>
<div class="layui-fluid" >
    <div class="layui-card">
        <div class="layui-card-header">
            <h3>系统权限管理</h3>
        </div>
        <div class="layui-card-body" >
            <form class="layui-form" id="perm-edit-form" lay-filter="perm-edit-form" onsubmit="return false;">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">用户角色：</label>
                        <div class="layui-input-inline" id="roles-option-wrap"></div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">权限类型：</label>
                        <div class="layui-input-inline" id="type-radio-wrap"></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">菜单权限：</label>
                    <div class="layui-input-block" id="perm-checkbox-wrap"></div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="save-perm">保存设置</button>
                        <button type="reset" class="layui-btn layui-btn-primary" lay-submit lay-filter="reset-perm">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/html" id="roleTpl">
    <select name="role" class="layui-select" lay-filter="select-role">
        {{# for (var i in roles){ }}
        <option value="{{roles[i].id}}" {{ roles[i].id==d?"selected":"" }}>{{ roles[i].role }}</option>
        {{# } }}
    </select>
</script>
<script type="text/html" id="typeTpl">
    <input type="checkbox" name="type" lay-skin="switch" {{# if(d=='denied'){ }}checked{{# } }} lay-text="禁止|允许" lay-filter="perm-type-switch" />
</script>
<script type="text/html" id="permTpl">
    <li class="{{d.cls}}">
        <input type="checkbox" lay-skin="primary" name="perm[]" value="{{d.id}}" title="{{d.node}}" {{d.checked}} lay-filter="perm-tree-node" />
        {{d.children}}
    </li>
</script>
<{/block}>

<{block name=script}>
<script type="text/javascript">
    var roles = <{$roles}>;
    var perms = <{$perms}>;
    var menus = <{$menus}>;
</script>
<script type="text/javascript">
    layui.use(['form', 'c_comm', 'laytpl'], function(){
        var $ = layui.$,
            comm = layui.c_comm,
            form = layui.form,
            tpl = layui.laytpl
        // init roles
        var js_perm = {
            render_role: function(role) {
                tpl($('#roleTpl').html()).render(role, function(html){
                    $('#roles-option-wrap').html(html);
                });
                form.render('select');
                form.on('select(select-role)', function() {
                    js_perm.render_perm();
                });
            },
            render_perm: function() {
                var perm = perms[$('select[lay-filter="select-role"]').val()];
                // render type
                tpl($('#typeTpl').html()).render(perm.type, function(html){
                    $('#type-radio-wrap').html(html);
                    form.render('radio');
                });
                $('#perm-checkbox-wrap').html(js_perm.render_tree(perm.node, 0));
                this.render_checkbox();
            },
            render_tree: function(nodes, pid) {
                if (!menus[pid]) {
                    return '';
                }
                var _html = '<ul class="menu-tree">';
                for (var i in menus[pid]) {
                    var _children = this.render_tree(nodes, menus[pid][i].id);
                    var data = {
                        id: menus[pid][i].id,
                        node: menus[pid][i].node,
                        cls: menus[pid][i].is_show==1?'':'menu-tree-leaf',
                        checked: (nodes.indexOf(parseInt(menus[pid][i].id))>=0 ? "checked" : ""),
                        children: _children
                    }
                    _html += tpl($('#permTpl').html()).render(data);
                }
                _html += '</ul>';
                return _html;
            },
            render_checkbox: function() {
                form.render('checkbox');
                if ($('input[name="type"]').prop('checked') == true) {
                    $('#perm-checkbox-wrap .layui-form-checkbox').addClass('check-denied');
                } else {
                    $('#perm-checkbox-wrap .check-denied').removeClass('check-denied');
                }
            },
            init: function() {
                var role = location.hash.substr(1);
                role = role || -1;
                this.render_role(role);
                this.render_perm();
            }
        };
        // init tree
        js_perm.init();
        // switch perm type
        form.on('switch(perm-type-switch)', function(obj){
            $('input[name="perm[]"]').each(function(i, e){
                $(e).prop('checked', !$(e).prop('checked'));
            });
            js_perm.render_checkbox();
        });
        // auto check parents and children
        form.on('checkbox(perm-tree-node)', function(o){
            var checked = o.elem.checked;
            if (checked) {
                $(o.elem).parents('li').each(function(i, e){
                    $(e).find('>input').prop('checked', checked);
                });
                js_perm.render_checkbox();
            } else {
                $(o.elem).parent().find('input[name="perm[]"]').each(function(i, e){
                    $(e).prop('checked', checked);
                });
                js_perm.render_checkbox();
            }
        });
        // submit
        form.on('submit(save-perm)', function(obj) {
            comm.ajax("/admin/system/permUpdate/", obj.field, function(res){
                perms[obj.field.role] = res.data;
                location.hash = '#'+obj.field.role;
                comm.msg_ok('操作成功！');
                js_perm.init();
            }, function(res) {
                comm.alert_error(res.msg, function(){
                    js_perm.init();
                });
            });
            return false;
        });
        form.on('submit(reset-perm)', function(obj) {
            js_perm.init();
            return false;
        });
    });
</script>

<{/block}>

