/**
 * Created by KF on 2017/4/6.
 */
$(function() {

    $('#btn-ajax').on('click', function(){
        $.ajax({
            'url': __BASEURI__ + '/Ajax/index',
            'type': 'get',
            'data': 't=' + (Date.parse(new Date()) / 1000),
            'dataType': 'json',
            'success': function(res){
                $('#ajax-res').append('<li>' + res.content + '</li>')
            },
            'error': function(err, errMsg){
                console.log('SERVER ERROR: ' + errMsg);
            }
        }) ;
    });
});