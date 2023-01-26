<div class="shop__product shop__product__best bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
    <div class="bestseller_title">HIT</div>
    <div class="product__photo">
        <a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>">
            <img src="<?=product_image('1.jpg', $product->articol, $product->first_color, 300, 195)?>" alt="<?=$product->title?>">
        </a>
    </div>
    <div class="product__block">
        <div class="product__price">
            <?=numberFormat($product->discounted_price)?>,–
        </div>
        <div class="product__old">
            <?php if($product->price > $product->discounted_price){?>
                <del class="old__price"><?=numberFormat($product->price)?>,–</del>
                <span class="old__sale">−<?=numberFormat($product->price - $product->discounted_price)?> <?=LEI?></span>
            <?php }?>
        </div>
        <div class="product__name">
            <span><a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>"><?=$product->title?></a></span>
        </div>
    </div>
</div>
