<{extends file='public/base.tpl'}>

<{block name=title}>模板管理<{/block}>

<{block name=static}><{/block}>

<{block name=body}>
<div class="layui-fluid">
    <div class="layadmin-content layui-row layui-col-space15">
        <form class="layui-form" onsubmit="return false;" lay-filter="template-form">
        <div class="layui-tab" lay-filter="template">
            <ul class="layui-tab-title">
                <{foreach $result as $c=>$cc}>
                <li <{if $cc@index eq 0}>class="layui-this"<{/if}> lay-id="cate<{$cc@index}>" ><a href="#cate<{$cc@index}>"><{$c}></a></li>
                <{/foreach}>
            </ul>
            <div class="layui-tab-content">
                <{foreach $result as $cate}>
                <div class="layui-tab-item <{if $cate@index eq 0}>layui-show<{/if}>">
                    <table class="layui-table" lay-size="sm">
                        <colgroup>
                            <col width="120px">
                            <!--<col width="5%">-->
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th><input type="checkbox" name="checkall" lay-skin="primary" title="全选" lay-filter="checkall"/></th>
                            <!--<th>ID</th>-->
                            <th>名称</th>
                            <th>模板类型</th>
                            <th>模板内容</th>
                            <th>其他信息</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <{foreach $cate as $t}>
                        <tr data-idx="<{$t.id}>">
                            <td><input type="checkbox" name="tid[]" value="<{$t.id}>" lay-skin="primary" title="ID[<{$t.id}>]" /></td>
                            <!--<td><{$t.id}></td>-->
                            <td><{$t.name}></td>
                            <td><{$t.content_type}></td>
                            <td>
                                <pre style="color:#393D49;font-family: Lucida Console;"><{$t.format_content}></pre>
                            </td>
                            <td><{$t.creator}> 创建于 <{$t.create_time}><br /><{$t.modifier}> 最后修改 于<{$t.modify_time}></td>
                            <td>
                                <a class="layui-btn layui-btn-xs" data-role="edit">编辑</a>
                                <a class="layui-btn layui-btn-danger layui-btn-xs" data-role="del">删除</a>
                            </td>
                        </tr>
                        <{/foreach}>
                        </tbody>
                    </table>
                </div>
                <{/foreach}>
            </div>
        </div>
        <div class="dealTable">
            <div class="layui-btn-group ">
                <button class="layui-btn layui-btn-sm" data-role="add"><i class="layui-icon layui-icon-tianjia2"></i>添加新模板</button>
                <button class="layui-btn layui-btn-sm layui-btn-danger" data-role="dels"><i class="layui-icon layui-icon-delete"></i>删除所选</button>
            </div>
        </div>
        </form>
    </div>
</div>
<script id="t_edit_template" type="text/html">
<div class="layui-row" style="padding:20px;">
<form class="layui-form" action="/tools/template/save/" method="post" layui-filter="template-form-edit">
    <input type="hidden" name="id" value="{{ d.id }}" />
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">模板名称</label>
        <div class="layui-input-block">
            <input name="name" placeholder="请输入名称" style="width:80%;" class="layui-input" value="{{ d.name }}" />
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">模板类别</label>
        <div class="layui-input-block" style="width:40%;">
            <select name="category" lay-verify="required">
                <{foreach $category as $c}>
                <option value="<{$c}>" {{# if ('<{$c}>' === d.category){ }}selected{{# } }}><{$c}></option>
                <{/foreach}>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容类型</label>
        <div class="layui-input-block" style="width:40%;">
            <select name="content_type" lay-verify="required">
                <{foreach $content_type as $t}>
                <option value="<{$t}>" {{# if ('<{$t}>' === d.content_type){ }}selected{{# } }}><{$t}></option>
                <{/foreach}>
            </select>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">模板内容</label>
        <div class="layui-input-block">
            <textarea style="color:#393D49;font-family: Lucida Console;" rows="10" name="content" placeholder="请输入内容" class="layui-textarea">{{ d.content }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="saveTemplate">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
</div>
</script>
<{/block}>

<{block name=script}>
<script type="application/javascript">
    layui.use(['element', 'laytpl', 'form', 'comm'], function(){ //加载code模块
        var $ = layui.$
                ,comm = layui.comm
                ,laytpl = layui.laytpl
                ,form = layui.form
                ,layer = layui.layer
                ,element = layui.element;
        var js_template = {
            action_add: function(e) {
                js_template.save({category: $('.layui-this a').html()});
            },
            action_edit: function(e) {
                comm.ajax_get('/tools/template/get/id/'+$(this).parents('tr').data('idx'), function(res){
                    res = $.parseJSON(res);
                    js_template.save(res.data);
                });
            },
            action_del: function(e) {
                var _id = $(this).parents('tr').data('idx');
                comm.confirm('删除这个模板？', function(){
                    comm.ajax('/tools/template/del/', {id: _id}, function(){location.reload();});
                });
            },
            action_dels: function(e) {
                // current tab
                var ids=[],chk = $('.layui-tab-item.layui-show input[name="tid[]"]:checked');
                for (var i=0; i<chk.length; i++) {
                    ids.push($(chk[i]).val());
                }
                if (ids && ids.length > 0) {
                    comm.confirm('删除这个模板？', function(){
                        comm.ajax('/tools/template/del/', {id: ids}, function(){location.reload();});
                    });
                }
            },
            save: function(data) {
                data = $.extend({
                    'id': 0,
                    'name': '',
                    'category': '',
                    'content_type': '',
                    'content': ''
                }, data);
                laytpl($('#t_edit_template').html()).render(data, function(html){
                    comm.open({
                        title: '添加模板',
                        content: html,
                    });
                    form.render();
                    form.on('submit(saveTemplate)', function(o){
                        comm.ajax_form($(o.elem).parents('form'), function(res){
                            location.reload();
                        });
                        return false;
                    });
                });
            }
        };
        // 初始化选项卡
        var curr_tab = location.hash.replace(/^#/, '');
        if (curr_tab) {
            element.tabChange('template', curr_tab);
        }
        // 绑定全选
        form.on('checkbox(checkall)', function(o){
            var checked = o.elem.checked,
                    chks = $(o.elem).parents('table.layui-table').find('input[name="tid[]"]');
            chks.each(function(idx, e) {
                e.checked = checked;
            });
            form.render('checkbox');
        });
        // 绑定事件
        $('.layui-btn').on('click', function(e) {
            var role = 'action_' + $(this).data('role');
            if (js_template[role]) {
                js_template[role].call(this, e);
            }
        });
    });
</script>
<{/block}>