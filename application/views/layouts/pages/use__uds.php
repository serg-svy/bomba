<div class="dop-vigoda__block use_uds">
    <div class="vigoda__top">
        <div class="top__item">
            <h4><?=ON_ACCOUNT?></h4>
            <p><img src="/dist/img/icons/i34.svg" alt=""><?=(int) $data->user->participant->points?> <?=BONUSES?></p>
        </div>
        <div class="top__item">
            <h4><?=CHARGED_FOR_PURCHASE?></h4>
            <p><img src="/dist/img/icons/i34.svg" alt="">+ <?=$data->cashback?> <?=BONUSES?></p>
        </div>
        <div class="top__item">
            <h4><a href="/<?=$data->lclang?>/uds/remove/" class="change_code"><?=ENTER_ANOTHER_CODE?></a></h4>
        </div>
    </div>
    <h4 class="title4"><?=PAY_SOME_PRODUCTS_WITH_UDS?></h4>
    <p class="uds_percent"><?=TEXT_UDS_PERCENT?></p>
    <div class="code-dus code-dus_used">
        <label for="uds"><?=NUMBER_OF_BONUSES?></label>
        <form method="post" action="/<?=$data->lclang?>/uds/set/">
            <input type="number" name="points" value="<?=$data->points_to_use?>" min="1" max="<?=$data->points_to_use?>" class="f_input">
            <button class="btn v2" id="set_uds" type="submit"><?=TOUSE?></button>
        </form>
    </div>
</div>
