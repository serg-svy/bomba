<?php $this->load->view("layouts/pages/breadcrumbs");?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<section class="listing">
    <div class="wrapper">
        <div class="listing__sides listing__sides171">
            <div class="side__lft category">
                <?php $this->load->view("layouts/pages/search/filters");?>
            </div>
            <div class="side__rht search__content">
                <div class="index-top">
                <?php if((!empty($left_banners) || !empty($right_banners) || !empty($mobile_banners))) {?>
                    <div class="sides">
                        <div class="top__slider left__banner">
                            <?php if($is_mobile){?>
                                <?php foreach ($mobile_banners as $mobile_banner) {?>
                                    <div class="photo">
                                        <img alt="" class="d-none" src="<?=newthumbs($mobile_banner->img, 'brand_banners', 328, 480, '328x480x1', 1)?>">
                                    </div>
                                <?php }?>
                            <?php } else {?>
                                <?php foreach ($left_banners as $left_banner) {?>
                                    <div class="photo">
                                        <img alt="" class="d-block" src="<?=newthumbs($left_banner->img, 'brand_banners', 760, 350, '760x350x1', 1)?>">
                                    </div>
                                <?php }?>
                            <?php }?>
                        </div>
                        <?php if(!empty($right_banners) and !$is_mobile) {?>
                            <div class="right__slider right__banner side__rht side__rht123">
                                <?php foreach ($right_banners as $right_banner) {?>
                                    <div class="photo">
                                        <img alt="" src="<?=newthumbs($right_banner->img, 'brand_banners', 328, 350, '328x350x1', 1)?>">
                                    </div>
                                <?php }?>
                            </div>
                        <?php }?>
                    </div>
                    <br>
                <?php }?>
                </div>
                <?php if(isset($find_categories) and !empty($find_categories)){?>
                    <div class="categories__block categories__block-2">
                        <?php foreach($find_categories as $category) {?>
                            <a href="/<?=$lclang?>/<?=$menu['all'][11]->uri?>/<?=$category['uri']?>/<?=$get_params_for_search?>" class="categories__item">
                                <div class="photo"><img alt="" src="<?=newthumbs($category['img'], 'category', 115, 115, '115x115x0', 0)?>"></div>
                                <p class=""><?=$category['title']?></p>
                                <div class="count"><?=$category['count']?></div>
                            </a>
                        <?php }?>
                    </div>
                <?php }?>
                <div class="filters__sort filters__sort-l">
                    <div class="filter__btn d-none"><img src="/dist/img/icons/i36.svg"><span><?=FILTERS?></span>
                        <div class="num">?</div>
                    </div>
                    <div class="sort__block sort__block_place">
                        <div class="block__head">
                            <span><?=$stores[$get_store]->title?></span>
                            <img src="/dist/img/icons/dropdown.svg" alt="">
                        </div>
                        <div class="block__body">
                            <?php $temp_city_name = '';?>
                            <?php foreach($stores as $store){?>
                                <?php if ($temp_city_name!==$store->city_name) {?>
                                    <?php $temp_city_name = $store->city_name;?>
                                    <div class="sorth4"><?=$store->city_name?></div>
                                <?php }?>
                                <div class="checkbox__item">
                                    <label for="store-<?=$store->id?>">
                                        <input <?=($store->id == $get_store) ? 'checked' : ''?> type="radio" name="store-temp" id="store-<?=$store->id?>" value="<?=$store->id?>">
                                        <span class="spans">
                                            <span><?=$store->title?></span>&nbsp;
                                            <span><?=$store->address?></span>
                                        </span>
                                    </label>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="sort__count"><span><span class="product_count"><?=$count?></span> <?=PRODUCTS_OV?></span></div>
                    <div class="sort__block sort__num">
                        <div class="block__head"><span><?=$limit_view[$limit]?></span><img src="/dist/img/icons/dropdown.svg"></div>
                        <ul class="block__body">
                            <?php foreach($limit_view as $key=> $limit_item){?>
                                <li class="body__li" data-name="limit" data-key="<?=$key?>"><?=$limit_item?></li>
                            <?php }?>
                        </ul>
                    </div>
                    <div class="sort__block">
                        <div class="block__head"><span><?=$sorter_view[$sort]?></span><img src="/dist/img/icons/dropdown.svg"></div>
                        <ul class="block__body">
                            <?php foreach($sorter_view as $key=> $sorter_item){?>
                                <li class="body__li" data-name="sort" data-key="<?=$key?>"><?=$sorter_item?></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <span class="count-num d-none"><span class="product_count"><?=$count?></span> <?=PRODUCTS_OV?></span>
                <?php $this->load->view("layouts/pages/ajax__list");?>
                <?php $this->load->view("layouts/pages/category_spoilers");?>
            </div>
        </div>
    </div>
    <?php $this->load->view("layouts/pages/filters__scripts");?>
</section>
