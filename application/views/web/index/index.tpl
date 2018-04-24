<{extends 'public/bs_base.tpl'}>

<{*<{block name=title}><{/block}>*}>

<{block name=static}>
    <link rel="stylesheet" href="/swiper/css/swiper.min.css">
    <style type="text/css">
        @media (min-width: 992px) {
            .swiper-slide {
                width: 50%;
            }
        }
        .section-2{
            margin: 20px 0;
        }
    </style>
<{/block}>

<{block name=body}>
<section class="section-1 main-body">
    <div class="container">
        <div class="row">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img class="img-responsive" src="/imgs/swiper/index-1.jpg" title="游戏截图" alt="游戏截图"/></div>
                    <div class="swiper-slide"><img class="img-responsive" src="/imgs/swiper/index-2.jpg" title="游戏截图" alt="游戏截图"/></div>
                    <div class="swiper-slide"><img class="img-responsive" src="/imgs/swiper/index-3.jpg" title="游戏截图" alt="游戏截图"/></div>
                    <div class="swiper-slide"><img class="img-responsive" src="/imgs/swiper/index-4.jpg" title="游戏截图" alt="游戏截图"/></div>
                    <div class="swiper-slide"><img class="img-responsive" src="/imgs/swiper/index-5.jpg" title="游戏截图" alt="游戏截图"/></div>
                    <div class="swiper-slide"><img class="img-responsive" src="/imgs/swiper/index-6.jpg" title="游戏截图" alt="游戏截图"/></div>
                </div>
                <!-- 分页器 -->
                <div class="swiper-pagination"></div>
                <!-- 导航按钮 -->
                <div class="swiper-button-prev swiper-button-white"></div>
                <div class="swiper-button-next swiper-button-white"></div>
                <!-- 滚动条 -->
                <{*<div class="swiper-scrollbar"></div>*}>
            </div>
        </div>
    </div>
</section>
<section class="section-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                <img src="/imgs/app-icon72x72@2x.png" class="img-responsive" />
                <div class="row">
                    <div class="col-md-4 col-sm-12" style="margin-top: 10px;">
                        <button class="btn-block btn btn-warning">下载游戏</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12">
                <div class="page-header">
                    <h3><{$site.name}> <small><{$site.description}></small></h3>
                </div>
                <p>充钱是不可能充钱的啦，这辈子都不可能充钱的。自动打怪，爆率又高，这里个个BOSS都是人才，金币刷得超开心的啦。</p>
            </div>
        </div>
    </div>
</section>
<{/block}>

<{block name=script}>
    <script src="/swiper/js/swiper.min.js"></script>
    <script>
        var mySwiper = new Swiper ('.swiper-container', {
            autoplay: true,
            slidesPerView: 'auto',
            centeredSlides: true,
            loop: true,
            spaceBetween: 5,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                bulletActiveClass: 'my-swiper-pagination-bullet-active',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            // scrollbar: {
            //     el: '.swiper-scrollbar',
            // },
        });
    </script>
<{/block}>