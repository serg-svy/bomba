<?php $this->load->view("layouts/pages/structured_data/product");?>
<?php $this->load->view('layouts/pages/retail_rocket/product'); ?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>
<script src="/dist/js/fancybox.umd.js"></script>
<link rel="stylesheet" href="/dist/css/fancybox.css">
<style>
    .fancybox__backdrop,
    .fancybox__toolbar {
        background: #fff;
    }
    .carousel__button {
        color: #a4a4a5;
    }
    .carousel__button svg{
        filter: none;
    }
</style>
<script>
    Fancybox.bind('[data-fancybox="gallery"]', {
        Toolbar: {
            display: [
                "close",
            ],
        },
    });
</script>

<?php $this->load->view("layouts/pages/breadcrumbs");?>

<section class="product bv_data" data-size="<?=(!empty($product->sizes)) ? 'null' : 'NO_SIZE';?>" data-color="<?=(!empty($product->colors)) ? 'null' : 'NO_COLOR';?>" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
    <div class="wrapper">
        <?php if(isset($_SESSION['quick_order'])) {?>
            <div class="alert alert-success"><?=ORDER_SUCCESSFULLY_SENT?></div>
            <?php unset($_SESSION['quick_order'])?>
        <?php }?>
        <?php if(isset($_SESSION['preorder_order'])) {?>
            <div class="alert alert-success"><?=ORDER_SUCCESSFULLY_SENT?></div>
            <?php unset($_SESSION['preorder_order'])?>
        <?php }?>
        <div class="sides">
            <div class="side__sliders">
                <div class="brand">
                    <img src="<?=newthumbs($product->brand_id.'.jpg', 'brand', 180, 60, '180x60x0', 0)?>" alt="">
                </div>
                <div class="slider-for">
                    <?php if($photos) {?>
                        <?php foreach($photos as $photo){?>
                            <div href="/public/products/<?=substr($product->articol, -2)?>/<?=$product->articol?>/<?=$product->first_color?>/<?=$photo?>" class="slide" data-fancybox="gallery"><img alt="<?=$product->title?>" src="<?=product_image($photo, $product->articol, $product->first_color, 968, 630)?>"></div>
                        <?php }?>
                    <?php } else {?>
                        <div class="slide"><img src="<?=newthumbs("1.jpg", "products", 968, 630, "968x630x0", 0)?>" alt="<?=$product->title?>"></div>
                    <?php }?>
                </div>
                <div class="slider-nav">
                    <?php foreach($photos as $photo){?>
                        <div class="slide">
                            <img src="<?=product_image($photo, $product->articol, $product->first_color, 68, 68)?>" alt="<?=$product->title?>">
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="side__info side__info122">
                <div class="ff-top">
                    <div class="product__price">
                        <?=numberFormat($product->discounted_price)?>,–
                        <?php if($product->uds_cashback) {?>
                            <span class="pls d-none">
                                <img src="/dist/img/icons/i34.svg">
                                <span>+<?=$product->uds_cashback?></span>
                            </span>
                        <?php }?>
                    </div>
                    <?php if($product->price > $product->discounted_price){?>
                        <div class="product__old">
                            <del class="old__price"><?=numberFormat($product->price)?>,–</del>
                            <span class="old__sale">−<?=numberFormat($product->price - $product->discounted_price)?> <?=LEI?></span>
                        </div>
                    <?php }?>
                    <?php if($product->uds_cashback) {?>
                        <span class="pls"><img src="/dist/img/icons/i34.svg"><span>+<?=$product->uds_cashback?> бонусных лей</span></span>
                    <?php }?>
                    <?php if($product->preorder){?>
                        <div class="product__error"><img src="/dist/img/icons/Time.svg"><span><?= str_replace('{days}', PREORDER_DAYS, PREORDER_TITLE) ?></span></div>
                    <?php }?>
                </div>
                <?php if($product->colors){?>
                    <div class="product__func prod_color_wr">
                        <h5><?=COLORS?></h5>
                        <div class="prod_color">
                            <?php foreach($product->colors as $key=>$color){?>
                                <label for="color-<?=$key?>" class="switch_c pr_s_item">
                                    <input type="radio" name="color" id="color-<?=$key?>" value="<?=$color?>">
                                    <span class="sw_c_in"><img width="40" src="<?=product_image('1.jpg', $product->articol, $color, 68, 68)?>" alt=""></span>
                                </label>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>
                <?php if($product->sizes){?>
                    <div class="product__func prod_size_wr">
                        <h5><?=SIZES?></h5>
                        <div class="prod_size">
                            <?php foreach($product->sizes as $key=>$size){?>
                                    <label for="size-<?=$key?>" class="switch_c pr_s_item">
                                        <input type="radio" name="size" id="size-<?=$key?>" value="<?=$size?>">
                                        <span class="sw_c_in"><?=$size?></span>
                                    </label>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>
                <?php if($product->preorder){?>
                    <a class="btn preorder" href="#"><?=PREORDER?></a>
                <?php } else {?>
                    <div class="prod_btn">
                        <?php $this->load->view('layouts/pages/product/btn')?>
                    </div>
                    <a class="btn2 quick_order" href="#"><?=QUICK_ORDER?></a>
                <?php }?>
                <?php if ($product->price >= 1000  && !$product->preorder && in_array($product->partner_id, $credit_partner_ids)) { ?>
                    <?php if($product->rate) {?>
                        <div class="_in_installments">
                            <?=INSTALLMENTS?>
                            <?=numberFormat($product->discounted_price/$product->rate)?>
                            <?=LEI?>
                            <?=IN_MONTH?>
                        </div>
                    <?php }?>
                    <div class="_in_credit">
                        <?=CREDIT?>
                        <?=calculateCredit($product->discounted_price, MONTH_RATE, $credit)?>
                        <?=LEI?>
                        <?=IN_MONTH?>
                    </div>
                    <a class="btn3 buy_in_credit" href="#"><?=($product->rate) ? BUY_IN_INSTALLMENTS_OR_CREDIT : BUY_IN_CREDIT ?></a>
                    <div class="detail">
                        <span class="cl-pop">
                            <?=file_get_contents(base_url()."/dist/img/icons/i47.svg");?>
                            <?=PROGRAM_CONDITION?>
                            <div class="hint">
                                <div class="hint-h4" style="font-size: 16px; line-height: 130%;"><?=CREDIT_TEXT?> </div>
                                <hr>
                                <div class="hint-h4"><?=CREDIT?></div>
                                <div class="credit_items">
                                    <?php foreach ($credit as $credit_item) {?>
                                        <div class="credit_item">
                                            <div class="credit_item_title"><?=$credit_item['months']?> <?=SHORT_MONTH?></div>
                                            <div class="credit_item_text"><?=calculateCredit($product->discounted_price, $credit_item['months'], $credit)?>,– <br> <?=IN_MONTH?></div>
                                        </div>
                                    <?php }?>
                                </div>
                                <?php if($product->rate) {?>
                                    <hr>
                                    <div class="hint-h4"><?=INSTALLMENTS?></div>
                                    <div class="credit_items">
                                        <div class="credit_item">
                                            <div class="credit_item_title"><?=$product->rate?> <?=SHORT_MONTH?></div>
                                            <div class="credit_item_text"><?=numberFormat($product->discounted_price/$product->rate)?>,– <br> <?=IN_MONTH?></div>
                                        </div>
                                    </div>
                                <?php }?>
                                <a href="/<?=$lclang?>/<?=$menu['all'][8]->uri?>"><?=LEARN_MORE?></a>
                            </div>
                        </span>
                    </div>
                <?php }?>
                <hr>
                <div class="delivery">
                    <span><?=DELIVERY_AND_AVAILABILITY?></span>
                    <div class="its">
                        <div class="it"><img src="/dist/img/icons/i42.svg"><span><?=AVAILABLE_IN_ONLINE_STORE?></span></div>
                        <div class="it">
                            <img src="/dist/img/icons/i43.svg">
                            <span>
                                <?=COURIER_TO?>
                                <samp class="city_title"><?= $deliveryCourier['city'] ?></samp>:
                                <span><?=$deliveryCourier['day']?></span>
                                <br class="d-none">
                                <?=FREE_FROM?> <?=$deliveryCourier['free']?> <?=LEI?>
                            </span>
                            <div class="it_popup">
                                <p><?=SHIPPING_COAST_VARY?></p><a href="/<?=$lclang?>/<?=$menu['all'][2]->uri?>"><?=MORE_ABOUT_DELIVERY?></a>
                            </div>
                        </div>
                        <div class="it"><img src="/dist/img/icons/i31.svg"><span><?=PICKUP_FROM?> <span><?=count($stores)?> <?=STORES?></span> <br class="d-none"></span>
                            <div class="it_popup">
                                <?php foreach($stores as $store){?>
                                    <div class="it_item">
                                        <div class="item__lft">
                                            <div class="line <?=($store->quantity >= 1) ? 'line-act' : ''?>"></div>
                                            <div class="line <?=($store->quantity >= 2) ? 'line-act' : ''?>"></div>
                                            <div class="line <?=($store->quantity >= 3) ? 'line-act' : ''?>"></div>
                                        </div>
                                        <div class="item__rht">
                                            <div><?=$store->title?></div>
                                            <p><?=$store->address?></p>
                                        </div>
                                    </div>
                                <?php }?>
                                <a href="/<?=$lclang?>/<?=$menu['all'][14]->uri?>"><?=SEE_STORE_MAP?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="product-bottom">
    <div class="wrapper">
        <?php if($filters){?>
            <div class="specifications" id="specifications">
                <div class="product__title"><?=SPECIFICATIONS?></div>
                <div class="product__block">
                    <div class="product__specifications toggle_text gradient">
                        <?php foreach ($filters as $filter) {?>
                            <?php
                                $show_head = false;
                                foreach ($filter['attributes'] as $akey => &$attr) {
                                    if (!empty($attr['values']['value'])) {
                                        $attr['values']['value'] = trim($attr['values']['value']);
                                        $attr['values']['value'] = str_replace("\N", '', @$attr['values']['value']);
                                        $attr['values']['value'] = str_replace("\n", '', @$attr['values']['value']);
                                    }
                                    if (!empty($attr['values']['value']) && trim($attr['values']['value']) != '0' && $attr['in_filter'] == 0) $show_head = true;
                                }
                                if (!$show_head) continue;
                            ?>
                            <div class="titleh5"><?=$filter['name']?></div>
                            <ul>
                                <?php foreach ($filter['attributes'] as $attribute) { ?>
                                    <?php if (empty($attribute['values']) || $attribute['values']['value'] == '' || $attribute['in_filter']) continue; ?>
                                    <?php
                                        if ($attribute['attribute_type'] == 'boolean') {
                                            if (isset($attribute['values']['value'])) {
                                                if ($attribute['values']['value'] == 1) $attribute['values']['value'] = YES;
                                                elseif ($attribute['values']['value'] == 0) $attribute['values']['value'] = NO;
                                            }
                                        }

                                    ?>
                                    <li><span class="span-first"><?=$attribute['name']?></span><span class="span-last"><?=str_replace("\N", '-', $attribute['values']['value'])?></span></li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </div>
                    <a class="toggle_button" data-new="<?=NARROW?>" href="#"><?=ALL_SPECIFICATIONS?></a>
                </div>
            </div>
        <?php }?>
        <?php if($product->description || $blocks){?>
            <div class="description">
                <div class="product__title"><?=DESCRIPTION?></div>
                <div class="product__block">
                    <?php if($product->description) {?>
                        <div class="text <?php if(strlen($product->description) > 500){?>toggle_text gradient<?php }?>" style="max-width: 100%;<?php if(strlen($product->description) > 500){?>height:80px;<?php }?>">
                            <div class="ck_editor">
                                <?=$product->description?>
                            </div>

                            <?php $this->load->view("layouts/pages/product/flix", ['product'=>$product, 'lang' => $lclang]);?>

                            <?php foreach ($blocks as $block){ ?>
                                <?php switch ($block->position) {
                                    case 0:?>
                                        <div class="text_image">
                                            <span class="ck-editor"><?= $block->text ?></span>
                                            <span><img src="<?= newthumbs($block->img, 'product_block', 590, 370, '590x370x0', 0) ?>"/></span>
                                        </div>
                                        <?php break; ?>
                                    <?php case 1:?>
                                        <div class="text_image inverse">
                                            <span class="ck-editor"><?= $block->text ?></span>
                                            <span><img src="<?= newthumbs($block->img, 'product_block', 590, 370, '590x370x0', 0) ?>"/></span>
                                        </div>
                                        <?php break; ?>
                                    <?php case 2:?>
                                        2
                                        <?php break; ?>
                                    <?php case 3:?>
                                        3
                                        <?php break; ?>
                                    <?php case 4:?>
                                        <div class="text_video"><?= $block->text ?></div>
                                        <?php break; ?>
                                    <?php case 5:?>
                                        <div class="text_text"><?= $block->text ?></div>
                                        <?php break; ?>
                                    <?php }?>
                            <?php }?>
                        </div>
                    <?php }?>
                    <?php if(strlen($product->description) > 500){?>
                        <a class="toggle_button" data-new="<?=NARROW?>" href="#"><?=EXPAND?></a>
                    <?php }?>
                </div>
            </div>
        <?php }?>
        <?php if($instructions) {?>
            <div class="documents">
                <div class="product__title"><?=DOCUMENTS?></div>
                <div class="product__block">
                    <?php foreach ($instructions as $instruction){ ?>
                        <a target="_blank" class="block__item" href="<?=$instruction['path']?>"><img src="/dist/img/icons/i45.svg">
                            <div class="it-tx">
                                <h5><?=$instruction['label']?> <?=$product->title?></h5><span><?=$instruction['size']?></span>
                            </div>
                        </a>
                    <?php }?>
                </div>
            </div>
        <?php }?>

        <?php if($related_products){?>
            <div class="tovars">
                <div class="product__title">Сопутствующие товары</div>
                <div class="slider__tovars">
                    <?php foreach($related_products as $related_product) {?>
                        <?php $this->load->view('layouts/pages/product/slider', ['product'=> (object) $related_product]);?>
                    <?php }?>
                </div>
            </div>
        <?php }?>
        <?php if($feedbacks){?>
            <div class="reviews">
                <div class="product__title"><?=FEEDBACKS?></div>
                <div class="product__block">
                    <div class="reviews__top"><span><?=generateFeedbackText($product->feedbacks_count)?></span>
                        <div class="reviews__rating"><span><?=OVERAL_RATING?></span>
                            <div class="stars">
                                <div class="main__reviews__stars"></div>
                                <span><?=numberFormat($product->score, 1)?></span>
                            </div>
                        </div><a class="write-review" href="#review"><?=LEAVE_FEEDBACK?></a>
                    </div>
                    <div class="reviews__center">
                        <?php foreach($feedbacks as $key=>$feedback) {?>
                            <div class="review__item" style="display: <?=($key < 3) ? 'block' : 'none' ?>">
                                <div class="item__top">
                                    <div class="stars start-<?=$feedback->product_id?>"></div>
                                    <script>
                                        $(".start-<?=$feedback->product_id?>").starRating({
                                            starSize: 20,
                                            readOnly: true,
                                            totalStars: 5,
                                            emptyColor: 'lightgray',
                                            activeColor: 'rgb(213 9 16)',
                                            initialRating: <?=!$feedback->score ? 0 : $feedback->score ?>,
                                            strokeWidth: 0,
                                            useGradient: false,
                                            minRating: 1,
                                        });
                                    </script>
                                    <div class="name"><?=$feedback->first_name?> <?=$feedback->last_name?></div>
                                    <data class="date"><?=date('d.m.Y', strtotime($feedback->date))?></data>
                                </div>
                                <div class="item__text"><?=$feedback->text?></div>
                                <?php if ($feedback->img){?>
                                    <div class="item__photos">
                                        <div class="bl">
                                            <?php foreach(explode(' ', trim($feedback->img)) as $feedback_img){?>
                                                <div class="bl-it">
                                                    <img src="/public/product_feedback/<?=$product->id?>/<?=$feedback_img?>" alt="">
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        <?php }?>
                        <?php if(count($feedbacks) > 3){?>
                                <? $newCount = count($feedbacks) - 3 ?>
                            <a class="toggle_button" data-new="Свернуть" href="#"><?=MORE?> <?=generateFeedbackText($newCount)?></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
</section>
<section class="btn bottom_pop bv_data" data-size="<?=(!empty($product->sizes)) ? 'null' : 'NO_SIZE';?>" data-color="<?=(!empty($product->colors)) ? 'null' : 'NO_COLOR';?>" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
    <p><?=numberFormat($product->discounted_price)?>,–</p><a href="#" class="btn add_to_cart"><?=TO_CART?></a>
</section>
<?php $this->load->view("layouts/pages/popup/reviews")?>
<?php $this->load->view("layouts/pages/popup/quick")?>
<?php $this->load->view("layouts/pages/popup/preorder")?>
<?php $this->load->view("layouts/pages/popup/credit")?>
<?php if(isset($_SESSION['popup__success-title'])) {?>
    <?php $this->load->view("layouts/pages/popup/success")?>
    <?php unset($_SESSION['popup__success-title']);?>
<?php }?>

<script>
    $(".main__reviews__stars").starRating({
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
<?php $_SESSION['watched_products'][] = $product->id; ?>
<style>
    .text_image {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
    }
    .text_image span img{
        width: 100%;
    }
    .text_image span:nth-child(1){
        flex-basis: 65%;
    }
    .text_image span:nth-child(2) {
        flex-basis: 35%;
        margin-left: 30px;
        margin-right: 0;
    }
    .text_image.inverse span:nth-child(1){
        flex-basis: 65%;
    }
    .text_image.inverse span:nth-child(2) {
        flex-basis: 35%;
        order: -1;
        margin-right: 30px;
        margin-left: 0;
    }
    @media only screen and (max-width: 640px) {
        .text_image {
            flex-direction: column;
        }

        .text_image span {
            flex-basis: 100% !important;
            order: -1 !important;
        }
    }
</style>
