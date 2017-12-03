/**
 * Created by KF on 2017/5/11.
 */
var js_gantt_project = {
    add_project: function(p_id){
        var self = this;
        var _content = '<form id="add-pro-frm">';
            _content+= '<input type="hidden" name="pid" value="'+ p_id +'" />';
            _content+= '<input type="hidden" name="pro_id" value="'+ $('.pro-table').data('pro-id') +'" />';
            _content+= '<div>任务名称：<input type="text" name="name" value="" /></div>';
            _content+= '<div>内容描述：<input type="text" name="desc" value="" /></div>';
            _content+= '<div>主负责人：<input type="text" name="owner" value="Carl" /></div>';
            _content+= '<div>相关人员：<input type="text" name="relate" value="" /></div>';
            _content+= '<div>开始日期：<input type="text" name="begin" value="" /></div>';
            _content+= '<div>完成日期：<input type="text" name="end" value="" /></div>';
            _content+= '<div>排列顺序：<input type="text" name="order" value="0" /></div>';
            _content+= '</form>';
        self.dialog('#add-dialog', '添加项目', _content, function(){
            self.ajax(__BASE__ + '/Gantt/AddProject', $('#add-pro-frm').serialize(), function(){
                location.reload();
            });
        });
    },
    mod_project: function(id){
        var self = this;
        self.ajax(__BASE__ + '/Gantt/ModifyProject', 'id='+id, function(data){
            var _content = '<form id="mod-pro-frm">';
            _content+= '<input type="hidden" name="id" value="'+ data.id +'" />';
            _content+= '<div>任务名称：<input type="text" name="name" value="'+ data.name +'" /></div>';
            _content+= '<div>内容描述：<input type="text" name="desc" value="'+ data.desc +'" /></div>';
            _content+= '<div>主负责人：<input type="text" name="owner" value="'+ data.ownner +'" /></div>';
            _content+= '<div>相关人员：<input type="text" name="relate" value="'+ data.relation +'" /></div>';
            _content+= '<div>开始日期：<input type="text" name="begin" value="'+ data.begin_date +'" /></div>';
            _content+= '<div>完成日期：<input type="text" name="end" value="'+ data.end_date +'" /></div>';
            _content+= '<div>排列顺序：<input type="text" name="order" value="'+ data.list_order +'" /></div>';
            _content+= '</form>';
            self.dialog('#edit-dialog', '编辑项目', _content, function(){
                self.ajax(__BASE__ + '/Gantt/UpdateProject', $('#mod-pro-frm').serialize(), function(){
                    location.reload();
                });
            });
        });
    },
    del_project: function(id){
        var self = this;
        console.log(id);
        this.dialog('#del-dialog', '删除项目', '删除操作不可恢复，是否继续？', function(){
            self.ajax(__BASE__ + '/Gantt/DeleteProject', 'id='+id, function(){
                location.reload();
            })
        });
    },
    ajax: function(url, data, callback){
        $.ajax({
            "url": url,
            "data": data,
            "dataType": 'JSON',
            "type": 'POST',
            "timeOut": 10,
            "success": function(res) {
                if (0 === res.status) {
                    callback(res.content);
                } else {
                    alert(res.content);
                }
            },
            "error": function(res, err) {
                alert('Server error: \n'+ err + "\n\nResponse text: \n" + res.responseText);
            },
            "complete": function() {

            }
        });
    },
    dialog: function(id, title, content, callback) {
        $(id).dialogBox({
            'hasClose': true,
            'hasMask': true,
            'hasBtn': true,
            'title': title,
            'content': content,
            'confirmValue': 'OK',
            'confirm': function(){
                callback();
            },
            'cancelValue': 'Cancel',
        })
    },
    ini: function(){
        var self = this;
        $('.btn-edit').on('click', function(e){
            e.preventDefault();
            self.mod_project($(this).data('id'));
        });
        $('.btn-add').on('click', function(e){
            e.preventDefault();
            self.add_project($(this).data('id'));
        });
        $('.btn-del').on('click', function(e){
            e.preventDefault();
            self.del_project($(this).data('id'));
        });
    }
};$(function(){js_gantt_project.ini();});