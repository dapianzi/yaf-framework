/**
 * Created by Carl on 2017/11/23.
 */
;
js_comm = {
    ajax_lock: false,

    ini: function() {
        if (typeof __PAGE__ !== 'undefined') {
            $('.side-bar .nav>li').each(function(){
                if ($(this).find('a').attr('href') == __PAGE__) {
                    $(this).addClass('active');
                }
            })
        }
    },
    ajax: function(_url, _data, _ok, _fail) {
        if (js_comm.ajax_lock) {
            return false;
        }
        $.ajax({
            url: _url,
            type: 'POST',
            data: _data,
            dataType: 'JSON',
            timeOut: 15000,
            success: function(res) {
                if (res.status === 0) {
                    _ok(res);
                } else {
                    _fail(res);
                }
            },
            error: function(xhr, status, err) {
                alert('系统出错了：' + err);
                console.log(xhr, status);
            },
            complete: function() {
                js_comm.ajax_lock = false;
            }
        })
    },
    ajax_get: function(_url, _ok) {
        $.ajax({
            url: _url,
            type: 'GET',
            dataType: 'html',
            timeOut: 15000,
            success: function(res) {
                _ok(res);
            },
            error: function(xhr, status, err) {
                alert('系统出错了：' + err);
                console.log(xhr, status);
            }
        })
    }
};
$(function(){js_comm.ini();});