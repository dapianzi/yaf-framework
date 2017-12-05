/**
 * Created by KF on 2017/4/12.
 */
(function(){
    var clientWidth = document.documentElement.clientWidth;
    if (!clientWidth) return;
    document.documentElement.style.fontSize = (clientWidth*100/360) + 'px';
})();