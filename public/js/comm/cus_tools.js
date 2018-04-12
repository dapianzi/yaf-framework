layui.define(['comm'], function(exports) {
    var defDate = {
        date: 'yyyy-MM-dd H:m:s',
        year: 'yyyy',
        month: 'MM',
        day: 'dd',
        hours: 'H',
        minutes: 'm',
        seconds: 's',
    };
    var defDateOffsetKey = {
    	days:24*3600*1000,
    	hours:3600*1000,
    	seconds:1000,
    	minutes:60*1000
    };
    // defDay='yyyy-MM-dd';
    var tools = {
        Date: {
            init: function() {
            },
            now: function(_offset) {
            	var now =new Date()*1;
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
                return this.fmtDate(now, defDate.date);

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

            },
            laydateRangValue: function(_s, _d) {
            	return _s+' - '+_d;
            },
        }
    };
    exports('cus_tools', tools);
});