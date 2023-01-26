<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<section class="index-top">
    <div class="wrapper">
        <div class="sides">
            <div id="mobile__top__slider" class="carousel slide top__slider" data-bs-ride="carousel" data-bs-pause="true" data-bs-interval="false" data-bs-touch="true">
                <div class="carousel-inner">
                    <?php foreach ($sliders as $key=>$slider) {?>
                        <div class="photo carousel-item <?=($key==0) ? 'active' : ''?>">
                            <a href="<?=$slider->link?>"><img alt="" src="<?=newthumbs($slider->mobile_img, 'slider', 346, 506, '346x506x1', 1)?>"></a>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="carousel-indicators" id="mobile-carousel-indicators">
                <?php foreach ($sliders as $key=>$slider) {?>
                    <button type="button" data-bs-target="#mobile__top__slider" data-bs-slide-to="<?=$key?>" class="<?=($key==0) ? 'active' : ''?>" aria-current="true" aria-label="Slide <?=$key?>">
                        <span></span>
                    </button>
                <?php }?>
            </div>
            <div id="top__slider" class="carousel slide top__slider" data-bs-ride="carousel" data-bs-pause="true" data-bs-interval="false" data-bs-touch="true">
                <div class="carousel-indicators">
                    <?php foreach ($sliders as $key=>$slider) {?>
                        <button type="button" data-bs-target="#top__slider" data-bs-slide-to="<?=$key?>" class="<?=($key==0) ? 'active' : ''?>" aria-current="true" aria-label="Slide <?=$key?>">
                            <span></span>
                        </button>
                    <?php }?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($sliders as $key=>$slider) {?>
                        <div class="photo carousel-item <?=($key==0) ? 'active' : ''?>">
                            <a href="<?=$slider->link?>"><img alt="" src="<?=newthumbs($slider->img, 'slider', 1048, 540, '1048x540x1', 1)?>"></a>
                        </div>
                    <?php }?>
                </div>
            </div>
            <?php if(!empty($bestseller_products)){?>
                <?php $this->load->view('layouts/pages/product/bestseller', ['product' => (object) $bestseller_products[1]]);?>
            <?php }?>
        </div>
    </div>
</section>

<?php if(!empty($new_products)) {?>
    <section class="news">
        <div class="wrapper">
            <div class="index-h2"><?=NEW_PRODUCTS?></div>
            <div class="slider__tovars">
                <?php foreach($new_products as $new_product){?>
                    <?php $this->load->view('layouts/pages/product/slider', ['product' => (object) $new_product]);?>
                <?php }?>
            </div>
        </div>
    </section>
<?php }?>

<?php $this->load->view('/layouts/pages/banner', ['position' => 1]); ?>

<?php if(isset($blocks[1])){?>
    <section class="lft-photo-slider">
        <div class="wrapper">
            <div class="sides">
                <?php $src = newthumbs($blocks[1]->img, 'main_blocks', 272, 485, '272x485x1', 1)?>
                <?php $mobile_src = newthumbs($blocks[1]->mobile_img, 'main_blocks', 395, 575, '395x575x1', 1)?>
                <div class="photo">
                    <img src="<?=$src?>" alt="">
                    <span><?=$blocks[1]->title?></span>
                    <a href="<?=$blocks[1]->uri?>">
                        <span><?=$blocks[1]->uri_title?></span>
                        <img src="/dist/img/icons/arrPh.svg" alt="">
                    </a>
                </div>
                <style>
                    @media screen and (max-width: 768px){
                        .lft-photo-slider .slider__lft-photo-<?=$blocks[1]->id?> {
                            background: url(<?=$mobile_src?>) center center/cover no-repeat;
                        }
                    }
                </style>
                <div class="slider__lft-photo slider__lft-photo-<?=$blocks[1]->id?>">
                    <?php foreach($blocks[1]->products as $block_product){?>
                        <?php $this->load->view('layouts/pages/product/slider', ['product' => (object) $block_product]);?>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>
<?php }?>

<section class="benefit">
    <div class="wrapper">
        <div class="index-h2"><?=PROFITABLE_OFFER?></div>
        <?php $this->load->view('/layouts/pages/banner', ['position' => 2]); ?>
        <div class="block__square">
            <?php foreach($benefit_products as $benefit_product){?>
                <?php $this->load->view("layouts/pages/product/square", ['product'=> (object) $benefit_product]);?>
            <?php }?>
        </div>
    </div>
</section>

<?php if(!empty($watched_products)){?>
    <section class="seen">
        <div class="wrapper">
            <div class="index-h2"><?=YOU_WATCHED?></div>
            <div class="slider__tovars">
                <?php foreach($watched_products as $watched_product){?>
                    <?php $this->load->view("layouts/pages/product/slider", ['product'=> (object) $watched_product]);?>
                <?php }?>
            </div>
        </div>
    </section>
<?php }?>

<?php if(isset($blocks[2])){?>
    <section class="best">
        <div class="wrapper" id="tabs">
            <div class="index-h2"><?=$blocks[2]->title?></div>
            <?php if(isset($blocks[2]->categories) and !empty($blocks[2]->categories)) {?>
                <ul class="list-select">
                    <?php foreach($blocks[2]->categories as $block_category){?>
                        <li><a href="#tab-<?=$block_category->id?>"><?=$block_category->title?></a></li>
                    <?php }?>
                </ul>
                <?php foreach($blocks[2]->categories as $block_category){?>
                <div class="sides" id="tab-<?=$block_category->id?>">
                    <div class="block__square" style="width: 100%">
                        <?php foreach($blocks[2]->products as $block_product){?>
                            <?php if($block_product->category_id == $block_category->id){?>
                                <?php $this->load->view('layouts/pages/product/square', ['product' => (object) $block_product]);?>
                            <?php }?>
                        <?php }?>
                    </div>
                    <?php if(isset($bestseller_products[2])){?>
                        <?php $this->load->view('layouts/pages/product/bestseller', ['product' => (object) $bestseller_products[2]]);?>
                    <?php }?>
                </div>
            <?php }?>
            <?php }?>
        </div>
    </section>
    <script src="/dist/js/jquery-ui.min.js"></script>
    <script>
        $( function() {
            $( "#tabs" ).tabs();
        } );
    </script>
<?php }?>

<?php if(isset($blocks[3])){?>
    <section class="lft-photo-slider">
        <div class="wrapper">
            <div class="sides">
                <?php $src = newthumbs($blocks[3]->img, 'main_blocks', 272, 485, '272x485x1', 1); ?>
                <?php $mobile_src = newthumbs($blocks[3]->mobile_img, 'main_blocks', 395, 575, '395x575x1', 1)?>
                <div class="photo">
                    <img src="<?=$src?>" alt="">
                    <span><?=$blocks[3]->title?></span>
                    <a href="<?=$blocks[3]->uri?>">
                        <span><?=$blocks[3]->uri_title?></span>
                        <img src="/dist/img/icons/arrPh.svg" alt="">
                    </a>
                </div>
                <style>
                    @media screen and (max-width: 768px){
                        .lft-photo-slider .slider__lft-photo-<?=$blocks[3]->id?> {
                            background: url(<?=$mobile_src?>) center center/cover no-repeat;
                        }
                    }
                </style>
                <?php if(isset($blocks[3]->products) and !empty($blocks[3]->products)) {?>
                    <div class="slider__lft-photo slider__lft-photo-<?=$blocks[3]->id?>">
                        <?php foreach($blocks[3]->products as $block_product){?>
                            <?php $this->load->view('layouts/pages/product/slider', ['product' => (object) $block_product]);?>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        </div>
    </section>
<?php }?>

<?php $this->load->view('/layouts/pages/stores'); ?>

<?php $this->load->view('/layouts/pages/subscribe'); ?>

<?php if(!empty($popular_categories)){?>
    <section class="categories">
        <div class="wrapper">
            <div class="index-h2"><?=POPULAR_CATEGORIES?></div>
            <div class="categories__block">
                <?php foreach ($popular_categories as $popular_category) {?>
                    <div class="categories__item">
                        <div class="photo"><img src="<?=newthumbs($popular_category['img'], 'category', 115, 115, '115x115x0', 0)?>" alt=""></div>
                        <p><a href="/<?=$lclang?>/<?=$menu['all'][11]->uri?>/<?=$popular_category['uri']?>"><?=$popular_category['title']?></a></p>
                    </div>
                <?php }?>
            </div>
        </div>
    </section>
<?php }?>

<?php $this->load->view('/layouts/pages/brands'); ?>

<?php $this->load->view('/layouts/pages/banner', ['position' => 3]); ?>

<?php $this->load->view('/layouts/pages/service'); ?>

<link rel="stylesheet" href="/app/css/carousel.css?time=<?=time()?>">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" type="application/javascript"></script>

<script>
    const topSlider = document.getElementById("top__slider");
    const mobileSlider = document.getElementById("mobile__top__slider");

    const topCarouselIndicators = topSlider.querySelectorAll(".carousel-indicators button span");
    const mobileCarouselIndicators = document.querySelectorAll("#mobile-carousel-indicators button span");
    const mobileCarouselButton = $("#mobile-carousel-indicators button");

    let intervalID;
    let intervalID2;

    const topCarousel = new bootstrap.Carousel(topSlider);
    const mobileCarousel = new bootstrap.Carousel(mobileSlider);

    window.addEventListener("load", function () {
        fillTopCarouselIndicator();
        fillMobileCarouselIndicator();
    });

    topSlider.addEventListener("slide.bs.carousel", function (e) {
        fillTopCarouselIndicator();
    });

    mobileSlider.addEventListener("slide.bs.carousel", function (e) {
        fillMobileCarouselIndicator(e.target);
    });

    function fillTopCarouselIndicator() {
        let i = 20;
        for (const topCarouselIndicator of topCarouselIndicators) {
            topCarouselIndicator.style.width = 0;
        }
        clearInterval(intervalID);
        topCarousel.pause();

        intervalID = setInterval(function () {
            i++;

            topSlider.querySelector(".carousel-indicators .active span").style.width =
                i + "%";

            if (i >= 100) {
                // i = 0; -> just in case
                topCarousel.next();
            }
        }, 50);
    }

    function fillMobileCarouselIndicator(target) {
        let current=$(target).find('.carousel-item.active');
        mobileCarouselButton.removeClass('active');
        let j=$(current).index();
        if((j+2)>mobileCarouselButton.length)
            j=-1
        $('#mobile-carousel-indicators button:nth-child('+(j+2)+')').addClass('active');

        let i2 = 20;
        for (const mobileCarouselIndicator of mobileCarouselIndicators) {
            mobileCarouselIndicator.style.width = 0;
        }
        clearInterval(intervalID2);
        mobileCarousel.pause();

        intervalID2 = setInterval(function () {
            i2++;

            document.querySelector("#mobile-carousel-indicators .active span").style.width =
                i2 + "%";

            if (i2 >= 100) {
                // i = 0; -> just in case
                mobileCarousel.next();
            }
        }, 50);
    }
</script>
