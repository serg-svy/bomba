<div class="square__item product__item bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
    <div class="product__buttons-2 d-none">
        <div class="product__buttons-icons">
            <span class="ico add_to_favorite <?=(in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/Favorite.svg");?>
            </span>
            <span class="ico add_to_compare <?=(in_array($product->id, $_SESSION['compare'])) ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/status.svg");?>
            </span>
        </div>
    </div>
    <a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>/" style="display: block" class="product__photo"><img src="<?=product_image('1.jpg', $product->articol, $product->first_color, 132, 132)?>" alt="<?=$product->title?>"></a>
    <div class="product__price"><?=numberFormat($product->discounted_price)?>,–</div>
        <?php if($product->price > $product->discounted_price){?>
            <div class="product__old">
                <del class="old__price">
                    <?=numberFormat($product->price)?>,–
                </del>
                <span class="old__sale">
                    −<?=numberFormat($product->price - $product->discounted_price)?> <?=LEI?>
                </span>
            </div>
        <?php }?>
    <div class="product__name"><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>/"><?=$product->title?></a></div>
</div>
