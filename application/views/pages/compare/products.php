<?php $this->load->view("layouts/pages/breadcrumbs");?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<section class="simile">
    <div class="wrapper">
        <div class="simile__top-block">
            <div class="block__fix">
                <div class="simile__list">
                    <ul>
                        <?php $flag = true;?>
                        <?php foreach($find_categories as $category) {?>
                            <li class="<?=($cat == $category['id']) ? 'li_active' : '' ?>">
                                <a href="?cat=<?=$category['id']?>"><?=$category['title']?></a>
                                <p class="num"><?=$category['count']?></p>
                            </li>
                            <?php $flag = false;?>
                        <?php }?>
                    </ul><a data-ids='<?=json_encode(array_map(function($item) {return $item['id'];}, $products))?>' class="list_clean" href="#"><?=CLEAR_LIST?></a>
                </div>
                <div class="simile__products">
                    <div class="lft">
                        <div class="checkbox__item">
                            <label for="sr1">
                                <input type="checkbox" id="sr1">
                                <span><?=SHOW_ONLY_DIFERRENCES?></span>
                            </label>
                        </div>
                        <?php if(count($products) > 2) {?>
                            <div class="arrows">
                                <div class="arrow arrow-lft" tabindex="1"></div>
                                <div class="arrow arrow-rht" tabindex="2"></div>
                            </div>
                        <?php }?>
                    </div>
                    <div class="rht simile-slider" style="display: block">
                        <?php foreach ($products as $product) {?>
                            <div class="rht__item bv_data" data-id="<?=$product['id']?>" data-articol="<?=$product['articol']?>">
                                <img class="delete delete_compare" src="/dist/img/icons/Delete.svg">
                                <div class="simile-slider-sides">
                                    <a href="/<?=$lclang?>/<?=$menu['all'][17]->uri?>/<?=$product['id']?>" class="photo"><img src="<?=product_image('1.jpg', $product['articol'], $product['first_color'], 222, 166)?>"></a>
                                    <div class="simile-slider-rth">
                                        <div class="stock"><?=IN_STOCK?></div>
                                        <div class="price"><?=numberFormat($product['discounted_price'])?>,–</div>
                                        <?php if($product['price'] > $product['discounted_price']){?>
                                            <div class="product__old">
                                                <del class="old__price"><?=numberFormat($product['price'])?>,–</del><span class="old__sale">−<?=numberFormat($product['price'] - $product['discounted_price'])?> <?=LEI?></span>
                                            </div>
                                        <?php } else {?>
                                            <div class="product__old"></div>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="name">
                                    <a href="/<?=$lclang?>/<?=$menu['all'][17]->uri?>/<?=$product['id']?>"><?=$product['title']?></a>
                                </div>
                                <input type="submit" class="btn check_color_and_size" value="<?=TO_CART?>">
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <div class="simile__settings">
            <div class="settings__block">
                <h4><?=INSTALLMENTS_AND_CREDIT?></h4>
                <div class="settings__body <?=count(array_unique(array_column($products, 'rate'))) == 1 ? 'same' : 'diff'?>">
                    <h5><?=INSTALLMENTS?></h5>
                    <div class="setting__items simile-slider-settings">
                        <?php foreach ($products as $product) {?>
                            <div class="setting__item">
                                <?php if($product['rate']) {?>
                                    <div class="_in_installments">
                                        <?=INSTALLMENTS?>
                                        <?=numberFormat($product['discounted_price']/$product['rate'])?>
                                        <?=LEI?>
                                        <?=MONTH?>
                                    </div>
                                <?php }?>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="settings__body">
                    <h5><?=CREDIT?></h5>
                    <div class="setting__items simile-slider-settings">
                        <?php foreach ($products as $product) {?>
                            <div class="setting__item">
                                <?=calculateCredit($product['discounted_price'], MONTH_RATE, $credit)?>,– /<?=MONTH?>
                                <br>
                                <?php foreach($credit as $key=>$credit_item) {?>
                                    <?=($key != 0) ? ',' : ''?> <?=$credit_item['months']?> <?=MONTH?>
                                <?php }?>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="settings__block">
                <h4><?=DELIVERY_AND_PICKUP?></h4>
                <div class="settings__body same">
                    <h5><?=DELIVERY?></h5>
                    <div class="setting__items simile-slider-settings">
                        <?php foreach ($products as $product) {?>
                            <div class="setting__item">
                                <?=COURIER_TO?> <?= $deliveryCourier['city'] ?>: <?=$deliveryCourier['day']?>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <div class="settings__body same">
                    <h5><?=PICKUP?></h5>
                    <div class="setting__items simile-slider-settings">
                        <?php foreach ($products as $product) {?>
                            <div class="setting__item" style="padding-right: 30px;">
                                <?=PICKUP_TODAY?>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="settings__block">
                <h4><?=PURCHASE_BONUSES?></h4>
                <div class="settings__body <?=count(array_unique(array_column($products, 'uds_cashback'))) == 1 ? 'same' : 'diff'?>">
                    <h5><?=BONUSES?></h5>
                    <div class="setting__items simile-slider-settings">
                        <?php foreach ($products as $product) {?>
                            <div class="setting__item">
                                <?php if($product['uds_cashback']) {?>
                                    <span class="pls" style="display: flex"><img src="/dist/img/icons/i34.svg"><span>+<?=$product['uds_cashback']?> <?=BONUS_LEI?></span></span>
                                <?php }?>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="settings__block">
                <h4><?=SCORE?></h4>
                <div class="settings__body <?=count(array_unique(array_column($products, 'feedbacks_count'))) == 1 ? 'same' : 'diff'?>">
                    <h5><?=SCORE?></h5>
                    <div class="setting__items simile-slider-settings">
                        <?php foreach ($products as $product) {?>
                            <div class="setting__item">
                                <div class="stars reviews__stars-<?=$product['id']?>"></div>
                                <a class="review-num" href="#"><?=generateFeedbackText($product['feedbacks_count']);?></a>
                                <script>
                                    $(".reviews__stars-<?=$product['id']?>").starRating({
                                        starSize: 20,
                                        readOnly: true,
                                        totalStars: 5,
                                        emptyColor: 'lightgray',
                                        activeColor: 'rgb(213 9 16)',
                                        initialRating: <?=!$product['score'] ? 0 : $product['score'] ?>,
                                        strokeWidth: 0,
                                        useGradient: false,
                                        minRating: 1,
                                    });
                                </script>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>

            <div class="settings__block">
                <?php foreach($real as $group) {?>
                    <div>
                        <h4><?=$group['group_name']?></h4>
                        <?php foreach ($group['attributes'] as $attribute) {?>
                            <?php if(!$attribute['hidden']) {?>
                                <div class="settings__body <?=$attribute['class']?>">
                                    <h5><?=$attribute['attribute_name']?></h5>
                                    <div class="setting__items simile-slider-settings">
                                        <?php foreach($attribute['values'] as $value) {?>
                                            <div class="setting__item"><span><?=$value?></span></div>
                                        <?php }?>
                                    </div>
                                </div>
                            <?php }?>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        </div>
        <style>
            .simile-slider .slick-track,
            .simile-slider-settings .slick-track {
                display: flex;
                align-items: stretch;
            }
        </style>
        <script>
            $('.simile-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
                let direction;
                if((currentSlide<nextSlide&&currentSlide===nextSlide-1)||(currentSlide===slick.slideCount-1&&nextSlide===0)) {
                    direction='right';
                }
                else if(nextSlide<currentSlide||(nextSlide===slick.slideCount-1&&currentSlide===0)) {
                    direction='left';
                }
                if(direction==='right'){
                    $(".simile-slider-settings").slick("slickNext");
                }
                if(direction==='left') {
                    $(".simile-slider-settings").slick("slickPrev");
                }
            });
        </script>
    </div>
</section>
<style>
    .simile-slider-sides{
        display: flex;
        flex-direction: column;
    }
    .simile-fix .simile-slider-sides .photo{
        flex-basis: 50%;
        max-height: 40px !important;
    }
    .simile-fix .simile-slider-sides .simile-slider-rth {
        flex-basis: 50%;
        display: flex;
        flex-direction: column;
    }
    .simile-fix .simile-slider-sides .simile-slider-rth .stock {
        order: 1;
        margin-top: 15px;
    }

</style>
