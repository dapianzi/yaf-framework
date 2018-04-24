/**
 * Created by carl on 2018-04-08.
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
                time: 1500
            }, function(){
                _callbk && _callbk();
            });
        },
        msg_error: function(msg, _callbk) {
            this.msg(msg, {
                icon: 5,
                time: 2500
            }, function(){
                _callbk && _callbk();
            });
        },
        /**
         * 提供了默认的图标和关闭弹窗操作
         * @param content 提示内容
         * @param yes 确认之后的回调函数
         */
        confirm: function(content, yes) {
            layer.confirm(content, {icon: 3}, function(idx){
                yes && yes();
                layer.close(idx);
            });
        },
        /**
         * 统一的弹窗风格
         * @param option
         */
        open: function(option) {
            // 默认配置项
            option = $.extend({
                type: 1,
                area: '60%',
                skin: 'layui-layer-rim',
            }, option);
            return layer.open(option);
        },

        ajax: function (_url, _data, _ok, _fail) {
            if (this.ajax_lock) {
                return false;
            }
            // self.ajax_lock = true;
            var self = this;
            _ok = _ok || function() {
                self.msg_ok('操作成功！');
            };
            _fail = _fail || function(res) {
                self.alert_error(res.msg);
            };
            var loading = layer.load(1, {
                shade: [0.1,'#fff']
            });
            $.ajax({
                url: _url,
                type: 'POST',
                data: _data,
                dataType: 'JSON',
                timeOut: 15000,
                success: function (res) {
                    res.code === 0 ? _ok(res) : _fail(res);
                },
                error: function (xhr, status, err) {
                    self.msg_error('系统出错了['+status+']：' + err);
                },
                complete: function () {
                    self.ajax_lock = false;
                    layer.close(loading);
                }
            })
        },
        ajax_get: function (_url, _ok) {
            var self = this;
            $.ajax({
                url: _url,
                type: 'GET',
                dataType: 'text',
                timeOut: 15000,
                success: function (res) {
                    _ok && _ok(res);
                },
                error: function (xhr, status, err) {
                    self.alert_error('系统出错了：' + err);
                }
            })
        },
        ajax_get_json: function (_url, _ok) {
            var self = this;
            $.ajax({
                url: _url,
                type: 'GET',
                dataType: 'json',
                timeOut: 15000,
                success: function (res) {
                    if (res.code === 0) {
                        _ok && _ok(res);
                    }
                },
                error: function (xhr, status, err) {
                    self.msg_error('系统出错了['+status+']：' + err);
                }
            })
        },
        ajax_form: function (_form, _ok, _fail) {
            this.ajax($(_form).attr('action'), $(_form).serialize(), _ok, _fail);
        },
        check_all: function($all, $chk, cbf) {
            $all.on('change', function() {
                var checked = $all[0].checked;
                $chk.each(function(idx, e) {
                    //console.log(e);
                    e.checked = checked;
                });
                cbf && cbf();
            });
        },
        date: {
            defDate: {
                datetime: 'yyyy-MM-dd H:m:s',
                date: 'yyyy-MM-dd',
                time: 'H:m:s',
                year: 'yyyy',
                month: 'MM',
                day: 'dd',
                hours: 'H',
                minutes: 'm',
                seconds: 's',
            },
            date: function(_offset) {
                return this.now(_offset, this.defDate.date);
            },
            datetime: function(_offset) {
                return this.now(_offset, this.defDate.datetime);
            },
            time: function(_offset) {
                return this.now(_offset, this.defDate.time);
            },
            now: function(_offset, _fmt) {
                _fmt = _fmt || this.defDate.datetime;
                var defDateOffsetKey = {
                    days:24*3600*1000,
                    hours:3600*1000,
                    seconds:1000,
                    minutes:60*1000
                },
                now =new Date()*1;
                if (_offset) {
                    var r = /(\-|\+)\s?(\d+)\s?([a-z]+)\s?/g;
                    if (r.test(_offset)) {
                        var t = [];
                        t.push(RegExp.$1);
                        t.push(RegExp.$2*1);
                        t.push(RegExp.$3);
                        if(defDateOffsetKey[t[2]]){
                            var v = defDateOffsetKey[t[2]];
                            now =now+((t[0]+(1*t[1]*v))*1);
                        }else{
                            throw new Error('提供的格式需要为[+|-][整数][days|hours|minutes|seconds],如:-1 days +8 days');
                        }
                    }else{
                        throw new Error('提供的格式需要为[+|-][整数][days|hours|minutes|seconds],如:-1 days +8 days');
                    }
                }
                return this.fmtDate(now, _fmt);

            },
            fmtDate: function(_time, _fmt) {
                var d = new Date(_time * 1);
                var o = {
                    "M+": d.getMonth() + 1, //月份
                    "d+": d.getDate(), //日
                    "h+": d.getHours() % 12 == 0 ? 12 : d.getHours() % 12, //小时
                    "H+": d.getHours(), //小时
                    "m+": d.getMinutes(), //分
                    "s+": d.getSeconds(), //秒
                    "q+": Math.floor((d.getMonth() + 3) / 3), //季度
                    "S": d.getMilliseconds() //毫秒
                };
                var week = {
                    "0": "/u65e5",
                    "1": "/u4e00",
                    "2": "/u4e8c",
                    "3": "/u4e09",
                    "4": "/u56db",
                    "5": "/u4e94",
                    "6": "/u516d"
                };
                if (/(y+)/.test(_fmt)) {
                    _fmt = _fmt.replace(RegExp.$1, (d.getFullYear() + "").substr(4 - RegExp.$1.length));
                }
                if (/(E+)/.test(_fmt)) {
                    _fmt = _fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "/u661f/u671f" : "/u5468") : "") + week[d.getDay() + ""]);
                }
                for (var k in o) {
                    if (new RegExp("(" + k + ")").test(_fmt)) {
                        _fmt = _fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                    }
                }
                return _fmt;
            }
        },
        storage: {
            get: function (name) {
                return JSON.parse(localStorage.getItem(name))
            },
            set: function (name, val) {
                localStorage.setItem(name, JSON.stringify(val))
            },
            add: function (name, addVal) {
                let oldVal = this.get(name)
                let newVal = oldVal.concat(addVal)
                this.set(name, newVal)
            }
        },

        // init
        init: function() {
            if (!this.storage.get('os')) {
                this.storage.set('os', 'all');
            }
        }
    };
    comm.init();
    exports('c_comm', comm);
});
