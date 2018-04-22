<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><{block name=title}><{$site.title}><{/block}></title>
    <meta name="keywords" content="<{block name=keywords}><{$site.keywords}><{/block}>">
    <meta name="description" content="<{block name=description}><{$site.description}><{/block}>">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <link rel="icon" type="image/png" href="/imgs/favicon.png">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="/imgs/app-icon72x72@2x.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<{$site.title}>"/>
    <link rel="apple-touch-icon-precomposed" href="/imgs/app-icon72x72@2x.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="/imgs/app-icon72x72@2x.png">
    <meta name="msapplication-TileColor" content="#0e90d2">

    <link rel="stylesheet" href="/assets/css/style.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <!-- static files -->
    <{block name=static}><{/block}>
</head>
<body>
    <header data-am-widget="header" class="am-header am-header-default">
        <div class="am-header-left am-header-nav">
            <a href="#left-link" class=""><i class="am-header-icon am-icon-home"></i></a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link" class=""><{$site.name}></a>
        </h1>
        <div class="am-header-right am-header-nav">
            <a href="#right-link" class=""><i class="am-header-icon am-icon-bars"></i></a>
        </div>
    </header>
    <{block name=body}>
    <div class="mj-main">
        <section id="section-1" class="am-container">
            <div class="am-u-lg-4 am-u-md-6 am-u-sm-12">
                <div data-am-widget="intro" class="am-intro am-cf am-intro-default">
                    <div class="am-intro-hd">
                        <h2 class="am-intro-title">DEMO 页</h2>
                        <a class="am-intro-more am-intro-more-top" href="#more">更多细节</a>
                    </div>
                    <div class="am-g am-intro-bd">
                        <div class="am-intro-left am-u-sm-5">
                            <img src="http://s.amazeui.org/assets/2.x/i/cpts/intro/WP_Cortana_China.png" alt="小娜" />
                        </div>
                        <div class="am-intro-right am-u-sm-7">
                            <p>充钱是不可能充钱的，这辈子都不可能充钱。这里面每天都送金币，刷怪简单，爆率又高，个个BOSS都是人才，超喜欢这里的。</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-12">
                <p>
                    以下实例全部基于前面所讲的基本模板并配合 Bootstrap 的众多组件开发而成。我们鼓励你根据自身项目的需要对 Bootstrap 进行定制和修改。

                    Get the source code for every example below by downloading the Bootstrap repository. Examples can be found in the docs/examples/ directory.
                </p>
            </div>
        </section>
        <section id="section-2" class="am-container am-cf">
            <div class="am-u-lg-4">
                <div class="mj-panel">
                    <img class="am-img-responsive" src="http://s.amazeui.org/assets/2.x/i/cpts/intro/WP_Cortana_China.png" />
                    <p>hre;'msznrthij 根号二公开安慰破格哈伦裤然后阿尔</p>
                </div>
            </div>
            <div class="am-u-lg-4">
                <div class="am-panel am-panel-success">
                    <div class="am-panel-hd">面板标题</div>
                    <div class="am-panel-bd">
                        面板内容
                    </div>
                </div>
            </div>
            <div class="am-u-lg-4">
                <div class="am-panel am-panel-warning">
                    <div class="am-panel-hd">面板标题</div>
                    <div class="am-panel-bd">
                        面板内容
                    </div>
                </div>
            </div>
        </section>
    </div>
    <{/block}>
    <hr data-am-widget="divider" style="" class="am-divider am-divider-default" />
    <footer data-am-widget="footer" class="am-footer am-footer-default"
            data-am-footer="{  }">
        <div class="am-footer ">
            <{*<p>由 Dapianzi Carl 提供技术支持</p>*}>
            <p>健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防上当受骗 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活</p>
            <p><{$site.copyright}></p>
            <p><{$site.ISBN}> | <a href="/comming/">客服中心</a></p>
        </div>
    </footer>
    <{block name=modal}><{/block}>
    <!--[if (gte IE 9)|!(IE)]><!-->
    <script src="/jquery/1.12.4/jquery.min.js"></script>
    <!--<![endif]-->
    <!--[if lte IE 8 ]>
    <script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
    <script src="/assets/js/ie8polyfill.min.js"></script>
    <![endif]-->
    <script src="/assets/js/main.min.js"></script>
    <{block name=script}><{/block}>
</body>
</html>