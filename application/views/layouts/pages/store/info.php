<div class="city-block d-block">
    <div class="city-item-top">
        <div class="city-item-lft">
            <img class="arrow back_stores" src="/dist/img/icons/Arrow_16px.svg" alt="">
        </div>
        <div class="city-item-rht">
            <h5 class="title-h5">
                <?=$store->title?>
            </h5>
            <p class="text-p">
                <?=$store->address?>
            </p>
        </div>
    </div>
    <?php $pizza = explode("\n", $store->workhours);?>
    <div class="city-item">
        <h6 class="title-h6">
            <?=$pizza[0]?>
        </h6>
        <p class="text-p">
            <?=$pizza[1]?>
            <? if(isset($pizza[2])) echo '<br>'.$pizza[2]?>
        </p>
    </div>
    <div class="city-item">
        <h6 class="title-h6">
            <?=PHONE?>
        </h6>
        <p class="text-p">
            <a href="tel:<?=$store->phone?>"><?=$store->phone?></a>
        </p>
    </div>
    <?php $pizza = explode("\n", $store->parking);?>
    <div class="city-item">
        <h6 class="title-h6">
            <?=$pizza[0]?>
        </h6>
        <p class="text-p">
            <?=@$pizza[1]?>
        </p>
    </div>
    <?php $pizza = explode("\n", $store->troleibus);?>
    <div class="city-item">
        <h6 class="title-h6">
            <?=$pizza[0]?>
        </h6>
        <p class="text-p">
            <?=@$pizza[1]?>
        </p>
    </div>
    <?php foreach($store->images as $img) {?>
        <div class="city-item city-item-max-width">
            <img src="<?=newthumbs($img->img, 'store', 272, 175, '272x175x1', 1)?>" alt="">
        </div>
    <?php }?>
</div>
