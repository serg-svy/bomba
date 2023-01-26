<?php $this->load->view("layouts/pages/breadcrumbs");?>

<script src="/dist/js/jquery.star-rating-svg.min.js"></script>

<section class="listing favorites">
    <div class="wrapper">
        <div class="listing__sides listing__sides171">
            <div class="side__lft category">
                <?php $this->load->view("layouts/pages/filters");?>
            </div>
            <div class="side__rht">
                <div class="filters__checked ajax__filters">
                    <?php $this->load->view("layouts/pages/ajax__filters");?>
                </div>
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
                                    <h4><?=$store->city_name?></h4>
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
                <!--<div class="ajax__list">-->
                    <?php $this->load->view("layouts/pages/ajax__list");?>
                <!--</div>-->
            </div>
        </div>
    </div>
    <?php $this->load->view("layouts/pages/filters__scripts");?>
</section>
