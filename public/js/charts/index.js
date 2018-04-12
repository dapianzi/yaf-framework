;
layui.define(['comm', 'cus_echarts', 'cus_charts' /* 依赖的组件 */ ], function(exports) {
    // console.log(aa);
    var comm = layui.comm;
    var $ = layui.jquery;
    var cusCharts = layui.cus_charts;
    window.cusCharts = cusCharts;
    var echarts = layui.cus_echarts;
    var Index = {
        $win: null,
        ajaxLock: false,
        loadingIcon: null,
        chartsNode: null,
        loaddingWrap: null,
        noticeWrap: null,
        init: function() {
            var self = this;
            self.$win = $(window);
            self.loaddingWrap = $("#loading-wrap");
            self.noticeWrap = $("#error-notice");

            // self.createLoadNode();
        },
        getChartsNode: function() {
            var self = this;
            var elId = 'charts-wrap-div';
            $("#" + elId).remove();
            var node = $('<div id="' + elId + '"></div>');
            var chartsH = 600;
            node.css({
                width: self.$win.width() + "px",
                height: chartsH + "px"
            });
            $('#charts-container').append(node);
            self.chartsNode = node;
            return node;
        },
        addNotice: function(_text) {
            _text = _text || '';
            var self = this;
            self.noticeWrap.empty();
            self.noticeWrap.html(_text);
        },
        createLoadNode: function(_text) {
            _text = _text || '获取数据...';
            var self = this;
            self.loadingIcon = $('<div><i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63d;</i>' + _text + '</div>');
            return self.loadingIcon;
        },
        addLoading: function(_text) {
            var self = this;
            self.ajaxLock = true;
            self.chartsNode&&self.chartsNode.remove();
            self.loaddingWrap.append(self.createLoadNode(_text));
        },
        removeLoading: function() {
            var self = this;
            self.ajaxLock = false;
            self.loadingIcon.remove();
        },
        get: function(id, _cbfun) {
            var self = this;
            if (self.ajaxLock) {
                return false;
            }
            self.addLoading();
            $.post('/index/charts/getScreenItem?id=' + id, {}, function(_cbData) {
                self.removeLoading();
                // var $ = layui.jquery;
                var code = _cbData.code * 1;
                if (code == -1) {
                    $("#charts-container").html('<h1>' + _cbData.msg + '</h1>');
                    return;
                }

                // var myChart = echarts.init(node);
                var dataList = _cbData.data;
                if (dataList.length > 0) {
                    for (var i = 0; i < dataList.length; i++) {
                        var cur = dataList[i];
                        var data = cur.series;
                        cur.series = data.map(function(item) {
                            // var tmp =item;
                            item['value'][0] = (new Date(item['value'][0] * 1));
                            return item;
                        });
                    }
                    var chartsObj = cusCharts.create(self.getChartsNode()[0], 'line', dataList).createLine();
                    _cbfun(chartsObj.getEchartsObj());
                    return;
                }
            });
        },
    };
    Index.init();
    exports('charts_index', Index);
});