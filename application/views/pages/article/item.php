<?php $this->load->view("layouts/pages/breadcrumbs");?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<section class="deliver-info for-clients article-few article-one <?/*=(count($products) > 1) ? 'article-few' : 'article-one'*/?> index">
    <div class="wrapper">
        <div class="sides">
            <div class="content-text">
                <div class="content-important content__title-photo">
                    <img class="d-block" src="<?=newthumbs($page->image_head, 'article', 1192, 415, '1192x415x1', 1)?>" alt="">
                    <img class="d-none" src="<?=newthumbs($page->image_list, 'article', 335, 245, '335x245x1', 1)?>" alt="">
                </div>
                <div class="content-top ck-editor">
                    <?=$page->text?>
                </div>
                <?php if($products) {?>
                    <?php if(count($products) > 1){?>
                        <div class="content-top">
                            <div class="slider-tovars-title">
                                <h2 class="title-h2 title-h2-new">
                                    <?=SELECT_MODEL?>
                                </h2>
                            </div>
                        </div>
                        <div class="slider__tovars d-block">
                            <?php foreach($products as $product) {?>
                                <?php $this->load->view('layouts/pages/product/slider', ['product' => $product]);?>
                            <?php }?>
                        </div>
                    <?php } else {?>
                        <?php $product = current($products);?>
                        <div class="product bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
                            <div class="wrapper">
                                <div class="sides">
                                    <div class="side__sliders">
                                        <div class="brand">
                                            <img src="<?=newthumbs($product->brand_id.'.jpg', 'brand', 100, 18, '100x18x0', 0)?>">
                                            <?php if($product->labels) {?>
                                                <div class="photo__sticks">
                                                    <?php foreach($product->labels as $label) {?>
                                                        <span class="span-top" style="background: <?=$label->color?>">
                                                            <span><?=$label->title?></span>
                                                        </span>
                                                    <?php }?>
                                                </div>
                                            <?php }?>
                                        </div>
                                        <div class=" slider-for-1">
                                            <?php if($photos) {?>
                                                <?php foreach($photos as $photo){?>
                                                    <div class="slide"><img src="<?=product_image($photo, $product->articol, $product->first_color, 222, 166)?>" alt="<?=$product->title?>"></div>
                                                <?php }?>
                                            <?php } else {?>
                                                <div class="slide"><img src="<?=newthumbs("1.jpg", "products", 222, 166, "222x166x0", 0)?>" alt="<?=$product->title?>"></div>
                                            <?php }?>
                                        </div>
                                        <div class=" slider-nav-1">
                                            <?php foreach($photos as $photo){?>
                                                <div class="slide"><img src="<?=product_image($photo, $product->articol, $product->first_color, 68, 68)?>" alt="<?=$product->title?>"></div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="side__info">
                                        <div class="product__price">
                                            <?=numberFormat($product->discounted_price)?>,–
                                            <?php if($product->uds_cashback) {?>
                                                <span class="pls"><img src="/dist/img/icons/i34.svg"><span>+<?=$product->uds_cashback?></span></span>
                                            <?php }?>
                                        </div>
                                        <div class="product__old">
                                            <?php if($product->price > $product->discounted_price){?>
                                                <del class="old__price"><?=numberFormat($product->price)?>,–</del>
                                                <span class="old__sale">−<?=numberFormat($product->price - $product->discounted_price)?> <?=LEI?></span>
                                            <?php }?>
                                        </div>
                                        <?php if ($product->price >= 1000  && !$product->preorder && in_array($product->partner_id, $credit_partner_ids)) { ?>
                                            <div class="product__rasr"><?=BUY_IN_CREDIT?> <?=calculateCredit($product->discounted_price, MONTH_RATE, $credit)?>,– /<?=MONTH?></div>
                                        <?php }?>
                                        <div class="product__rating">
                                            <div class="stars reviews__stars-<?=$product->id?>"></div>
                                            <a href="#"><?=generateFeedbackText($product->feedbacks_count, false);?></a>
                                        </div>
                                        <div class="product__name">
                                            <?=$product->title?>
                                        </div>
                                        <div class="product__dop">
                                            <span><?=COURIER_TO?> <?= $deliveryCourier['city'] ?>: <?=$deliveryCourier['day']?></span>
                                            <span><?=PICKUP_TODAY?></span>
                                        </div>
                                        <?php if($product->partner_id > 1) {?>
                                            <div class="product__error">
                                                <span><?=PARTNER_PRODUCT?></span>
                                            </div>
                                        <?php }?>
                                        <?php if ($product->preorder) {?>
                                            <div class="product__error">
                                                <img src="/dist/img/icons/Time.svg">
                                                <span><?= str_replace('{days}', PREORDER_DAYS, PREORDER_TITLE) ?></span>
                                            </div>
                                        <?php } ?>
                                        <?php if ($product->qty == 1 and !$product->preorder) {?>
                                            <div class="product__error">
                                                <img src="/dist/img/icons/Warning.svg">
                                                <span><?= ONE_ITEM_LEFT ?></span>
                                            </div>
                                        <?php } ?>
                                        <a class="btn check_color_and_size" href="#"><?=ADD_TO_CART?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(".reviews__stars-<?=$product->id?>").starRating({
                                starSize: 20,
                                readOnly: true,
                                totalStars: 5,
                                emptyColor: 'lightgray',
                                activeColor: 'rgb(213 9 16)',
                                initialRating: <?=!$product->score ? 0 : $product->score ?>,
                                strokeWidth: 0,
                                useGradient: false,
                                minRating: 1,
                            });
                        </script>
                    <?php }?>
                <?php }?>

                <?php if(!empty($lastArticles)) {?>
                    <br>
                    <div class="content-top">
                        <div class="content-important">
                            <div class="content-bottom">
                                <?php foreach ($lastArticles as $lastArticle) {?>
                                    <div class="bottom-item">
                                        <a href="/<?=$lclang?>/<?=$menu['all'][25]->uri?>/<?=$lastArticle->uri?>/">
                                            <img src="<?=newthumbs($lastArticle->image_list, 'article', 335, 245, '335x245x1', 1)?>" alt="">
                                        </a>
                                        <div class="title-h4">
                                            <a href="/<?=$lclang?>/<?=$menu['all'][25]->uri?>/<?=$lastArticle->uri?>/">
                                                <?=$lastArticle->title?>
                                            </a>
                                        </div>
                                        <p class="text-p">
                                            <?=$lastArticle->announce?>
                                        </p>
                                        <p class="text-span">
                                            <?=date('d.m.Y', strtotime($lastArticle->add_date))?>
                                        </p>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</section>
<style>
    .slick-track {
        margin-left: 0;
    }
    .article-one .slick-initialized .slick-slide {
        margin-bottom: 0;
    }
    .slick-next,
    .slick-prev {
        top: 100px;
    }
</style>
