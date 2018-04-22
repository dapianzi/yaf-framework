<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><{block name=title}><{$site.title}><{/block}></title>
    <meta name="keywords" content="<{block name=keywords}><{$site.keywords}><{/block}>">
    <meta name="description" content="<{block name=description}><{$site.description}><{/block}>">

    <link rel="icon" type="image/png" href="/imgs/favicon.png">

    <!-- Bootstrap -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <{*<link href="/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">*}>

    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- static files -->
    <link href="/css/web/style.css" rel="stylesheet">
    <{block name=static}><{/block}>
</head>
<body>
<{block name=header}>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">切换菜单</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img alt="<{$site.name}>" src="/imgs/logo.png"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a title="魔境游戏" href="#" data-toggle="dropdown" aria-haspopup="true" class="dropdown-toggle">魔境游戏 <span class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a title="魔境大冒险" href="#">魔境大冒险</a></li>
                            <li><a title="魔境荣耀" href="http://pvp.qq.com/" target="_blank">魔境荣耀</a></li>
                            <li><a title="魔境先锋" href="http://ow.blizzard.cn/" target="_blank">魔境先锋</a></li>
                            <li><a title="魔境大作战" href="http://www.battleofballs.com/" target="_blank">魔境大作战</a></li>
                            <li><a title="魔境求生" href="http://pubg.qq.com/" target="_blank">魔境求生</a></li>
                        </ul>
                    </li>
                    <li>
                        <a title="游戏下载" href="/about-us/" target="_self">游戏下载</a>
                    </li>
                    <li>
                        <a title="关于魔境" href="/about-us/" target="_self">关于魔境</a>
                    </li>
                    <li>
                        <a title="加入魔境" href="/join-us/" target="_self">加入魔境</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="javascript:alert('敬请期待~');">微信公众号</a></li>
                </ul>
            </div>
        </div>
    </nav>
<{/block}>
<{block name=body}>
    <div class="container main-body">
        <div class="jumbotron">
            <h1>Hello, world!</h1>
            <p>...</p>
            <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>
        </div>
    </div>
<{/block}>
<{block name=footer}>
    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-8 links">
                    <dl class="footer-tips">
                        <dd>抵制不良游戏，拒绝盗版游戏。</dd>
                        <dd>注意自我保护，谨防受骗上当。</dd>
                        <dd>适度游戏益脑，沉迷游戏伤身。</dd>
                        <dd>合理安排时间，享受健康生活。</dd>
                    </dl>
                </div>
                <div class="col-md-4">
                    <img src="/imgs/app-icon72x72@2x.png" class="img-thumbnail" />
                    <img src="/imgs/app-icon72x72@2x.png" class="img-thumbnail" />
                </div>
            </div>
            <div id="copyright"><{$site.copyright}> | <{$site.ISBN}></div>
        </div>
    </footer>
<{/block}>
<{block name=modal}><{/block}>
<script src="/jquery/1.12.4/jquery.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/js/web/main.js"></script>
<{block name=script}><{/block}>

</body>
</html>