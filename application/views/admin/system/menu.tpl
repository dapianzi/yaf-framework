<{extends 'public/base.tpl'}>

<{block name=title}>系统菜单管理<{/block}>

<{block name=static}>
<style type="text/css">
    .radio-icon {
        display: inline-block; margin-right: 10px;
    }
    .radio-icon .layui-form-radio{
        padding: 0px;
        margin: 6px 0px 0 4px;
        display: inline;
    }
    .layui-icon-none:before {
        content: '无';
    }
</style>
<{/block}>

<{block name=body}>
<div class="layui-fluid">
    <div class="layadmin-content">
        <div class="dealTable">
            <!--<div class="layui-inline">
                <input class="layui-input" name="name" id="name" autocomplete="off" placeholder="节点名称">
            </div>
            <button class="layui-btn" data-type="search">搜索</button>-->
            <div class="layui-btn-group ">
                <button class="layui-btn" data-action="add-menu"><i class="layui-icon">&#xe654;</i>添加新菜单</button>
                <button class="layui-btn layui-btn-danger" data-action="delete-menu"><i class="layui-icon">&#xe640;</i>删除所选</button>
            </div>
        </div>
        <table class="layui-table" style="min-width: 1200px;" lay-data="{height: 'full-120',cellMinWidth: 80,url:'/admin/system/menuList/', id:'menu-table',skin:'line'}" lay-filter="menu-table">
            <thead>
            <tr>
                <th lay-data="{type:'checkbox'}">ID</th>
                <!--<th lay-data="{field:'id', width:80}">ID</th>-->
                <th lay-data="{field:'list_order', width:60, edit: 'text'}">排序</th>
                <th lay-data="{field:'icon', width: 60, templet: '#iconTpl',align: 'right'}">图标</th>
                <th lay-data="{field:'level', width: 100, align: 'right'}">节点层级</th>
                <th lay-data="{field:'node', event: 'edit-menu', style:'cursor:pointer;color:#01AAED;text-decoration:underline;'}">节点名称</th>
                <th lay-data="{field:'href'}">菜单链接</th>
                <th lay-data="{field:'perm_route'}">权限路由</th>
                <th lay-data="{field:'status',width: 90,templet: '#statusTpl', unresize: true}">是否启用</th>
                <th lay-data="{field:'is_show',width: 90,templet: '#showTpl', unresize: true}">是否菜单</th>
                <th lay-data="{field:'right',toolbar: '#barMenu', fixed:'right', width:180}">操作</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="menu-state" {{ d.status == -1 ? '' : 'checked' }}>
</script>
<script type="text/html" id="showTpl">
    <input type="checkbox" name="is_show" value="{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="menu-state" {{ d.is_show == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="iconTpl">
    <i class="layui-icon layui-icon-{{d.icon}}"></i>
</script>
<script type="text/html" id="barMenu">
    <a class="layui-btn layui-btn-xs" lay-event="add-menu">子菜单</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit-menu">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del-menu">删除</a>
</script>
<script type="text/html" id="editMenuForm">
    <form class="layui-form layui-fluid" action="/admin/system/menuSave/" lay-filter="menu-edit-form">
        <input type="hidden" name="id" value="{{ d.id }}" />
        <div class="layui-col-md6">
            <div class="layui-form-item">
                <label class="layui-form-label">菜单名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="node" value="{{ d.node }}" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">菜单链接：</label>
                <div class="layui-input-block" >
                    <input type="text" name="href" value="{{ d.href }}" title="菜单链接" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">是否菜单：</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_show" lay-skin="switch" lay-filter="switchShow" lay-text="是|否" {{# if(d.is_show == '1'){ }} checked {{# } }} />
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-form-item">
                <label class="layui-form-label">上级菜单：</label>
                <div class="layui-input-block">
                    <input type="text" name="pnode" class="layui-input layui-disabled" disabled value="{{ d.pnode }}" />
                    <input type="hidden" name="pid" value="{{ d.pid }}" />
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序权值：</label>
                <div class="layui-input-block" style="width: 60px;">
                    <input type="text" name="list_order" value="{{ d.list_order }}" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">是否启用：</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="status" lay-skin="switch" lay-filter="switchStatus" lay-text="是|否" {{# if(d.status == '0'){ }} checked {{# } }} />
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">菜单图标：</label>
            <div class="layui-input-block">
                <ul>
                    {{# for(var i in icons){ }}
                    <li class="radio-icon">
                        <i class="layui-icon layui-icon-{{ icons[i] }}"></i>
                        <input type="radio" name="icon" value="{{ icons[i] }}" {{# if(d.icon == icons[i]){ }} checked {{# } }} />
                    </li>
                    {{# } }}
                    <li class="radio-icon">
                        <i class="layui-icon layui-icon-none"></i>
                        <input type="radio" name="icon" value="" />
                    </li>
                </ul>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限路由：</label>
            <div class="layui-input-block" style="width: 480px;">
                <textarea name="perm_route" autocomplete="off" class="layui-textarea">{{ d.perm_route }}</textarea>
                <div class="layui-form-mid layui-word-aux">多个路由用英文逗号（,）隔开</div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-primary" lay-submit>确认</button>
                <button type="reset" class="layui-btn">重置</button>
            </div>
        </div>
    </form>
</script>
<{/block}>

<{block name=script}>
<script type="text/javascript">
    var icons = <{$icons}>;
</script>
<script type="text/javascript">
    layui.use(['table', 'c_comm', 'form', 'laytpl'], function(){
        var table = layui.table
            ,form = layui.form
            ,laytpl = layui.laytpl
            ,$ = layui.$
            ,comm = layui.c_comm;
        var js_menu = {
            action_save: function(data) {
                data = $.extend({
                    id:0,node:'',pid:0,pnode:'--顶层菜单--',href:'',perm_route:'',is_show:0,status:0,icon: '',list_order:0,
                }, data);
                laytpl($('#editMenuForm').html()).render(data, function(content){
                    comm.open({
                        'title': '编辑/修改菜单',
                        'content': content
                    });
                    form.render('radio');
                })
            },
            action_del: function(ids, cb) {
                comm.ajax("/admin/system/menuDel/", {"id": ids}, function(){
                    comm.msg_ok('删除成功！');
                    cb && cb();
                });
            }
        };
        //监听是否菜单操作
        form.on('switch(menu-state)', function(obj){
            comm.ajax("/admin/system/menuState/",
                {
                    "id": obj.value,
                    "type": $(obj.elem).attr('name'),
                    "checked": $(obj.elem).prop('checked')
                },
                function(res){
                    comm.msg_ok('操作成功！');
                }
            );
        });
        form.on('submit(menu-edit-form)', function(data){
            comm.ajax_form($(data.elem), function(){
                location.reload();
            });
            return false;
        })
        //监听工具条
        table.on('tool(menu-table)', function(obj){
            var data = obj.data;
            if (obj.event === 'del-menu') {
                comm.confirm('确定删除 ['+ data.node +'] 么？', function(){
                    js_menu.action_del(data.id, function(){
                        obj.del();
                    });
                });
            } else if (obj.event === 'edit-menu'){
                js_menu.action_save(obj.data);
            } else if (obj.event === 'add-menu'){
                js_menu.action_save({'pid': obj.data.id, 'pnode': obj.data.node});
            }
        });
        $('.dealTable .layui-btn').on('click', '', function(){
            switch ($(this).data('action')) {
                case 'add-menu':
                    return js_menu.action_save(); break;
                case 'delete-menu':
                    var checks = table.checkStatus('menu-table').data
                        ,ids = [];
                    for (var i in checks) {
                        ids.push(checks[i].id);
                    }
                    if (ids.length > 0) {
                        comm.confirm('确定删除所选行吗', function(){
                            js_menu.action_del(ids.join(','), function(){
                                $('input[name="layTableCheckbox"]:checked').each(function(i,e){
                                    $(e).parents('tr').remove();
                                });
                            });
                        });
                    }
                    break;
            }
        });
    });
</script>
<{/block}>

