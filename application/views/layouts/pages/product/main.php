<div class="product__item bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
        <img class="delete delete_favorite" src="/dist/img/icons/Delete.svg">
        <div class="product__brand"><img src="<?=newthumbs($product->brand_id.'.jpg', 'brand', 100, 18, '100x18x0', 0)?>" alt=""></div>
        <?php if($product->labels) {?>
            <div class="product__stickers"></div>
        <?php }?>
        <div class="product__photo">
            <a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>/">
                <img src="<?=product_image('1.jpg', $product->articol, $product->first_color, 240, 200)?>" alt="<?=$product->title?>">
            </a>
            <?php if($product->labels) {?>
                <div class="photo__sticks">
                    <?php foreach((object) $product->labels as $label) {?>
                        <?php $label = (object) $label; ?>
                        <span class="span-top" style="background: <?=$label->color?>"><span><?=$label->title?></span></span>
                    <?php }?>
                </div>
            <?php }?>
            <?php if($product->display){?>
                <div class="product__stock-2"><?=IN_STOCK?></div>
            <?php } else {?>
                <div class="product__sold-2"><?=PRODUCT_SOLD?></div>
            <?php }?>
            <div class="product__reviews-2 d-none">
                <div class="reviews__stars reviews__stars-<?=$product->id?>"></div>
                <div class="reviews__count">
                    <?=generateFeedbackText($product->feedbacks_count, true);?>
                </div>
            </div>
            <div class="product__buttons-2 d-none">
                <div class="product__buttons-icons">
                    <div class="tooltip">
                        <span class="ico add_to_compare <?=(in_array($product->id, $_SESSION['compare'])) ? 'active' : ''?>">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="#A4A4A5" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7 2a1 1 0 012 0v12a1 1 0 11-2 0V2zM2 9a1 1 0 012 0v5a1 1 0 11-2 0V9zm11-5a1 1 0 00-1 1v9a1 1 0 102 0V5a1 1 0 00-1-1z"/></svg>
                        </span>
                        <div class="info">
                            <p><?=PROUCT_ADDED_TO_COMPARE?></p>
                            <a href="/<?=$lclang?>/<?=COMPARE_URI?>/"><?=GO_TO?></a>
                            <div class="arrow"></div>
                        </div>
                    </div>
                    <span class="ico add_to_favorite <?=(in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>">
                        <svg width="16" height="15" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" fill="#A4A4A5"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.3913C0 1.93655 2.04469 0 4.5 0C6.07643 0 7.22085 0.562156 8 1.32371C8.77915 0.562156 9.92357 0 11.5 0C13.9553 0 16 1.93655 16 4.3913C16 6.80024 14.6457 9.05843 13.1345 10.823C11.6026 12.6118 9.77448 14.0509 8.5394 14.842C8.2207 15.0462 7.81406 15.0529 7.48878 14.8594C6.2443 14.1192 4.40751 12.74 2.86902 10.9633C1.3438 9.20182 0 6.91903 0 4.3913ZM4.5 2C3.08932 2 2 3.10013 2 4.3913C2 6.21952 2.98953 8.04712 4.38098 9.65406C5.55116 11.0055 6.92683 12.1086 7.98194 12.8072C9.0464 12.0593 10.4352 10.9003 11.6155 9.52207C13.021 7.88089 14 6.08208 14 4.3913C14 3.10013 12.9107 2 11.5 2C9.9881 2 9.24483 2.74641 8.89443 3.44721C8.72503 3.786 8.37877 4 8 4C7.62123 4 7.27496 3.786 7.10557 3.44721C6.75517 2.74641 6.0119 2 4.5 2Z"/></svg>
                    </span>
                </div>
                <img class="brand" src="<?=newthumbs($product->brand_id.'.jpg', 'brand', 100, 18, '100x18x0', 0)?>" alt="">
            </div>
        </div>
        <div class="product__block">
            <div style="display:flex;align-items:center;gap:10px;">
                <?php if($product->display){?>
                    <div class="product__stock">
                        <?=IN_STOCK?>
                    </div>
                    <?php if ($product->qty == 1 and !$product->preorder) {?>
                        <div class="product__error">
                            <span><?= ONE_ITEM_LEFT ?></span>
                        </div>
                    <?php } ?>
                <?php } else {?>
                    <div class="product__sold"><?=PRODUCT_SOLD?></div>
                <?php }?>
            </div>
            <div class="product__price">
                <?=numberFormat($product->discounted_price)?>,–
                <?php if($product->uds_cashback) {?>
                    <span class="pls"><img src="/dist/img/icons/i34.svg" alt=""><span>+<?=$product->uds_cashback?></span></span>
                <?php }?>
            </div>
            <div class="product__old">
                <?php if($product->price > $product->discounted_price){?>
                    <del class="old__price"><?=numberFormat($product->price)?>,–</del>
                    <span class="old__sale">−<?=numberFormat($product->price - $product->discounted_price)?> <?=LEI?></span>
                <?php }?>
            </div>
            <div class="product__reviews">
                <div class="reviews__stars reviews__stars-<?=$product->id?>"></div>
                <div class="reviews__count">
                    <?=generateFeedbackText($product->feedbacks_count);?>
                </div>
            </div>
            <div class="product__name">
                <span><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>/"><?=$product->title?></a></span>
                <span><?=COURIER_TO?> <?= $deliveryCourier['city'] ?>: <?=$deliveryCourier['day']?></span>
                <span><?=PICKUP_TODAY?></span>
            </div>
            <?php if($product->partner_id > 1) {?>
                <div class="product__error" style="margin-top: 12px">
                    <span><?=PARTNER_PRODUCT?></span>
                </div>
            <?php }?>
            <?php if ($product->preorder) {?>
                <div class="product__error">
                    <img src="/dist/img/icons/Time.svg">
                    <span><?= str_replace('{days}', PREORDER_DAYS, PREORDER_TITLE) ?></span>
                </div>
            <?php } ?>
            <?php if ($product->price >= 1000  && !$product->preorder && in_array($product->partner_id, $credit_partner_ids)) { ?>
                <div class="product__error">
                    <?=BUY_IN_CREDIT?> <?=calculateCredit($product->discounted_price, MONTH_RATE, $credit)?>,– /<?=IN_MONTH?>
                </div>
            <?php }?>
        </div>
        <div class="product__buttons d-none">
            <?php if ($product->preorder) {?>
                <input type="submit" class="btn" value="<?=PREORDER?>">
            <?php }else{?>
                <?php if($product->display){?>
                    <input type="submit" class="<?=(in_array($product->id, array_column($_SESSION['cart'], 'id'))) ? 'btn4': 'btn'?> check_color_and_size" data-alternative="<?=IN_CART?>" value="<?=(in_array($product->id, array_column($_SESSION['cart'], 'id'))) ? IN_CART: TO_CART?>">
                <?php } else {?>
                    <button type="submit" class="btn-subscribe">
                        <img src="/dist/img/icons/Message.svg">
                        <?=SUBSCRIBE?>
                    </button>
                <?php }?>
            <?php }?>
            <div class="product__buttons-icons">
                <?php if($product->display){?>
                    <div class="tooltip">
                        <span class="ico add_to_compare <?=(!empty($_SESSION['compare']) and in_array($product->id, $_SESSION['compare'])) ? 'active' : ''?>">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="#A4A4A5" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7 2a1 1 0 012 0v12a1 1 0 11-2 0V2zM2 9a1 1 0 012 0v5a1 1 0 11-2 0V9zm11-5a1 1 0 00-1 1v9a1 1 0 102 0V5a1 1 0 00-1-1z"/></svg>
                        </span>
                        <div class="info">
                            <p><?=PROUCT_ADDED_TO_COMPARE?></p>
                            <a href="/<?=$lclang?>/<?=COMPARE_URI?>/"><?=GO_TO?></a>
                            <div class="arrow"></div>
                        </div>
                    </div>
                <?php }?>
                <span class="ico add_to_favorite <?=(!empty($_SESSION['favorite']) and in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>">
                    <svg width="16" height="15" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" fill="#A4A4A5"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.3913C0 1.93655 2.04469 0 4.5 0C6.07643 0 7.22085 0.562156 8 1.32371C8.77915 0.562156 9.92357 0 11.5 0C13.9553 0 16 1.93655 16 4.3913C16 6.80024 14.6457 9.05843 13.1345 10.823C11.6026 12.6118 9.77448 14.0509 8.5394 14.842C8.2207 15.0462 7.81406 15.0529 7.48878 14.8594C6.2443 14.1192 4.40751 12.74 2.86902 10.9633C1.3438 9.20182 0 6.91903 0 4.3913ZM4.5 2C3.08932 2 2 3.10013 2 4.3913C2 6.21952 2.98953 8.04712 4.38098 9.65406C5.55116 11.0055 6.92683 12.1086 7.98194 12.8072C9.0464 12.0593 10.4352 10.9003 11.6155 9.52207C13.021 7.88089 14 6.08208 14 4.3913C14 3.10013 12.9107 2 11.5 2C9.9881 2 9.24483 2.74641 8.89443 3.44721C8.72503 3.786 8.37877 4 8 4C7.62123 4 7.27496 3.786 7.10557 3.44721C6.75517 2.74641 6.0119 2 4.5 2Z"/></svg>
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
