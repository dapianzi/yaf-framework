<{extends 'public/base.tpl'}>

<{block name=title}>用户概况<{/block}>

<{block name=static}>
    <style type="text/css">
        .echart-container{
            height: 400px;
        }
    </style>
<{/block}>

<{block name=body}>
    <div class="layui-fluid">
        <div class="layadmin-content layui-row layui-col-space15">
            <form class="layui-form" lay-filter="charts-data-form" onsubmit="return false;">
                <div class="layui-inline" style="width: 350px;">
                    <input class="layui-input" name="time" id="datepicker" placeholder="yyyy-MM-dd HH:mm:ss - yyyy-MM-dd HH:mm:ss">
                </div>
                <div class="layui-inline">
                    <select class="layui-select" name="channel" lay-filter="channel-opt">
                        <option>所有渠道</option>
                        <option>[80000001] 今日头条</option>
                        <option>[80000002] 百度搜索</option>
                        <option>[80000003] 知乎</option>
                        <option>[80000004] 美团</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" lay-submit lay-filter="load-week">最近7天</button>
                    <button class="layui-btn" lay-submit lay-filter="load-month">最近30天</button>
                    <button class="layui-btn" lay-submit lay-filter="reload">确定</button>
                </div>
            </form>
            <div class="layui-tab layui-tab-brief" lay-filter="echarts-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="new">激活用户</li>
                    <li lay-id="reg">注册用户</li>
                    <li lay-id="act">活跃用户</li>
                    <li lay-id="pay">充值用户</li>
                    <li lay-id="regs">累计注册</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item echart-container layui-show" id="echarts-new"></div>
                    <div class="layui-tab-item echart-container" id="echarts-reg"></div>
                    <div class="layui-tab-item echart-container" id="echarts-act"></div>
                    <div class="layui-tab-item echart-container" id="echarts-pay"></div>
                    <div class="layui-tab-item echart-container" id="echarts-regs"></div>
                </div>
            </div>
        </div>
    </div>
<{/block}>

<{block name=script}>
    <script type="text/javascript">
        layui.use(["c_comm", "laydate", "element", "form",'c_charts'], function(){
            var $ = layui.$,
                element = layui.element,
                charts = layui.c_charts,
                form = layui.form,
                laydate = layui.laydate,
                comm = layui.c_comm;
            laydate.render({
                elem: '#datepicker' ,//指定元素
                type: 'date',
                range: true,
                calendar: true,
                value: comm.date.date('-7days') + ' - ' + comm.date.date()
            });
            // init tab
            element.tabChange('echarts-tab', location.hash.substr(1));
            // tab event
            element.on('tab(echarts-tab)', function(obj){
                location.hash = this.getAttribute('lay-id');
                js_charts.renderCharts(this.getAttribute('lay-id'));
            });
            var js_charts = {
                currData: null,
                getData: function(from, to, channel) {
                    var self = this;
                    self.currData = null;
                    var params = 'from='+from+'&to='+to+'&channel='+channel+'&os='+comm.storage.get('os');
                    comm.ajax_get_json('/admin/useranalysis/generalData/?'+params, function(res){
                        self.currData = res.data;
                        self.renderCharts($('.layui-tab .layui-this').attr('lay-id'));
                    });
                },
                renderCharts: function(series) {
                    if (!this.currData) {
                        comm.msg('数据加载中，请稍候');return false;
                    }
                    // todo render echarts or table
                    var theme = ['macarons','dark', 'shine', 'layui'][Math.floor(Math.random()*4)];
                    console.log(theme);
                    charts.render('echarts-'+series, 'line', {
                        xAxis: {
                            data: this.currData.range,
                        },
                        series: [{
                            data: this.currData.series[series].data,
                            name: this.currData.series[series].name,
                        },{
                            data: this.currData.series.reg.data,
                            name: this.currData.series.reg.name,
                        }]
                    }, theme);
                },
                init: function() {
                    this.getData(comm.date.now('-14days'), '', '');
                }
            };
            form.on('submit(load-week)', function(data){
                var range = data.field.time.split(' - ');
                if (range.length == 2) {
                    js_charts.getData(range[0], range[1], data.field.channel);
                } else {
                    comm.msg_error('日期格式不合法');
                }
                return false;
            });
            form.on('submit(load-month)', function(data){
                js_charts.getData(comm.date.date('-30 days'), comm.date.date(), data.field.channel);
                return false;
            });
            form.on('submit(reload)', function(data){
                js_charts.getData(comm.date.date('-7 days'), comm.date.date(), data.field.channel);
                return false;
            });
            js_charts.init();
        });
    </script>
<{/block}>