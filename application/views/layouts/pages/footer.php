<footer class="footer">
    <div class="wrapper">
        <div class="footer__block">
            <?php foreach($bottom_categories as $bottom_category) {?>
                <div class="footer__item">
                    <div class="footer-h5"><?=$bottom_category->title?></div>
                    <ul>
                        <?php foreach ($menu['bottom'] as $bottom) { ?>
                            <?php if($bottom_category->id == $bottom->bottom_category_id) {?>
                                <li><a href="/<?=$lclang?>/<?=$bottom->uri?>/"><?=$bottom->title?></a></li>
                            <?php }?>
                        <?php }?>
                    </ul>
                </div>
            <?php }?>
            <div class="footer__item footer__item_last">
                <div class="footer-h5"><a href="tel:<?=str_replace(" ", "", CALL_CENTER_NUMBER)?>"><img src="/dist/img/icons/i15.svg" alt=""><span><?=CALL_CENTER_NUMBER?></span></a></div>
                <ul>
                    <li class="li-work"><?= nl2br(WORK_HOURS) ?></li>
                    <li><span><?=WRITE_TO_US?></span>
                        <ul>
                            <li><a href="<?=TELEGRAM_LINK ?>"><img src="/dist/img/icons/i16.svg" alt="telegram"></a></li>
                            <li><a href="<?=VIBER_LINK?>"><img src="/dist/img/icons/i18.svg" alt="viber"></a></li>
                        </ul>
                    </li>
                    <li><span><?=WE_ARE_IN_SOCIAL?></span>
                        <ul>
                            <li><a href="<?= FACEBOOK_LINK ?>"><img src="/dist/img/icons/i19.svg" alt="facebook"></a></li>
                            <li><a href="<?= YOUTUBE_LINK ?>"><img src="/dist/img/icons/i20.svg" alt="youtube"></a></li>
                            <li><a href="<?= INSTAGRAM_LINK ?>"><img src="/dist/img/icons/instagram.svg" alt="instagram"></a></li>
                        </ul>
                    </li>
                    <li class="payments"><img src="/dist/img/icons/visa.svg" alt="visa"><img src="/dist/img/icons/mscard.svg" alt="mastercard"></li>
                    <li>
                        <?php if($lclang == 'ru') {?>
                            <a href="https://ilab.md" target="_blank" class="footer__ilab">Разработано в ilab.md</a>
                        <?php }else{?>
                            <a href="https://ilab.md" target="_blank" class="footer__ilab">Site elaborat de ilab.md</a>
                        <?php }?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div class="footer__mob d-none">
    <ul>
        <li>
            <a href="/<?=$lclang?>/" class="<?=(uri(2) == '') ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/i48.svg");?>
                <span><?=HOME?></span>
            </a>
        </li>
        <li class="">
            <a href="/<?=$lclang?>/<?=CATEGORY_URI?>/" class="<?=(uri(2) == CATEGORY_URI) ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/i49.svg");?>
                <span><?=CATALOG?></span>
            </a>
        </li>
        <li class="basket cart_num" data-count="<?=$cart_count?>">
            <a href="/<?=$lclang?>/<?=$menu['all'][16]->uri?>/" class="<?=(uri(2) == $menu['all'][16]->uri) ? 'active' : ''?>">
                <?=file_get_contents(base_url()."/dist/img/icons/i50.svg");?>
                <span><?=$menu['all'][16]->title?></span>
            </a>
        </li>
    </ul>
</div>
