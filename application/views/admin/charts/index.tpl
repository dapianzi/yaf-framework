<{extends 'public/base.tpl'}>
<{block name=title}>图表<{/block}>
<{block name=body}>

<div class="layui-fluid">
    <div class="layadmin-content layui-row layui-col-space15">
        <form class="layui-form" >
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">聚合图：</label>
                <div class="layui-input-block">
                    <select name="category" lay-search lay-filter="filter">
                        <{foreach $screens as $c}>
                        <option value="<{$c.screenid}>"><{$c.name}></option>
                        <{/foreach}>
                    </select>
                    
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">时间：</label>
                <div class="layui-input-block" style="width:100%;">
                    <input type="text" class="layui-input" id="charts-date-el">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block" >
              <div id="loading-wrap"></div>
              <label class="layui-form-label" id="error-notice"></label>
            </div>
        </div>
        </form>
        <div class="" id="charts-container"></div>
    </div>
</div>
<{/block}>
<{block name=script}>
<!-- <script src="https://cdn.bootcss.com/echarts/4.0.4/echarts.min.js"></script> -->
<!-- <script src="/js/charts/index.js?v=1"></script> -->
<script type="text/javascript">
    layui.extend({
        cus_charts: 'js/charts/charts',
        charts_index: 'js/charts/index', //主入口模块

    });
    
    layui.use(["cus_tools","laydate","element","form",'charts_index'],function(){
        var element = layui.element;
        var chartsIndex=layui.charts_index;
        var form = layui.form;
        var laydate=layui.laydate;
        var tools =layui.cus_tools;
        var tDate=tools.Date;
        window.tools=tools;
        chartsIndex.init();
        element.tabChange('all-charts','tes1');
        element.on('tab', function(e){
            console.log(e);
        });
        
        laydate.render({
            elem: '#charts-date-el' ,//指定元素
            type: 'datetime',
            range: true,
            calendar: true,
            value: tDate.laydateRangValue(tDate.now('-7days'),tDate.now())
        });
        form.on('select(filter)', function(data){
            var v= data.value;
            chartsIndex.get(v,function(echartsObj){
                echartsObj.on("datazoom",function(e){
                    console.log(e)
                })
            });
        });
        
    });
</script>
<{/block}>