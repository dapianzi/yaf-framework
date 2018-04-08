/**
 * Created by KF on 2018-04-08.
 */
;
layui.define(['layer'/* 依赖的组件 */], function(exports){
    //do something
    var $ = layui.$
        ,layer = layui.layer;
    var comm = {
        ajax_lock: false,
        log: function () {
            console.log.apply(null, arguments);
        },
        alert: function () {
            layer.alert.apply(null, arguments);
        },
        alert_success: function (content, _callbk) {
            this.alert(content, {icon: 1}, function(idx){
                _callbk && _callbk();
                layer.close(idx);
            });
        },
        alert_error: function (content, _callbk) {
            this.alert(content, {icon: 2}, function(idx){
                _callbk && _callbk();
                layer.close(idx);
            });
        },
        msg: function() {
            layer.msg.apply(null, arguments);
        },
        msg_ok: function(msg, _callbk) {
            this.msg(msg, {
                icon: 6,
                time: 2000
            }, function(){
                _callbk && _callbk();
            });
        },
        msg_error: function(msg, _callbk) {
            this.msg(msg, {
                icon: 5,
                time: 3000
            }, function(){
                _callbk && _callbk();
            });
        },

        ajax: function (_url, _data, _ok, _fail) {
            if (this.ajax_lock) {
                return false;
            }
            // self.ajax_lock = true;
            var self = this;
            $.ajax({
                url: _url,
                type: 'POST',
                data: _data,
                dataType: 'JSON',
                timeOut: 15000,
                success: function (res) {
                    if (res.code === 0) {
                        _ok && _ok(res);
                    } else {
                        _fail ? _fail(res) : self.alert_error(res.msg);
                    }
                },
                error: function (xhr, status, err) {
                    self.msg_error('系统出错了['+status+']：' + err);
                },
                complete: function () {
                    self.ajax_lock = false;
                }
            })
        },
        ajax_get: function (_url, _ok) {
            $.ajax({
                url: _url,
                type: 'GET',
                dataType: 'text',
                timeOut: 15000,
                success: function (res) {
                    _ok && _ok(res);
                },
                error: function (xhr, status, err) {
                    js_comm.alert_error('系统出错了：' + err);
                }
            })
        },
        ajax_form: function (_form, _ok, _fail) {
            this.ajax($(_form).attr('action'), $(_form).serialize(), _ok, _fail);
        },
        check_all: function($all, $chk) {
            $all.on('change', function() {
                var checked = $all.prop('checked');
                $chk.each(function(idx, e) {
                    console.log(e);
                    $(e).prop('checked', checked);
                })
            });
        }
    };
    exports('comm', comm);
});
