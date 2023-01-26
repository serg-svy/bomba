<div class="shop__product bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
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
