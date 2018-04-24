<{extends 'public/base.tpl'}>

<{block name=title}>充值统计<{/block}>

<{block name=static}><{/block}>

<{block name=body}><{/block}>

<{block name=script}>
<script>
    layui.define('jquery', function(e){
        var $ = layui.$;

        var data = [
            {
                type: 'a',
                data: [1, 3, 5, 7, 9]
            },
            {
                name: 'second',
                data: [2, 4, 6, 8, 10]
            },
        ]
        var newData = [];
        // use json
        for (var i in data) {
            newData.push($.extend(data[i]))
        }

        // use variable
    });

</script>

<{/block}>