<header <?php if($options[1]->data) {?>style="background-color: #<?=$options[1]->data?>" <?php }?> class="header header1">
    <div class="wrapper">
        <div class="header__top">
            <nav class="menu">
                <ul class="menu__ul">
                    <li class="menu__li"><a class="menu__link select_locality" href="#"><img src="/dist/img/icons/i1.svg" alt=""><p class="city_title"><?= $cities[$_SESSION['city_id']]->title ?></p></a></li>
                    <?php foreach ($menu['top'] as $item) {?>
                        <li class="menu__li">
                            <a class="menu__link" href="<?=($item->alternative_uri) ? $item->alternative_uri.$lclang : '/'.$lclang.'/'.$item->uri?>/">
                                <?php if(!empty($item->icon)) {?>
                                    <img src="/dist/img/icons/<?=$item->icon?>">
                                <?php }?>
                                <p><?=$item->title?></p>
                            </a>
                        </li>
                    <?php }?>
                    <li class="menu__li">
                        <div class="menu__link"><img src="/dist/img/icons/i21.svg" alt="">
                            <p><?=WRITE_TO_US?></p>
                            <div class="block__pp">
                                <div class="block__popup">
                                    <div class="block__social"><img src="/dist/img/icons/tg.svg" alt="telegram"><a href="<?=TELEGRAM_LINK ?>">Telegram</a></div>
                                    <div class="block__social"><img src="/dist/img/icons/vb.svg" alt="viber"><a href="<?=VIBER_LINK?>">Viber</a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="menu__li">
                        <a class="menu__link" href="tel:<?=str_replace(" ", "",CALL_CENTER_NUMBER)?>">
                            <p><?=CALL_CENTER_NUMBER?></p>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="languages">
                <?php select_language($clang, $lang_urls); ?>
            </div>
        </div>
    </div>
</header>
<header <?php if($options[1]->data) {?>style="background-color: #<?=$options[1]->data?>" <?php }?> class="header header2">
    <div class="wrapper">
        <div class="catalog__popup">
            <div class="catalog__inner">
                <div class="catalog__lft">
                    <ul class="lft__ul">
                        <?php foreach ($categories as $category) {?>
                            <?php if (empty($category['children'])) continue; ?>
                            <li class="lft__li"><?=$category['title']?></li>
                        <?php }?>
                    </ul>
                </div>
                <div class="catalog__rht">
                    <?php foreach ($categories as $category) {?>
                        <?php if (empty($category['children'])) continue; ?>
                        <div class="rht__li-item">
                            <div class="rht__li-item__inner">
                                <?php foreach ($category['children'] as $key => $child) {?>
                                    <?php if (empty($child['children']) || empty($child['title'])) continue; ?>
                                    <div class="rht__item">
                                        <picture>
                                            <img src="<?=newthumbs($child['img'], 'category', 48, 48, '48x48x0', 0)?>" alt="">
                                        </picture>
                                        <div class="menuh3"><a href="<?=category_link($lclang, $child)?>"><?=$child['title']?></a></div>
                                        <ul>
                                            <?php $i = 0;?>
                                            <?php foreach ($child['children'] as $child2) {?>
                                                <?php if($i < 5){?>
                                                    <li><a href="<?=category_link($lclang, $child2)?>"><?=$child2['title']?></a></li>
                                                <?php }?>
                                                <?php $i++; ?>
                                            <?php }?>
                                            <?php if($i > 5){?>
                                                <li><a style="color: #a4a4a5;font-size: 16px;cursor: pointer;" href="<?=category_link($lclang, $child)?>"><?=MORE?> <?=$i - 5?></a></li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="header__center">
            <a class="header__logo" href="<?=($lclang == 'ro') ? '/' : '/'.$lclang.'/'?>">
                <img style="width: 200px" src="/public/header_options/<?= $options[0]->data ?>" alt="">
                <img class="d-none" src="/dist/img/logo-mob.svg" alt="">
            </a>
            <div class="header__catalog">
                <img src="/dist/img/icons/i5.svg">
                <img class="d-none" src="/dist/img/icons/close-catalog.svg">
                <span><?=CATALOG?></span>
            </div>
            <div class="header__catalog_mob d-none"><?=MENU?></div>
            <div class="header__search">
                <span class="mobile-arrow"><?=file_get_contents(base_url()."/dist/img/icons/withe-arrow.svg");?></span>
                <form class="search_form" method="get"
                      data-keyup="/<?=$lclang?>/ajax/search/"
                      data-previous="/<?=$lclang?>/ajax/previous_search/"
                      action="/<?=$lclang?>/search/">
                    <input type="text" name="query" placeholder="<?=SEARCH?>" value="<?=$query?>" autocomplete="off">
                    <div class="dropdown__img">
                        <img src="/dist/img/icons/i6.svg" alt="">
                    </div>
                    <img src="/dist/img/icons/Delete.svg" alt="" class="close__search" style="display: <?=($query) ? 'inline' : 'none'?>">
                    <button type="submit" class="loupe__img">
                        <img src="/dist/img/icons/i7.svg" alt="">
                    </button>
                    <div class="popup__search popup__input">
                    </div>
                    <div class="popup__search popup__arrow">
                        <div class="bvh2"><?=SEARCH_IN?>...</div>
                        <div class="arrow__block">
                            <?php $this->load->view('layouts/pages/search/categories');?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="header__buttons">
                <a class="buttons__item buttons__item_likes compare_count" href="/<?=$lclang?>/<?=$menu['all'][15]->uri?>/">
                    <img src="/dist/img/icons/compare.svg" alt="">
                    <p data-count="<?=count($_SESSION['compare'])?>"><?=$menu['all'][15]->title?></p>
                </a>
                <a class="buttons__item buttons__item_likes favorite_count" href="/<?=$lclang?>/<?=$menu['all'][21]->uri?>/">
                    <img src="/dist/img/icons/i9.svg" alt="">
                    <p data-count="<?=count($_SESSION['favorite'])?>"><?=$menu['all'][21]->title?></p>
                </a>
                <a class="buttons__item buttons__item_basket" href="/<?=$lclang?>/<?=$menu['all'][16]->uri?>/">
                    <img src="/dist/img/icons/i11.svg" alt="">
                    <p class="cart_num" data-count="<?=$cart_count?>"><?=$menu['all'][16]->title?></p>
                </a>
            </div>
            <div class="languages d-none"><?php select_language($clang, $lang_urls); ?></div>
        </div>
    </div>
</header>
<header <?php if($options[1]->data) {?>style="background-color: #<?=$options[1]->data?>" <?php }?> class="header header3">
    <div class="wrapper">
        <div class="header__bottom">
            <ul class="bottom__ul">
                <li class="bottom__li">
                    <a class="bottom__link" href="/<?=$lclang?>/<?=$menu['all'][10]->uri?>/" style="align-items: center;">
                        <img src="/dist/img/icons/i12.svg" style="width: 30px">
                        <span><?=$menu['all'][10]->title?></span>
                    </a>
                </li>
                <?php foreach ($categories as $category) {?>
                    <?php if (empty($category['children'])) continue; ?>
                    <li class="bottom__li"><a class="bottom__link" href="<?=category_link($lclang, $category)?>/"><?=wrapCatName($category['title'])?></a>
                        <?php if(!empty($category['children'])){?>
                            <div class="catalog__popup_mini">
                                <div class="catalog__inner">
                                    <div class="catalog__rht">
                                        <div class="rht__li-item">
                                            <div class="rht__li-item__inner">
                                                <?php foreach ($category['children'] as $key => $child) {?>
                                                    <?php if (empty($child['children']) || empty($child['title'])) continue; ?>
                                                    <div class="rht__item">
                                                        <picture>
                                                            <img src="<?=newthumbs($child['img'], 'category', 48, 48, '48x48x0', 0)?>" alt="">
                                                        </picture>
                                                        <div class="menuh3"><a href="<?=category_link($lclang, $child)?>"><?= $child['title']?></a></div>
                                                        <ul>
                                                            <?php foreach ($child['children'] as $child2) {?>
                                                                <li><a href="<?=category_link($lclang, $child2)?>"><?=$child2['title']?></a></li>
                                                            <?php }?>
                                                        </ul>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>
</header>
<div class="menu__bgd">
    <div class="header__menu">
        <div class="menu__close"><img src="/dist/img/icons/i28.svg" alt=""></div>
        <p>Ближайший магазин<span> работает до 20:30</span></p>
        <div class="place select_locality"><img src="/dist/img/icons/i32.svg" alt=""><span class="city_title"><?= $cities[$_SESSION['city_id']]->title ?></span></div>
        <ul>
            <li><img src="/dist/img/icons/status.svg" alt=""><span><a href="/<?=$lclang?>/<?=$menu['all'][15]->uri?>/"><?=$menu['all'][15]->title?></a></span></li>
            <li><img src="/dist/img/icons/i30.svg" alt=""><span><a href="/<?=$lclang?>/<?=$menu['all'][21]->uri?>/"><?=$menu['all'][21]->title?></a></span></li>
            <li><img src="/dist/img/icons/i31.svg" alt=""><span><a href="/<?=$lclang?>/<?=$menu['all'][14]->uri?>/"><?=$menu['all'][14]->title?></a></span></li>
            <?php foreach($bottom_categories as $bottom_category) {?>
                <li>
                    <?=$bottom_category->title?>
                </li>
                <div class="header__menu-dropdown">
                    <?php foreach ($menu['bottom'] as $bottom) { ?>
                        <?php if($bottom_category->id == $bottom->bottom_category_id) {?>
                            <a href="/<?=$lclang?>/<?=$bottom->uri?>/"><?=$bottom->title?></a>
                        <?php }?>
                    <?php }?>
                </div>
            <?php }?>
        </ul>
        <div class="menu__bottom">
            <div class="lft"><a href="tel:<?=str_replace(" ", "", CALL_CENTER_NUMBER)?>"> <img src="/dist/img/icons/i53.svg"><span><?=CALL_CENTER_NUMBER?></span></a>
                <p><?= nl2br(WORK_HOURS) ?></p>
            </div>
            <div class="rht"><img src="/dist/img/icons/visa.svg"><img src="/dist/img/icons/mscard.svg"></div>
        </div>
    </div>
</div>
<?php if($is_mobile) {?>
    <script>
        let scrollY;
        $(function () {
            scrollY = window.scrollY;
            window.addEventListener("scroll", function(){
                if(window.scrollY > scrollY){
                    $('.header2').css('top', '-100px');
                } else {
                    $('.header2').css('top', '-1px');
                }
                scrollY = window.scrollY;
            });
        });
    </script>
    <style>
        .header2 {
            transition: top 0.5s linear 0s;
        }
    </style>
<?php }?>
<style>
    .dropdown__img.loading img {
        width: 25px;
        margin-left: -8px;
        content: url(/dist/img/search_loading.gif);
    }
</style>
