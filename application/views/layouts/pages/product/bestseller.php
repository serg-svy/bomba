<div class="bestseller_product bv_data" data-id="<?=$product->id?>" data-articol="<?=$product->articol?>">
    <div class="bestseller_title"><?=$product->bestseller_title?></div>
    <div class="product__brand"><img src="<?=newthumbs($product->brand_id.'.jpg', 'brand', 100, 18, '100x18x0', 0)?>" alt=""></div>
    <div class="product__photo">
        <a href="/<?=$lclang?>/<?=PRODUCT_URI?>/<?=$product->uri?>">
            <img src="<?=product_image('1.jpg', $product->articol, $product->first_color, 300, 195)?>" alt="<?=$product->title?>">
        </a>
        <?php if($product->labels) {?>
            <div class="photo__sticks">
                <?php foreach($product->labels as $label) {?>
                    <span class="span-top" style="background: <?=$label['color']?>"><span><?=$label['title']?></span></span>
                <?php }?>
            </div>
        <?php }?>
        <div class="product__buttons-2 d-none">
            <span class="ico add_to_favorite <?=(in_array($product->id, $_SESSION['favorite'])) ? 'active' : ''?>"><?=file_get_contents(base_url()."/dist/img/icons/Favorite.svg");?></span>
            <img class="brand" src="<?=newthumbs($product->brand_id.'.jpg', 'brand', 100, 18, '100x18x0', 0)?>" alt="">
        </div>
    </div>
    <div class="product__block">
        <div class="product__price">
            <?=numberFormat($product->discounted_price)?>,–
            <?php if($product->price > $product->discounted_price){?>
                <span class="product__reduction">
                    <span>
                        <?=REDUCTION?> <?=$product->price - $product->discounted_price?> <?=LEI?>
                    </span>
                </span>
            <?php }?>
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
