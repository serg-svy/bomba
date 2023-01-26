<div class="product__item slider__item bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
    <div class="product__photo">
        <a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>/">
            <img src="<?=product_image('1.jpg', $product->articol, $product->first_color, 218, 166)?>" alt="<?=$product->title?>">
        </a>
    </div>
    <div class="product__price">
        <?=numberFormat($product->discounted_price)?>,–
    </div>
    <div class="product__old">
        <?php if($product->price > $product->discounted_price){?>
            <del class="old__price"><?=numberFormat($product->price)?>,–</del>
            <span class="old__sale">−<?=numberFormat($product->price - $product->discounted_price)?> <?=LEI?></span>
        <?php }?>
    </div>
    <div class="product__reviews">
        <div class="reviews__stars reviews__stars-<?=$product->id?>">
        </div>
        <div class="reviews__count d-block">
            <?=generateFeedbackText($product->feedbacks_count);?>
        </div>
        <div class="reviews__count d-none"><?=$product->feedbacks_count?></div>
    </div>
    <div class="product__name"><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>/"><?=$product->title?></a></div>
    <?php if ($product->price >= 1000  && !$product->preorder && in_array($product->partner_id, $credit_partner_ids)) { ?>
        <div class="product__error">
            <?=BUY_IN_CREDIT?> <?=calculateCredit($product->discounted_price, MONTH_RATE, $credit)?>,– /<?=MONTH?>
        </div>
    <?php }?>
    <div class="product__buttons d-none">
        <?php if ($product->preorder) {?>
            <input type="submit" class="btn" value="<?=PREORDER?>">
        <?php }else{?>
            <?php if($product->display){?>
                <input type="submit" class="btn check_color_and_size" value="<?=TO_CART?>" data-alternative="<?=IN_CART?>">
            <?php }?>
        <?php }?>
        <div class="product__buttons-icons">
            <div class="tooltip">
                    <span class="ico add_to_compare <?=(in_array($product->id, $_SESSION['compare'])) ? 'active' : ''?>">
                        <?=file_get_contents(base_url()."/dist/img/icons/status.svg");?>
                    </span>
                <div class="info">
                    <p><?=PROUCT_ADDED_TO_COMPARE?></p>
                    <a href="/<?=$lclang?>/<?=COMPARE_URI?>/"><?=GO_TO?></a>
                    <div class="arrow"></div>
                </div>
            </div>
            <span class="ico add_to_favorite <?=(in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/Favorite.svg");?>
            </span>
        </div>
    </div>
    <div class="product__buttons-2 d-none">
        <div class="product__buttons-icons">
            <div class="tooltip">
                <span class="ico add_to_compare <?=(in_array($product->id, $_SESSION['compare'])) ? 'active' : ''?>" >
                    <?=file_get_contents(base_url()."/dist/img/icons/status.svg");?>
                </span>
                <div class="info">
                    <p><?=PROUCT_ADDED_TO_COMPARE?></p>
                    <a href="/<?=$lclang?>/<?=COMPARE_URI?>/"><?=GO_TO?></a>
                    <div class="arrow"></div>
                </div>
            </div>
            <span class="ico add_to_favorite <?=(in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/Favorite.svg");?>
            </span>
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
</div>
