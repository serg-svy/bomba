<div class="popup__city popup">
    <div class="popup__inner">
        <div class="popup__close"><img alt="delete" src="/dist/img/icons/Delete.svg"></div>
        <div class="popup__arrow__close">
            <img src="/dist/img/icons/i38.svg">
            <span><?=BACK?></span>
        </div>
        <div class="popup__title"><?=SELECT_LOCALITY?></div>
        <p class="index-popup__subtitle set_city_automatically" data-url="/<?=$lclang?>/ajax/set_city_automatically/">
            <img src="/dist/img/icons/i24.svg" alt="">
            <span><?=DETECT_AUTOMATICALLY?></span>
            <span class="city_not_found"></span>
        </p>
        <div class="index-popup__input">
            <input data-url="/<?=$lclang?>/ajax/get_city/" placeholder="<?=ENTER_LOCALITY?>">
        </div>
        <div class="index-popup__list">
            <ul class="city__list" data-url="/<?=$lclang?>/ajax/set_city/">
            </ul>
        </div>
    </div>
</div>
