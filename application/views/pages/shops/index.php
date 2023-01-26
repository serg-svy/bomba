<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="shops">
    <div class="wrapper">
        <div class="sides">
            <div class="content-text">
                <div class="content-important content__title-photo">
                    <img class="d-block" src="<?=newthumbs($shop->banner, 'shops', 1408, 415, '1408x415x1', 1)?>" alt="">
                    <img class="d-none" src="<?=newthumbs($shop->mobile_banner, 'shops', 328, 480, '328x480x1', 1)?>" alt="">
                </div>
            </div>
            <h2 class="shop-title"><?=BESTSELLER?></h2>
            <div class="shop-bestseller">
                <?php foreach($best_products as $key=>$best_product) {?>
                    <?php if($key == 0){?>
                        <?php $this->load->view('layouts/pages/product/shop__best', ['product' => (object) $best_product]);?>
                    <?php } else {?>
                        <?php $this->load->view('layouts/pages/product/shop__square', ['product' => (object) $best_product]);?>
                    <?php }?>
                <?php }?>
            </div>
            <div class="shop-advantages">
                <?php foreach ($advantages as $advantage) {?>
                    <div class="shop-advantage">
                        <img src="<?=newthumbs($advantage->image, 'shop_advantages', 60, 60, '60x60x1', 1)?>" alt="">
                        <h4><?=$advantage->title?></h4>
                    </div>
                <?php }?>
            </div>
            <h2 class="shop-title"><?=PRODUCTS?></h2>
            <div class="shop-categories">
                <?php foreach ($shop_categories as $shop_category) {?>
                    <a href="<?=$shop_category->uri?>" class="shop-category">
                        <img src="<?=newthumbs($shop_category->image, 'shop_categories', 448, 392, '448x392x1', 1)?>" alt="">
                    </a>
                <?php }?>
            </div>
            <?php foreach ($product_categories as $product_category) {?>
                <h2 class="shop-title"><?=$product_category->title?></h2>
                <?php if(!empty($product_category->banners)) {?>
                    <section class="banner__slider allDevices">
                        <?php foreach($product_category->banners as $banner) {?>
                            <div>
                                <a href="<?=$banner->link?>" class="photo-banner__img">
                                    <img style="width: 100%" src="<?=newthumbs($banner->img, 'category_banners', 1408, 184, '1408x184x1', 1)?>" alt="">
                                </a>
                            </div>
                        <?php }?>
                    </section>
                <?php }?>
                <?php if(!empty($product_category->mobile_banners)) {?>
                    <section class="banner__slider verticalPhone">
                        <?php foreach($product_category->mobile_banners as $mobile_banner) {?>
                            <div>
                                <a href="<?=$mobile_banner->link?>" class="photo-banner__img">
                                    <img src="<?=newthumbs($mobile_banner->img, 'category_banners', 328, 288, '328x288x1', 1)?>" alt="">
                                </a>
                            </div>
                        <?php }?>
                    </section>
                <?php }?>
                <div class="block__square">
                    <?php foreach ($product_category->products as $product) {?>
                        <?php $this->load->view('layouts/pages/product/square', ['product' => (object) $product]);?>
                    <?php }?>
                </div>
            <?php }?>
        </div>
    </div>
</section>
<style>
    .shop-bestseller {
        margin-bottom: 56px;
        display: grid;
        grid-template-columns: 328px 238px 238px 238px 238px;
        grid-gap: 32px;
        grid-template-areas:
        "best"
        "best";
    }
    .shop-bestseller .shop__product  {
        background-color: #fff;
        border-radius: 16px;
        height: 238px;
        margin: 0;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 16px;
    }
    .shop-bestseller .shop__product__best {
        grid-area: best;
        height: 100%;
        padding: 24px;
        justify-content: space-between;
    }
    .shop-bestseller .shop__product .product__buttons-2 {
        display: none !important;
    }
    .shop-bestseller .shop__product .product__photo {
        height: 110px;
        margin: 0 auto auto;
        text-align: center;
    }
    .shop-bestseller .shop__product .product__photo img{
        height: inherit;
    }
    .shop-bestseller .shop__product__best .product__photo {
        margin: auto;
        height: auto !important;
    }
    .shop-bestseller .shop__product__best .product__photo img{
        width: 100%;
    }
    .shop-bestseller .shop__product .product__price,
    .shop-bestseller .shop__product .product__old {
        margin: 0 0 4px;
    }
    .shop-bestseller .shop__product .product__name {
        margin: 0;
    }
    .shop-bestseller .shop__product .product__name a {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }
    .shops .content__title-photo img {
        width: 100%;
        border-radius: 16px;
    }
    .shop-advantages {
        display: flex;
        flex-direction: row;
        padding: 0;
        gap: 32px;
        flex-wrap: wrap;
    }
    .shop-advantage {
        display: flex;
        flex-basis: calc(25% - 24px);
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 21px;
        gap: 24px;
        background: #FFFFFF;
        border-radius: 16px;
    }
    .shop-advantage h4 {
        font-family: 'Futura PT';
        font-style: normal;
        font-weight: 700;
        font-size: 16px;
        line-height: 24px;
        text-align: center;
        text-transform: uppercase;
        color: #0D0802;

    }
    .shop-title {
        font-family: 'Futura PT';
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        line-height: 120%;
        text-transform: uppercase;
        color: #0D0802;
        margin-top: 56px;
        margin-bottom: 32px;
    }
    .shop-categories {
        display: flex;
        gap: 32px;
        flex-wrap: wrap;
    }
    .shop-category {
        flex-basis: calc(33.33% - 24px);
    }
    .shop-category img {
        width: 100%;
        border-radius: 16px;
    }
    .banner__slider img {
        border-radius: 16px;
    }
    .shop-bestseller .shop__product .product__price {
        font-size: 24px;
    }
    .shop-bestseller .shop__product__best .product__price {
        font-size: 28px;
    }
    .shop-bestseller .shop__product .product__name a {
        font-size: 16px;
    }
    .shop-bestseller .shop__product__best .product__name a {
        font-size: 18px;
        white-space: break-spaces;
    }
    .block__square .square__item .product__photo {
        height: 132px;
        width: 132px;
    }
    @media screen and (max-width:1488px) {
        .shop-bestseller {
            grid-template-columns: 256px 184px 184px 184px 184px;
        }
        .shop-bestseller .shop__product {
            height: 184px;
            padding: 12px;
        }
        .shop-bestseller .shop__product__best  {
            padding: 20px;
            height: 100%;
        }
        .shop-bestseller .shop__product .product__photo {
            height: 85px;
            padding: 0;
        }
        .shop-bestseller .shop__product .product__price {
            font-size: 18px;
        }
        .shop-bestseller .shop__product .old__sale {
            font-size: 10px;
        }
        .shop-bestseller .shop__product .product__name a {
            font-size: 14px;
        }
        .shop-bestseller .shop__product__best .product__name a {
            font-size: 16px;
        }
    }
    @media screen and (max-width:1024px) {
        .shop-advantage {
            flex-basis: calc(50% - 16px);
        }
        .shop-bestseller {
            grid-template-columns: 256px 160px 160px 160px;
        }
        .shop-bestseller .shop__product {
            height: 160px;
        }
        .shop-bestseller .shop__product__best  {
            padding: 16px;
            height: 100%;
        }
        .shop-bestseller div:nth-child(8),
        .shop-bestseller div:nth-child(9) {
            display: none;
        }
    }
    @media screen and (max-width:768px) {
        .shops .d-none {
            display: block;
        }
        .shops .d-block {
            display: none;
        }
        .shop-bestseller {
            grid-template-areas:
            "simple simple"
            "simple simple"
            "simple simple"
            "best best";
            grid-template-columns: 156px 156px;
            gap: 16px;
        }
        .shop-bestseller .shop__product {
            height: 156px;
            min-width: auto;
        }
        .shop-bestseller .shop__product__best  {
            padding: 20px;
            height: 400px;
        }
    }
    @media screen and (max-width:480px) {
        .shop-title {
            margin-top: 40px;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .shop-advantage {
            flex-basis: 100%;
        }
        .shop-category {
            flex-basis: 100%;
        }
    }
</style>
