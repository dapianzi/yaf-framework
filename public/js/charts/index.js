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
        init: function() {
            this.$win = $(window);
        },
        get: function(id) {
            var self = this;
            if (self.ajaxLock) {
                return false;
            }
            self.ajaxLock = true;
            $.post('/index/charts/getScreenItem?id=' + id, {}, function(_cbData) {
                self.ajaxLock = false;
                // var $ = layui.jquery;
                var code = _cbData.code * 1;
                if (code == -1) {
                    $("#charts-container").html('<h1>' + _cbData.msg + '</h1>');
                    return;
                }
                $("#main").remove();
                var node = $('<div></div>');
                var chartsH = 600;
                node.css({
                    width: self.$win.width()*0.8+"px",
                    height: chartsH + "px"
                });

                $('#charts-container').append(node);
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
                    cusCharts.create(node[0], 'line', dataList).createLine();
                    return;
                }
            });
        },
    };
    Index.init();
    exports('charts_index', Index);
});