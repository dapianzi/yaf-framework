;
layui.define(['cus_echarts'], function(exports) {
    var $ = layui.jquery;
    // var macarons= layui.cus_macarons;
    var echarts = layui.cus_echarts;
    var O = {
        createCharts: function(_el, _data, _conf) {
            var defConf, ecObj, o;
            o = {};
            defConf = {
                visualMap: {
                    show: false,
                    calculable: true,
                    type: 'continuous',
                    itemWidth: 2
                },
                legend: {},
                title: {},
                grid: { height: '70%' },
                dataZoom: [],
                xAxis: {},
                yAxis: {
                    splitLine: { show: false }
                },
                series: []
            }
            if (_conf) {
                defConf = _conf;
            }
            var setxAxis = function(_xaxisConf) {
                defConf.xAxis = _xaxisConf;
            };
            var setZoom = function(_type, _zoom, _wrap) {
                _zoom = _zoom === undefined ? 50 : _zoom;
                _wrap = _wrap || 0;
                var zObj = { dataZoom: [] }
                var def = {
                    show: true,
                    realtime: true,
                    start: 0,
                    end: 100,
                };
                if (_type == 0) {
                    //both
                    zObj.dataZoom = setZoom(1, _zoom, 1).concat(setZoom(2, _zoom, 1));

                } else if (_type == 1) {
                    //x
                    zObj.dataZoom.push($.extend({}, def, { start: _zoom, type: 'slider', xAxisIndex: [0] }));
                    zObj.dataZoom.push($.extend({}, def, { start: _zoom, type: 'inside', xAxisIndex: [0] }));
                } else if (_type == 2) {
                    //y
                    zObj.dataZoom.push($.extend({}, def, { type: 'slider', yAxisIndex: [0] }));
                    zObj.dataZoom.push($.extend({}, def, { type: 'inside', yAxisIndex: [0] }));
                }
                if (!_wrap) {
                    defConf.dataZoom = zObj.dataZoom;
                } else {
                    return zObj.dataZoom;
                }
            };
            /**
             * [
             *  [
             *    {series:data,title:'123'}
             *  ],
             * ]
             */
            o.createLine = function(_xAxis, _zoom) {
                var defxAxis = {
                    type: 'time',
                    splitLine: {
                        show: false
                    },
                    minInterval: 1000,
                };
                var zoom = [1, 0];
                if (_xAxis) {
                    defxAxis = _xAxis;
                }
                if (_zoom) {
                    zoom = _zoom;
                }
                _data = _data || [];
                // _conf=_conf||{};
                datalen = _data.length;
                if (datalen === 0) return null;

                var titleA = new Array();
                for (var i = 0; i < _data.length; i++) {
                    var data = _data[i].series;
                    var title = _data[i].title;
                    defConf.series.push({
                        type: 'line',
                        data: data,
                        name: title
                    });
                    titleA.push(title);

                }
                if (!defConf.legend) {
                    defConf.legend = {};
                }
                defConf.legend.data = titleA;
                ecObj = echarts.init(_el,'shine');
                //init xAix
                setxAxis(defxAxis);
                setZoom(zoom[0], zoom[1]);
                ecObj.setOption(defConf);
                return o;
            };
            o.getConf = function() {
                return defConf;
            }
            o.getEchartsObj = function() {
                return ecObj;
            }
            return o;
        },
    };
    var Charts = {
        create: function(_el, _type, _data, _conf) {
           return O.createCharts(_el, _data, _conf);
        }
    };
    exports('cus_charts', Charts);
});