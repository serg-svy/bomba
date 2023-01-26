<section class="shops">
    <div class="wrapper">
        <div class="index-h2"> <span><?=STORES?></span><!--<a href="#"><span>все магазины</span><img src="/dist/img/icons/i27.svg"></a>--></div>
        <div class="shops__block">
            <?php foreach($shops as $shop) {?>
                <a href="/<?=$lclang?>/<?=$menu['all'][26]->uri?>/<?=$shop->id?>/" class="shops__item">
                    <div class="photo__top"><img src="<?=newthumbs($shop->logo, 'shops', 125, 25, '125x25x0', 0)?>"></div>
                    <div class="item__name"><?=$shop->title?></div>
                    <div class="item__subname"><?=$shop->desc?></div>
                    <div class="item__inner">
                        <div class="inner__item d-block"><img src="<?=newthumbs($shop->items, 'shops', 224, 224, '224x224x1', 1)?>"></div>
                        <div class="inner__item d-none"><img src="<?=newthumbs($shop->mobile_items, 'shops', 296, 65, '296x65x1', 1)?>"></div>
                    </div>
                </a>
            <?php }?>
        </div>
    </div>
</section>
