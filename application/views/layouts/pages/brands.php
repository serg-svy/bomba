<section class="brands">
    <div class="wrapper">
        <div class="index-h2"><?=POPULAR_BRANDS?></div>
        <div class="brands__block">
            <?php foreach($brands as $brand){?>
                <a href="/<?=$lclang?>/search/?brand[<?=$brand->id?>]=<?=$brand->id?>" class="brands__item">
                    <img src="<?=newthumbs($brand->id.'.jpg', 'brand', 100, 100, '100x100x0', 0)?>">
                </a>
            <?php }?>
        </div>
    </div>
</section>
